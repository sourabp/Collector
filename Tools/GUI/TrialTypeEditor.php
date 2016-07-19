<?php

/*
  	GUI - Trial type editor by Anthony Haffey

	Collector
    A program for running experiments on the web
    Copyright 2012-2015 Mikey Garcia & Nate Kornell

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 3 as published by
    the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>

    */
    

?>

<style>
  input[type="time"]::-webkit-clear-button {
    display: none;
  }
  
  h1{
    color       : grey;
    font-style  : bold;
    text-shadow : 0px 1px 0px rgba(255,255,255,.3), 0px -1px 0px rgba(0,0,0,.7);
  }
  
  input{
    border-radius : 10px;
    padding       : 5px;
  }
  
  .elementProperty{
    padding     : 5px;
    font-family : arial;
    text-shadow : 0px 1.5px 0px rgba(255,255,255,.3), 0px -1.5px 0px rgba(0,0,0,.7);
    font-size   : 18px;
    color  : grey;
  }
  
  
  #controlPanel {
    position         :  absolute;
    border           :  2px solid black;
    left             :  810px;  
    width            :  500px;
    top              :  300px;
    height           :  800px;
    padding          :  10px;
    opacity          :  .9;
    border-radius    :  10px;
    z-index          :  5;
    background-color :  #cfebfd;
  }
  
  #controlPanelRibbon {
    padding: 5px;
    border-bottom: 1px solid gray;
  }

  #controlPanelItems > div {
    display: none;
    height: 400px;
    border: none;
  }
  
  #displayEditor{
    width:400px;
    border:2px solid black;
    border-radius: 25px;
    padding:25px;
  }
  #elementArray{
    width:400px;
    height: 800px;
    top: 300px;
    left: 1350px;
    position:absolute;
    border:2px solid black;
    border-radius: 25px;
    padding:50px;  
  }
  
  #elementTypeList{
    position:absolute;
    left:100px;
    top:250px;
  }
  #keyboardResponses{
    width:400px;
    height:500px;
    position:absolute;
    padding:10px;
    border: 2px solid black;
    border-radius: 25px;
  }  
  #interactionEditor{
    width:400px;
    height:500px;
    position:absolute;
    border:2px solid black;
    border-radius: 25px;
    padding:25px;
  }
  
  #trialEditor{
    width:800px;
    height:800px;
    position:absolute;
    top:300px;
    left:0px;
    border:2px solid black;
    border-radius: 25px;
    padding:50px;
  }
  #trialEditor:hover{
    border: 2px solid blue;
  }

  #trialTypeName{
    position:absolute;
    left: 100px;
    top: 200px;
    width: 600px;
    line-height: 1;
    font-size:30px;
    padding: 5px;
  }
  
  .elementButton{
    background-color:blue;
    color:white;
  }
  .elementButton:hover{
    background-color:white;
    color:black;
  }
  .elementButtonSelected{
    
    background-color:green;
    color:white;
  }
  .elementButtonSelected:hover{
    background-color:transparent;
    color:black;
  }
  

  
  .inputElement{
    border:1px solid #cccccc;
  }

  .inputElementSelected{
    background-color:green;
  }
  
  .mediaElement{
    color:blue;
    width:160px;
    height: 160px;
    line-height:70px;
    border: 2px solid blue;
    border-radius: 10px;
    padding:10px;    
  }

  .mediaElementSelected{
    color:green;
    width:160px;
    height: 160px;
    line-height:70px;
    border: 4px solid green;    
    border-radius: 10px;
    padding:10px;    

  }
  .onsetOffset{
    color:grey;
  }
  
  .textElement{
    font-size:30px;
    color:black;
  }

  .textElementSelected {
    color:black;
    font-size:30px;
    font-weight:bold;
  }
  
  <!--  	pointer-events: none; !-->
  
</style>

<?php     
  
  /* * * * * * * *
  * Configurations
  * * * * * * * */

  $elementScale   =   8; // as the interface for inserting elements if 800px x 800px, and we are scaling to a 100% height or width (800/100 = 8)
  
  // load function 
  // save function
  // rename function
  // else scratch 
  
  $trialTypeElementsPhp;
  
  function loadTrialType($filename){
    
    global $_DATA;  // would ideally make this an input that could be dynamically altered
    
    $file_contents                                        =   file_get_contents("GUI/newTrialTypes/".$filename);
    $trialTypeElementsPhp                                 =   json_decode($file_contents);
    $_DATA['trialTypeEditor']['currentTrialTypeName']     =   str_replace('.txt','',$filename);
    return  ($trialTypeElementsPhp);
  }
  
  
  
  
  function saveTrialType($elementArray){
  
    global $_DATA,$_PATH,$_POST;
    
    $trialTypeElementsPhp=json_decode($elementArray);  
        
  
    /*
    if(!isset($trialTypeElementsPhp->trialTypeName)){
      $trialTypeElementsPhp->trialTypeName=$_POST['trialTypeName'];
    }
    */
    
    // Renaming files if task name has changed 
    if(isset($_DATA['trialTypeEditor']['currentTrialTypeName'])){   // checking if there is there a long term value for the name to check against
      /* does the new name match the old name*/
      if(strcmp($_DATA['trialTypeEditor']['currentTrialTypeName'], $trialTypeElementsPhp->trialTypeName)!=0){                 //i.e. a new trialType name
        if(file_exists("GUI/newTrialTypes/"       .     $_DATA['trialTypeEditor']['currentTrialTypeName'] . ".txt")){
          unlink("GUI/newTrialTypes/"             .     $_DATA['trialTypeEditor']['currentTrialTypeName'] . ".txt");          //Delete original file here            
          unlink($_PATH->get('Custom Trial Types')."/". $_DATA['trialTypeEditor']['currentTrialTypeName']  . "/display.php"); //deleting php file
          rmdir($_PATH->get('Custom Trial Types') ."/". $_DATA['trialTypeEditor']['currentTrialTypeName']);                   //deleting directory
        }
        $_DATA['trialTypeEditor']['currentTrialTypeName']=$trialTypeElementsPhp->trialTypeName;                               //identify correct name here
      }  
    }
    
    // saving backup of task (.txt), schematic of task (.txt) and task (.php)
    
    
    file_put_contents('GUI/newTrialTypes/backup.txt',$elementArray);                                                //creating backup - currently not being used :-\
    file_put_contents("GUI/newTrialTypes/".$_DATA['trialTypeEditor']['currentTrialTypeName'].'.txt',$elementArray); //actual act of saving
    require('createTrialType.php');                                                                                 //php file
    
    return $trialTypeElementsPhp;
  }
  
  
  /*sorting out whether we are working from scratch, have just saved a file, or are loading a file */
  
  /* loading */
  if(isset($_POST['loadButton'])){   //load first

    $trialTypeElementsPhp = loadTrialType($_POST['trialTypeLoaded'],$_DATA['trialTypeEditor']['currentTrialTypeName']);
  
  } else {
    /* saving */
    if(!empty($_POST['elementArray'])){ // have just saved
       
      $trialTypeElementsPhp=saveTrialType($_POST['elementArray']);

    } else { 
      /* creating a file from scratch */
      require ("guiClasses.php"); //not sure we need this;  maybe the guiClasses can be tidied up  
      $trialTypeElementsPhp                               =   new trialTypeElements();        
      $_DATA['trialTypeEditor']['currentTrialTypeName']   =   '';
    }
  }    
  $jsontrialTypeElements                                  =   json_encode($trialTypeElementsPhp);                       //to use for javascript manipulations
  $_DATA['trialTypeEditor']['currentTrialTypeFilename']   =   $_DATA['trialTypeEditor']['currentTrialTypeName'].".txt";
     
  // list of trial types the user can edit
  if (!is_dir("GUI/newTrialTypes")) {
      $trialTypesList = array();
  } else {
      $trialTypesList = scandir("GUI/newTrialTypes");
      $trialTypesList = array_slice($trialTypesList,2);
  }
  
//  var_dump($trialTypeElementsPhp); // so not in the code that is passed to javascript
  ?>

<form method="post">
  <textarea id="currentGuiSheetPage" name="currentGuiSheetPage" style="display:none">TrialTypeEditor</textarea>  
  <textarea id="trialTypeName" placeholder="[insert name of trial type here]" onkeyup="updateTrialTypeElements()"><?php 
echo $_DATA['trialTypeEditor']['currentTrialTypeName']
?></textarea>
    
  <div id="elementTypeList">
    <br>
    <span>
      <input id="mediaButton"   type="button" class="elementButton" value="Media"   onclick="elementType('media')">
      <input id="textButton"    type="button" class="elementButton" value="Text"    onclick="elementType('text')">
      <input id="inputButton"   type="button" class="elementButton" value="Input"   onclick="elementType('input')">
      <input id="complexButton" type="button" class="elementButton" value="Complex" onclick="alert('This will include more code heavy elements, e.g. progress bars, and will be in a later release')">
      <input id="selectButton"  type="button" class="elementButton" value="Select"  onclick="elementType('select')">

    </span>
    <span style="position:relative; left:420px">

      <input  type="submit" class="collectorButton" id="saveButton" name="saveButton" value="Save">
      <button type="button" class="collectorButton" onclick="saveTextAsFile()">download JSON</button>
    <?php 
      if(count($trialTypesList)>0){
      ?>
          <select id="trialTypeLoading" name="trialTypeLoaded">
            <option>-select a trial type-</option>
            <?php foreach($trialTypesList as $trialType){
              echo "<option>$trialType</option>";
            }
            ?>
          </select> 
          <input type="button" id="loadButton" class="collectorButton" value="Load">
          <input type="submit" id="loadButtonAction" name="loadButton" class="collectorButton" value="Load" style="display:none">
      <?php
      }
    ?>  

      </span>

    </div>

  <div id="trialEditor" onMouseMove="mouseMovingFunctions()" onclick="getPositions(); tryCreateElement()">

<?php

  foreach($trialTypeElementsPhp->elements as $elementKey=>$element){
    if($element!=NULL){ //ideally I'll tidy it up so that there are no null elements 
      /* identify if input or other type of element */
      if(isset($element->userInputType)){
                
        echo "<input id='element$elementKey' type='".$element->userInputType."'";
      } else {
        echo "<div id='element$elementKey' class='".$element->trialElementType."Element'";
      }
      echo "    style='position:absolute;
                width   : ".($elementScale*$element->width)."px;
                height  : ".($elementScale*$element->height)."px;
                left    : ".($elementScale*$element->xPosition)."px;
                top     : ".($elementScale*$element->yPosition)."px;
                ";
      if (isset($element->textColor)){
        echo "color:$element->textColor;
              font-family:$element->textFont;
              background-color:$element->textBack;
              font-size:".($element->textSize)."px;"; // look into this when I've finalised spacing for interfaces
      }
      echo "'   onclick       =   'clickElement($elementKey)'";
      if(isset($element->userInputType)){
        if($element->userInputType=="Text"){
          echo "placeholder   =   '".$element->stimulus."' readonly>";
        } else {  // it's a "Button"
          echo "value         =   '".$element->stimulus."'>";
        }
      }else {
        // it's not an input, so it's a div we're writing
        echo ">".$element->stimulus."</div>";        
      }
    }
  }
  
  if(isset($_DATA['trialTypeEditor']['currentTrialTypeName'])){
    if(file_exists("GUI/newTrialTypes/".$_DATA['trialTypeEditor']['currentTrialTypeName'].".txt")){
      $loadedContents=file_get_contents("GUI/newTrialTypes/".$_DATA['trialTypeEditor']['currentTrialTypeName'].".txt");
    } else {
      $loadedContents='';
    }
  }
  
?>
</div>

<!-- the helper bar !-->
<div id="controlPanel">
  <div id="controlPanelRibbon">
    <button type="button" class="collectorButton" value="#displayEditor"      style="display:none"  id="displayEditorButton">Display </button>
    <button type="button" class="collectorButton" value="#interactionEditor"  style="display:none"  id="interactionEditorButton">Interaction </button>
    <button type="button" class="collectorButton" value="#keyboardResponses"                        >Keyboard           </button>
    <button type="button" class="collectorButton" value="#responseInputs"                           >Responses          </button>
  </div>  
  
  
  <div id="controlPanelItems">
    <div id = "responseInputs">
      <h1> <b>Click Responses</b> that you have coded </h2>
      <textarea name="responseValues" id="responseValuesId" style="display:none"></textarea>
      <div id="responseValuesTidyId" class="elementProperty"></div>
    </div>

    <div id="displayEditor">
      <h1>Display editor <br><span style="font-size:20px" id="currentStimType">No Element Selected</span></h1>
      <table id="displaySettings" style="display:none">
        <tr title="we may allow editing of element names in a later release but without careful coding it can make code break easily">
          <td class="elementProperty">Element Name</td>
          <td><input type="text" id="elementNameValue" onkeyup="adjustElementName()" readonly style="background-color:#d3d3d3"></td>
        </tr>
        <div id="userInputSettings">
          <tr title="If you want to refer to a stimulus list then write '$cue' or '$answer' etc.">
            <td id="inputStimTypeCell" class="elementProperty">Input Type</td>
            <td id="inputStimSelectCell">
              <select style="padding:5px" id="mediaTypeValue" onchange="changeMediaType()">
                <option>Pic</option>
                <option>Audio</option>
                <option>Video</option>
              </select>
            
              <select id="userInputTypeValue" onchange="adjustUserInputType()">
                <option>Text</option>
                <option>Button</option> 
              </select>
            </td>
          </tr>
        </div>
        
        <tr title="If you want to refer to a stimulus list then write '[stimulus1]' or '[stimulus2]' etc.">
          <td class="elementProperty">Stimulus</td>
          <td><input id="stimInputValue" type="text" onkeyup="adjustStimulus()"></td>
        </tr>
        <tr>
          <td class="elementProperty">Width</td><td><input id="elementWidth" type="number" value="20" min="1" max="100" onchange="adjustWidth()">%</td>
          <td></td>
        </tr>
        <tr>
          <td class="elementProperty">Height</td><td><input id="elementHeight" type="number" value="20" min="1" max="100" onchange="adjustHeight()">%</td>
          <td></td>
        </tr>
        <tr title="this position is based on the left of the element">
          <td class="elementProperty">X-Position</td>
          <td><input id="xPosId" type="number" min="1" max="100" step="1" onchange="adjustXPos()">%</td>
          <td></td>
        </tr>
        <tr title="this position is based on the top of the element">
          <td class="elementProperty">Y-Position</td>
          <td><input id="yPosId" type="number" min="1" max="100" step="1" onchange="adjustYPos()">%</td>
          <td></td>
        </tr>
        <tr title="change this value to bring the element to forward or backward (to allow it to be on top of or behind other elements">
          <td class="elementProperty">Z-Position</td>
          <td><input id="zPosId" type="number" step="1" min="0" onchange="adjustZPos()"></td>
        </tr>
        <tr title="how long do you want until the element appears on screen?">
          <td class="elementProperty">Onset time</td>
          <td><input id="onsetId" placeholder="seconds" class="onsetOffset" type="text" onkeyup="adjustTime('onset')"></td>
        </tr>
        <tr title="if you want the element to disappear after a certain amount of time, change from 00:00">
          <td class="elementProperty">Offset time</td>
          <td><input id="offsetId" placeholder="seconds" class="onsetOffset" type="text" onkeyup="adjustTime('offset')"></td>
        </tr>
        <tr>
          <td><input id="deleteButton" type="button" value="delete" class="collectorButton"></td>
        </tr>       
      </table>
    </div>

    <div id="keyboardResponses">
      <h1>Keyboard responses</h1>
        accepted keyboard response(s) <input id="acceptedKeyboardResponses" name="acceptedKeyboardResponses" onkeyup="adjustKeyboard()"><br>
        proceed when an accepted key is pressed <input id="proceedKeyboardResponses" type="checkbox" onchange="adjustKeyboard()"> 
    </div>

    
    <div id="interactionEditor">
      <h1> Interaction Editor </h1>
       
      <div id="interactionEditorConfiguration"> 
        <table>
          <tr title="What actions to other elements do you want when clicking on this element? E.G. hide'(element1)'">
            <td class="elementProperty">Click outcomes:</td>
            <td>
              <select id="clickOutcomesActionId" onchange="adjustClickOutcomes(); supportClickOutcomes()">
                <option></option>
                <option>show</option>
                <option>hide</option>
                <option title="if you want this element to be (part of) your response">response</option>
              </select>
              <select id="clickOutcomesElementId" onchange="adjustOutcomeElement()">
                <option></option>
              </select>
              <input id="responseValueId" placeholder="[insert desired value here]" style="display:none" onkeyup="adjustClickOutcomes()">
              <span id="respNoSpanId" title="If you want multiple elements to contribute to the response, order the responses in the order you want them in the output" style="display:none"> <br>Resp No <input id="responseNoId" type="number" min="0" value="1" style="width:50px" onchange="adjustResponseOrder(trialTypeElements['responses']); adjustClickOutcomes() ">
              </span>
            </td>
          </tr>   
          <tr>
         <!--   <td> <input type="button" class="collectorButton" id="addDeleteFunctionButton0" value="Add Function" onclick="addDeleteFunction(0)"> </td> !-->
          </tr>
          <tr>
            <td class="elementProperty">Proceed Click</td>
            <td><input id="clickProceedId" title="if you want the trial to proceed when you click on this element, check this box" type="checkbox" onclick="adjustClickProceed()"></td>
          </tr>
        </table>
      </div>
    </div>
    


  </div>  
</div>

  <textarea id="elementArray" name="elementArray"><?=$loadedContents?></textarea>

</form>

<script src="GUI/trialTypeFunctions.js"></script>

<script>

/* Configurations and preparing global variables */
var elementScale = 8; // config


var currentElement      =   0;                                              //assumes that we are working from scratch
var trialTypeElements   =   <?= $jsontrialTypeElements ?>;                  //the object containing all the trialTypeInformation
var inputElementType;                                                       //the type of element that is currently selected to be added to the task. "Select" also included
var elementNo           =   Object.size(trialTypeElements['elements'])-1;   //elements are numbered, e.g. "element0","element1"
var currentResponseNo   =   0;                                              //this needs to be updated whenever you click on an element;
var inputButtonArray    =   ["media","text","input","select"];

elementType('select');                                                      // by default you are in select mode, not creating an element





/* * *
/ I may want to include more code within document.ready function?
* * */

$(document).ready(function() {
  $("#controlPanelRibbon button").click(function() { // when clicking on a button within the control panel ribbon
    var targetElementID = this.value;                // use the value of the clicked button
    
    $("#controlPanelItems > div").hide();            // hide all of the interface in the control panel
    
    $(targetElementID).show();                       // except the one for the clicked button
    
  });
  
  // initiating response array once the page is loaded
  if(typeof(trialTypeElements['responses']=='undefined')){
    trialTypeElements['responses'] = [[]];
  } else {
    updateClickResponseValues("initiate",trialTypeElements['responses']);    
  }
  
});


/* structuring code

  have a file for function definitions
    - try to pass in objects through functions rather than refer to global variables

*/



/* * * * *
* button clicking functions
* * * * * */

$("#deleteButton").on("click", function() {
  delConf   =   confirm ("Are you sure you wish to delete?");
  if (delConf == true){
    var element = document.getElementById("element"+currentElement);
    $("#displaySettings").hide();
    element.parentNode.removeChild(element);
    
    trialTypeElements['elements'].splice(currentElement,1);
    
    currentStimType.innerHTML   =   "No Element Selected";
    updateTrialTypeElements();    

    
    $("#interactionEditorButton").hide();
    $("#displayEditorButton").hide();
  }
});

$("#loadButton").on("click",function(){
  if(trialTypeLoading.value=="-select a trial type-"){
    alert ("You must select a trial type to proceed!!");
  } else {
    $("#loadButtonAction").click();
  }
});

/* * * * 
* Saving using CTRL S - doesn't suppress event in Firefox
* * * */

$(window).bind('keydown', function(event) {
  if (event.ctrlKey || event.metaKey) {
    switch (String.fromCharCode(event.which).toLowerCase()) {
    case 's':
      event.preventDefault();
      alert('Saving');
      $("#saveButton").click();
      break;
    }
  }
});

/* this function will be added in a later release 
function addDeleteFunction(x){
  alert ("The ability to have multiple click actions for a single element will be added in a later release");
  
  if(document.getElementById("addDeleteFunctionButton"+x).value=="Add Function"){  
    document.getElementById("addDeleteFunctionButton"+x).value="Delete Function";
    
    // update "clickOutcomesAction" for element
      // check what elements are within it
      alert (trialTypeElements['elements'][currentElement]['clickOutcomesAction']); // this has only one element, need to restructure to have multiple elements within it.     
    
  } else {
    document.getElementById("addDeleteFunctionButton"+x).value="Add Function";    
  }
  
}
*/


/* * * * 
*  loading inputs with trialTypeElements settings 
*/


  /* Keyboard */

  acceptedKeyboardResponses.value     =   trialTypeElements['keyboard'].acceptedResponses;
  proceedKeyboardResponses.checked    =   trialTypeElements['keyboard'].proceed;

  

  // identifying which response clicking on the element contributes to, e.g. - whether clicking on element1 contributes to Response1 or Response2
  function updateClickResponseValues(initiateUpdate,responseArray){
    
    var currentElementName = $("#elementNameValue").val();
    
    var newRespElement = checkIfResponseListContainsName(responseArray, currentElementName);                        // check if this element is part of new response
    
    responseValuesTidyId.innerHTML="";                                                                              // wipe user friendly list of responses associated with elements
    
    
    responseArray = addNewElementToResponseArray(responseArray,initiateUpdate,newRespElement,currentElementName);   // new Element being added to response array         

    
    responseArray = tidyResponseArray(responseArray);                                                               // remove null values from response array and populates user
                                                                                                                    // friendly response array
    
    trialTypeElements['elements'][currentElement] = updateTrialTypeElementsResponses(trialTypeElements['elements'][currentElement],initiateUpdate);  // update trialTypeElements with input values
   
    $("#responseValuesId").val(JSON.stringify(responseArray));                                                                        // update the hidden response code that is 
                                                                                                                                      //used for - apparently nothing anymore...
    
  }
    
  function checkIfResponseListContainsName(responseArray, currentElementName) {
    // check whether the current element is already in the response array
    for(var i=0; i<responseArray.length;i++){
      if(responseArray[i].indexOf(currentElementName)!=-1){
        return false;      //if it is 
      }
    } 
    
    return true;
  }

  function addNewElementToResponseArray(responseArray,initiate,newRespElement,currentElementName){
    if(initiate!="initiate" && newRespElement && currentElementName!=""){                                                      // don't load this at startup
      
        responseArray[0][responseArray[0].length] =   currentElementName;   // add it to the end of the first array in responseArray
        responseNoId.value                        =   0;                    // reset response number to zero (as it is being added to the first array)

    }    
    return responseArray;
  }

  function tidyResponseArray(responseArray){
    
    for(i=0; i<responseArray.length; i++){    
      /* tidying */
      responseArray[i]  =   removeNullValues(responseArray[i]);
      
      /* could add code here to remove blank arrays, but be careful - user may have a blank array in the middle of the response array, which - if deleted, will mess up the order of the arrays. You have been warned. */
      
      /* writing out array in Responses area in form that is legible to user */      
      responseValuesTidyId.innerHTML  +=  "Response "+i+":" +responseArray[i]+"<br>";
           
    }
    
    return responseArray;
  
  }

  function updateTrialTypeElementsResponses(trialTypeElementStem,initiateUpdate){

    if(initiateUpdate!="initiate"){ // not relevant when initiating page
      trialTypeElementStem['responseValue']  =   responseValueId.value;
      trialTypeElementStem['responseNo']     =   responseNoId.value;
      updateTrialTypeElements();    
    }
    return trialTypeElementStem;
  
  }

  
  /* adjust position of element within responseArray */
  function adjustResponseOrder(responseArray){
       
    var newPos; // the position the element will fit within the array selected. E.g. if the element is added to response 1, newPos will be at the end of response 1.
    
    // add to array that exists or create a new array
    /* adding to array that already exists */
    if(typeof responseArray[responseNoId.value] != 'undefined'){
      newPos = responseArray[responseNoId.value].length;
    } else {
    
    /* creating a new array within responseArray */    
      responseArray[responseNoId.value]   =   [];
      newPos                              =   0;
    }
      
    /* place null value where the element used to be (before being moved). This is tidied later. */
    for(i=0; i<responseArray.length;i++){
      if(responseArray[i].indexOf(elementNameValue.value)!=-1){
        responseArray[i][responseArray[i].indexOf(elementNameValue.value)] = null;
      }
    }
    
    //now that the element's been removed from it's original position, we can add it to the array.
    responseArray[responseNoId.value][newPos]   =   elementNameValue.value;  
    updateClickResponseValues("update",responseArray);                                             
  }

  /* adding elements to the trialType if not clicking on them for editing */
  function tryCreateElement(){        
    if(inputElementType !=  "select"){

      elementNo++; // we're not selecting an element, so we're creating one, which means we need a new element number.
      
      xPos  =  Math.round((_mouseX)/elementScale);
      yPos  =  Math.round((_mouseY)/elementScale);
      
      createElementFunction();                          //  add new element to trialType 
      populateDefaultValues();                          //  add default values to this new element
            
      var elemIndex=trialTypeElements['elements'][elementNo];
      
      /* add attributes depending on what type of element */
      if(inputElementType ==  "media"){
        elemIndex['mediaType']   =    "Pic"; // default assumption
      }
      
      if(inputElementType ==  "text" | inputElementType=="input"){
         // to allow more concise coding of the variables
        elemIndex['textSize']    =    12;
        elemIndex['textColor']   =    '';
        elemIndex['textFont']    =    '';
        elemIndex['textBack']    =    '';
      }
      
      if(inputElementType=="input"){
        elemIndex['userInputType']  = "Text";
        elemIndex['height']         = "5"; //overwriting default height
      }
         
      updateTrialTypeElements();
    }
  }
  
  function createElementFunction(){

    if(inputElementType=="input"){
      
      document.getElementById("trialEditor").innerHTML+=
        "<input class='inputElement' type='text' id='element"+elementNo+"' style='position: absolute; width:"+elementScale*20+"px; left:"+_mouseX+"px;top:"+_mouseY+"px' onclick='clickElement("+elementNo+")' name='"+inputElementType+"' readonly>";  
      
    } else {
      // it is not an input, so can create a span instead //
      document.getElementById("trialEditor").innerHTML+=
        "<span class='"+inputElementType+"Element' id='element"+elementNo+"' style='position: absolute; left:"+_mouseX+"px;top:"+_mouseY+"px; z-index:"+elementNo+"' onclick='clickElement("+elementNo+")' name='"+inputElementType+"'>"+inputElementType+"</span>";
    }
  
  }
  
  function populateDefaultValues(){
    trialTypeElements['elements'][elementNo] = {
      width                 :   20, 
      height                :   20,
      xPosition             :   xPos,
      yPosition             :   yPos,
      zPosition             :   elementNo,
      elementName           :   'element'+elementNo,
      stimulus              :   'not yet added',
      response              :   false,
      trialElementType      :   inputElementType, // repetition here
      clickOutcomesAction   :   '',
      clickOutcomesElement  :   '',
      proceed               :   false,
    };        
  }



  /* updating the trialType */
  
  backupTrialTypeName   =   trialTypeName.value;    //in case the user tries an illegal name

  function updateTrialTypeElements(){
    
    $("#trialTypeName").val(trialTypeName.value.replace(/ /g,""));      //  remove whitespace from title
    var trialName   =   $("#trialTypeName").val();                      //  apply this to later code in place of "trialTypeName.value";
    
    trialName=removeIllegalCharacters(trialName);                       //remove illegal characters from title

    trialTypeElements['trialTypeName']                  =   trialName;
    
    document.getElementById("elementArray").innerHTML   =   JSON.stringify(trialTypeElements,  null, 2);
    
    elementType("select");
  }

  function removeIllegalCharacters(thisString){
    var illegalChars        =   ['.',' '];              // this probably should be expanded
    var illegalCharPresent  =   false;
    for (var i  = 0; i  < illegalChars.length; i++){
      if(thisString.indexOf(illegalChars[i]) !=  -1){
        alert("Illegal character in name, reverting to acceptable version");
        illegalCharPresent  =   true;
        thisString           =   backupTrialTypeName;
        $("#trialTypeName").val(thisString);            // this will have to change if we use this function on anything other than the title
      }   
    }
    if(illegalCharPresent   ==    false){
      backupTrialTypeName  =  trialTypeName.value;    
    }
    
    return thisString;
  }

  function changeMediaType(){
    trialTypeElements['elements'][currentElement]['mediaType']  =   mediaTypeValue.value;
    updateTrialTypeElements();
    // code here to change image cue if we include media images
  }

  function clickElement(elementX){
    if(inputElementType=="select"){
      
      $("#displayEditorButton").show(1000);                             //this button is hidden at start and after deleting elements
      $("#interactionEditorButton").show(1000);                         //this button is hidden at start and after deleting elements
                            
      currentElement =  elementX;                                       // this is in order to update the global variable "currentElement";
      
      selectUnselectElements(elementNo,currentElement);                 // selecting and unselecting elements
            
      showDisplayEditor();                                              // and hide the other editors
        
      loadConfigs();                                                    // this loads the configurations for the editor
                      
      currentElementAttributes=trialTypeElements['elements'][elementX]; // to simplify later code
      
      editElement(currentElementAttributes);                             // preparing user interface for editing element

    }

  } 
  
  function selectUnselectElements(elementNo,currentElement){
    for(var i=0;i<=elementNo;i++){
      if(i==currentElement){
        document.getElementById("element"+i).className    =   trialTypeElements['elements'][i]['trialElementType']  +   "ElementSelected";
      } else {
        
        if (trialTypeElements['elements'][i] != null) { //code to check whether the element exists or not
          document.getElementById("element"+i).className  =   trialTypeElements['elements'][i]['trialElementType']  +   "Element";
        }
      }
    }
  }

  function showDisplayEditor(){
    $("#displaySettings").hide();
    $("#interactionEditorConfiguration").hide();
    $("#userInputSettings").hide();
    $("#controlPanelItems > div").hide();
    $("#displayEditor").show();  
  }
  
  function editElement(currentElementAttributes){
    switch (currentElementAttributes['trialElementType']){

    case "media":
        document.getElementById("inputStimTypeCell").innerHTML="Media Type";
        $("#displaySettings").show();
        $("#interactionEditorConfiguration").show();
        document.getElementById('userInputTypeValue').style.visibility="hidden";
        currentStimType.innerHTML="Media";
//        inputStimSelectCell.innerHTML='<select style="padding:5px" id="userInputTypeValue" onchange="changeMediaType()"><option>Pic</option><option>Audio</option><option>Video</option></select>';
      break

      case "text":
        $("#displaySettings").show();
        $("#interactionEditorConfiguration").show();
        document.getElementById('mediaTypeValue').style.visibility="hidden";
        currentStimType.innerHTML="Text";
        document.getElementById("inputStimTypeCell").innerHTML="Text properties";
        // userInputTypeValue is being used for both media and input types - this could probably be tidier by keeping them separate
        inputStimSelectCell.innerHTML=
          '<input id="mediaTypeValue" style="display:none">'+
          '<table>'+ 
            '<tr>'+
              '<td>font size</td>'+
              '<td><input type="number" id="textSizeId" onchange="adjustTextSize()" value=12 min="1" style="width:50px">px</td>'+
            '</tr>'+
            '<tr>'+
              '<td>color</td>'+
              '<td><input type="text" id="textColorId" onkeyup="adjustTextColor()" placeholder="color"></td>'+
            '</tr>'+
            '<tr>'+
              '<td>font</td>'+
              '<td><input type="text" id="textFontId" onkeyup="adjustTextFont()" placeholder="font"></td>'+
            '</tr>'+
            '<tr>'+
              '<td>background-color</td>'+
              '<td><input type="text" id="textBackId" onkeyup="adjustTextBack()" placeholder="background-color"></td>'+
            '</tr>'
          '</table>';
               
        //rather than embed it in above text, i've listed these values below for improved legibility
        textFontId.value   =  currentElementAttributes.textFont;
        textColorId.value  =  currentElementAttributes.textColor;
        textSizeId.value   =  currentElementAttributes.textSize;
        textBackId.value   =  currentElementAttributes.textBack;
        
      break      

      case "input":      
        document.getElementById("inputStimTypeCell").innerHTML="Input Type";
        $("#displaySettings").show();
        $("#interactionEditorConfiguration").show();        
        document.getElementById('mediaTypeValue').style.visibility="invisible";
        document.getElementById('userInputTypeValue').style.visibility="visible";
        currentStimType.innerHTML="Input";
        textTableSize       =     '<tr id="textTableSizeRow">'+
                                    '<td>size</td>'+
                                    '<td><input type="number" id="textSizeId" onchange="adjustTextSize()" value=12 min="1" style="width:50px">px</td><br>'+
                                  '</tr>';
        textTableColor      =     '<tr id="textTableColorRow">'+
                                    '<td>color</td>'+
                                    '<td><input type="text" id="textColorId" onkeyup="adjustTextColor()" placeholder="e.g. red, #FF0000" ></td>'+
                                  '</tr>';
        textTableFont       =     '<tr id="textTableFontRow">'+
                                    '<td>font</td>'+
                                    '<td><input type="text" id="textFontId" onkeyup="adjustTextFont()" placeholder="font"></td>'+
                                  '</tr>';
        textTableBackColor  =     '<tr id="textTableBackRow">'+
                                    '<td>background-color</td>'+
                                    '<td><input type="text" id="textBackId" onkeyup="adjustTextBack()" placeholder="background-color"></td>'+
                                  '</tr>';
                          
        /* if handling text, not button input, then need to remove font color and background-color due to inflexibility of placeholders */

        inputStimSelectCell.innerHTML=
          '<select id="userInputTypeValue" onchange="adjustUserInputType()">'+
            '<option>Text</option>'+
            '<option>Button</option>'+
          '</select>'+
          '</td><br>'+
          '<table>'+
            textTableSize+
            textTableColor+
            textTableFont+
            textTableBackColor+
          '</table>';

        //rather than embed it in above text, i've listed these values below for improved legibility
        textFontId.value          =   currentElementAttributes.textFont;
        textColorId.value         =   currentElementAttributes.textColor;
        textSizeId.value          =   currentElementAttributes.textSize;
        textBackId.value          =   currentElementAttributes.textBack;
        document.getElementById("userInputTypeValue").value   =   currentElementAttributes.userInputType;
        
        if(document.getElementById("userInputTypeValue").value    ==    "Text"){
          $('#textTableColorRow').hide();
          $('#textTableBackRow').hide();
        } else {
          $('#textTableColorRow').show();
          $('#textTableBackRow').show();
        }
        
          
        // might add check box and radio in a later release
      break      
    
    }
  }

  function loadConfigs(){

    currentElementAttributes=trialTypeElements['elements'][currentElement]; //to make following code more concise

    console.dir(currentElementAttributes.mediaType);
        
    /* deciding which part of interactionEditor to show  - this may need to be more flexible when more interactive features are added*/ 
    if (currentElementAttributes.clickOutcomesAction=="response"){
      $("#clickOutcomesElementId").hide();
      $("#responseValueId").show();
      $("#respNoSpanId").show();
    
      if(typeof(currentElementAttributes.responseValue)!="undefined"){
        responseValueId.value = currentElementAttributes.responseValue;
        responseNoId.value    = currentElementAttributes.responseNo;
        clickProceedId.checked= currentElementAttributes.proceed;
      } else {
        responseValueId.value = ""
        responseNoId.value    = 0;
        clickProceedId.checked= false;
      }
      updateClickResponseValues("update",trialTypeElements['responses']);

    
    } else {
      $("#clickOutcomesElementId").show();
      $("#responseValueId").hide();
      $("#respNoSpanId").hide();
      populateClickElements();    
    }

    elementNameValue.value        =   currentElementAttributes.elementName;
    
    if(typeof(currentElementAttributes.mediaType)!="undefined"){
    
      console.dir(currentElementAttributes.mediaType);
      console.dir(mediaTypeValue.value);

      mediaTypeValue.value      =   currentElementAttributes.mediaType;
      
      console.dir(currentElementAttributes.mediaType);
      console.dir(mediaTypeValue.value);

    }
    
    if(typeof(currentElementAttributes.userInputType)!="undefined"){
      userInputTypeValue.value      =   currentElementAttributes.userInputType;
    }
    
    
    stimInputValue.value          =   currentElementAttributes.stimulus;
    elementWidth.value            =   currentElementAttributes.width;
    elementHeight.value           =   currentElementAttributes.height;
    
    /* positions */
    xPosId.value                  =   currentElementAttributes.xPosition;
    yPosId.value                  =   currentElementAttributes.yPosition;
    zPosId.value                  =   currentElementAttributes.zPosition; 
    
    /* click events */
    clickOutcomesActionId.value   =   currentElementAttributes.clickOutcomesAction;
    clickOutcomesElementId.value  =   currentElementAttributes.clickOutcomesElement;

    /* Timings */
    if(typeof(currentElementAttributes.onsetTime) == 'undefined'){
      $('#onsetId').val("");
    } else {    
      $('#onsetId').val(currentElementAttributes.onsetTime);
    }
    
    if(typeof(currentElementAttributes.onsetTime) == 'undefined'){
      $('#offsetId').val("");
    } else {
      $('#offsetId').val(currentElementAttributes.offsetTime); 
    }  
  }



  function populateClickElements(){
    removeOptions(document.getElementById("clickOutcomesElementId"));   

    var option                      =   document.createElement("option");
    option.text                     =   '';
    document.getElementById("clickOutcomesElementId").add(option);
    clickOutcomesElementId.value    =   trialTypeElements['elements'][currentElement].clickOutcomesElement;  
    var elementList                 =   [];

    
    for(x in trialTypeElements['elements']){
      
      // here be the bug //
      
      if (trialTypeElements['elements'][x] != null){
        elementList.push(trialTypeElements['elements'][x].elementName); //may become redundant
        var option    =   document.createElement("option");
        option.text   =   trialTypeElements['elements'][x].elementName;
        option.value  =   trialTypeElements['elements'][x].elementName; 
        document.getElementById("clickOutcomesElementId").add(option);  
      }
    }
  }

  /* Which element type are you adding to the trial */
  function elementType(x){
    inputElementType=x;
    for(i=0;i<inputButtonArray.length;i++){
      if(inputButtonArray[i]==x){
        document.getElementById(inputButtonArray[i]+"Button").className="elementButtonSelected";
      } else {
          if (typeof ("element"+i) != 'undefined') { //code to check whether the element exists or not
            document.getElementById(inputButtonArray[i]+"Button").className="elementButton";
          }     
      }
    }
    if(x!="select"){
      $("#displaySettings").hide();
      $("#userInputTypeValue").value="n/a";

      currentStimType.innerHTML="No Element Selected";
      // for all elements revert formatting to element
      for(i=0;i<=elementNo;i++){
          if (typeof trialTypeElements['elements'][i] != 'undefined') { //code to check whether the element exists or not
            document.getElementById("element"+i).className=trialTypeElements['elements'][i]['trialElementType']+"Element";
          }      
      }
    }  
  }


  /* mouse functions */
  
  function getPositions(ev) { 
  if (ev == null) { ev = window.event }
    var offset = $("#trialEditor").offset(); 
    _mouseX = ev.pageX;
    _mouseY = ev.pageY;
    _mouseX -= offset.left;
    _mouseY -= offset.top;
     
  }

  function mouseMovingFunctions(){
    if(inputElementType=="select"){
      
      /* text element style */
      var css   =  '.textElement:hover{ border-color        :     black;'+
                                        'background-color   :     transparent;'+
                                        'text-shadow        :     -1px -1px 0 #000,1px -1px 0 #000,-1px 1px 0 #000,1px 1px 0 #000; }';
      
      applynewStyle();
 
      /* media element style */
      var css   =   '.mediaElement:hover{ border-color      :     white;'+
                                          'background-color :     green;'+
                                          'color            :     white }';
      
      applynewStyle();

      
      /* input elemnt style */
      var css   =   '.inputElement:hover{ border-color      : green;'+
                                          'background-color : green;'+
                                          'color            : blue }';
                                          
      applynewStyle();
   }     
      
     
    /* change all element styles to nonHover version */ 
    if(inputElementType!="select"){
      //text elements
      var css   =   '.textElement:hover{  border-color      :   transparent;'+
                                          'background-color :   transparent;'+
                                          'color            :   blue}';
      applynewStyle();

      //media elements
      var css   =   '.mediaElement:hover{ border-color      :   blue;'+
                                          'background-color :   transparent;'+
                                          'color:blue }';
      applynewStyle();
      
      //input elements
      var css   =   '.inputElement:hover{ border            :   1px solid #cccccc;'+
                                          'background-color :   white;'+
                                          'color            :   white}';
      applynewStyle();
    }
    //keeping this function local;
    function applynewStyle(){
      style = document.createElement('style');

      if (style.styleSheet) {
          style.styleSheet.cssText = css;
      } else {
          style.appendChild(document.createTextNode(css));
      }
      document.getElementsByTagName('head')[0].appendChild(style);  
    }
  }
  

  var showHideRequestInput=false;
  $("#showRequestOptionsId").on("click", function(){
    if(showHideRequestInput==false){
      showHideRequestInput=true;
      $("#newFunctionTable").show();
    } else {
      showHideRequestInput=false;
      $("#newFunctionTable").hide();
    }
  });  
</script>
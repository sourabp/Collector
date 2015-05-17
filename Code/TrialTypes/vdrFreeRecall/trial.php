<?php
	$compTime = 60;					// time in seconds to use for 'computer' timing

	$prompt = str_ireplace(array($cue, $answer), array('$cue', '$answer'), $text);      // undo this change, since we are doing something a little non-standard here
    $prompts = explode('|', $prompt);
?>
  <style>
    .vdr-vcenter    {   position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); }
    #content { width: auto; }
  </style>
  

    <div class="prompt"><?= trim($prompts[0]) ?></div>
<?php
    if (isset($prompts[1])) {
        $cues = explode('|', $cue);
        $answers = explode('|', $answer);
        foreach ($cues as $i => $thisCue) {
            echo str_replace(array('$cue', '$answer'), array($thisCue, $answers[$i]), $prompts[1]);
        }
    }
    
    
    
    $input = 'one';
    
    $settings = explode('|', $settings);
    foreach ($settings as $setting) {
        if ($test = removeLabel($setting, 'input')) {
            $test = strtolower($test);
            if (($test === 'one') OR ($test === 'many') OR (is_numeric($test))) {
                $input = $test;
            } else {
                exit('Error: invalid "input" setting for trial type "'.$trialType.'", on trial '.$currentPos);
            }
        }
    }
    
    if ($input === 'one') {
        ?>
            <div>
                <textarea rows="20" cols="55" name="Response" class="precache collectorInput" wrap="physical" value=""></textarea>
                <br><button class="collectorButton collectorAdvance" id="FormSubmitButton" autofocus>Submit</button>
            </div>
        <?php
    } elseif ($input === 'many') {
        ?>
            <style>
                .freeRecallArea {   display: inline-block;  width: 850px;   text-align: left;   }
                .freeRecallArea input { width: 192px;   margin: 4px;    padding: 4px;}
            </style>
            
            <div class="textcenter"><!--
             --><div class="freeRecallArea"><!--
                 --><?php
                        $answerCount = substr_count($answer, '|')+1;
                        for ($i=1; $i<=$answerCount; ++$i) {
                            echo '<input type="text" name="Response' . $i . '" autocomplete="off" class="noEnter"/>';
                        }
                    ?><!--
             --></div><br><button class="collectorButton collectorAdvance" id="FormSubmitButton" autofocus>Submit</button>
        <?php
    } else {
        ?>
            <style>
                .freeRecallArea {   display: inline-block;  width: 850px;   text-align: left;   }
                .freeRecallArea input { width: 192px;   margin: 4px;    padding: 4px;}
            </style>
            
            <div class="textcenter"><!--
             --><div class="freeRecallArea"><!--
                 --><?php
                        for ($i=1; $i<=$input; ++$i) {
                            echo '<input type="text" name="Response' . $i . '" autocomplete="off" class="noEnter"/>';
                        }
                    ?><!--
             --></div><br><button class="collectorButton collectorAdvance" id="FormSubmitButton" autofocus>Submit</button>
            </div>
        <?php
    }
?>

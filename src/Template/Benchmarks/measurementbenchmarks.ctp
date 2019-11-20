<?= $this->Html->script('measurementBenchmarks.js') ?>
<?= $this->Html->css('measurementBenchmarks.css') ?>
<?= $this->Html->css('cakemessages.css') ?>

<div class="message hidden" id='message'></div>

<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-scale" style="font-size: 20pt;"></span>  Measurement Benchmarks
     <a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
      <span class="glyphicon glyphicon-question-sign info" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
     </a></p>
    <hr>
    <div class="collapse" id="collapseInfo">
        <div class="card card-body">
            <p>This form is used to define the benchmark values for highlighting abnormal water quality data.</p>
            <p>To change a value:</p>
            <ol>
                <li>Click within the field containing the value to change</li>
                <li>Erase the current value</li>
                <li>Enter the desired value</li>
                <li>Click outside of the changed field</li>
            </ol>
        </div>
    </div>
    
    <table id='tableView' class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Measure</th>
                <th>Minimum Acceptable<br>Value</th>
                <th>Maximum Acceptable<br>Value</th>
            </tr>
        </thead>
        <tbody id="benchmarksTable">
	    <?php
		$row = 0;
		foreach ($Benchmarks as $benchmark):
		    ?>
		    <tr id='tr-<?= $benchmark->Measure?>'>
	    		<td id="<?php echo 'measure-' . $row;?>"><?= $benchmark->Measure ?></td>
	    		<td id='<?php echo 'td-' . $benchmark->Measure . '-min';?>'><?php
                        if($benchmark->Measure == 'Water Temperature (°C)' || 
                                $benchmark->Measure == 'Dissolved Oxygen (mg/L)' || $benchmark->Measure == 'pH') {
                            
                            $min = $benchmark->Minimum_Acceptable_Value;
                            if($min != 0.0){
                                $min = number_format((float)$min, 3, '.', '');
                            }
                            echo $this->Form->input('min-Minimum_Acceptable_Value', ['maxlength' => '7',
                                'id' => 'min-' . $row,
                                'class' => 'inputfields tableInput',
                                'size' => '11',
                                'value' => $min,
                                'style' => 'display: none',
                                'label' => [
                                    'style' => 'display: in-line; cursor: pointer',
                                    'class' => 'btn btn-thin inputHide',
                                    'text' => $min . ' '
                                ]
                            ]);
                        }
                            
                        ?>
                        </td>
                        <td id='<?php echo 'td-' . $benchmark->Measure . '-max'; ?>'><?php
                            $max = $benchmark->Maximum_Acceptable_Value;
                            if(!($benchmark->Measure === 'E. coli. (CFU/100 ml)') && !($benchmark->Measure === 'Turbidity (NTU)')){
                                $max = number_format((float)$max, 3, '.', '');
                            }
                            echo $this->Form->input('max-Maximum_Acceptable_Value', ['maxlength' => '7',
                                'id' => 'max-' . $row,
                                'class' => 'inputfields tableInput',
                                'size' => '11',
                                'value' => $max,
                                'style' => 'display: none',
                                'label' => [
                                    'style' => 'display: in-line; cursor: pointer',
                                    'class' => 'btn btn-thin inputHide',
                                    'text' => $max . ' '
                                ]
                            ]);
                        ?>
                        </td>
			    <?php
			    $row++;
			?>
		    </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?= $this->Html->script('measurementSettings.js') ?>
<?= $this->Html->css('measurementBenchmarks.css') ?>
<?= $this->Html->css('cakemessages.css') ?>

<div class="message hidden" id='message'></div>

<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-scale" style="font-size: 20pt;"></span>  Measurement Settings
    <table id='tableView' class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Measure Key</th>
				<th>Measure Name</th>
                <th>Minimum Benchmark</th>
                <th>Maximum Benchmark</th>
				<th>Minimum Detectable</th>
				<th>Maximum Detectable</th>
            </tr>
        </thead>
        <tbody id="benchmarksTable">
	    <?php
		$row = 0;
		foreach ($MeasurementSettings as $benchmark):
		    ?>
		    <tr id='tr-<?= $benchmark->measureKey?>'>
	    		<td id="<?php echo 'measure-' . $row;?>"><?= $benchmark->measureKey ?></td>
				<td id="<?php echo 'measureName-' . $row;?>"><?= $benchmark->measureName ?></td>
	    		<td id='<?php echo 'td-' . $benchmark->measureKey . '-min';?>'><?php
                            $min = $benchmark->benchmarkMinimum;
                            if ($min != 0.0) {
                                $min = number_format((float)$min, 3, '.', '');
                            }
                            echo $this->Form->input('min-benchmarkMinimum', ['maxlength' => '7',
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
                        ?>
					</td>
					<td id='<?php echo 'td-' . $benchmark->measureKey . '-max'; ?>'><?php
                            $max = $benchmark->benchmarkMaximum;
                            if(!($benchmark->measureKey == 'Ecoli') && !($benchmark->measureKey == 'Turbidity')){
                                $max = number_format((float)$max, 3, '.', '');
                            }	
                            echo $this->Form->input('max-benchmarkMaximum', ['maxlength' => '7',
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
						<td>
							<?php
							$detectionMin = $benchmark->detectionMinimum;
							echo $this->Form->input('min-detectionMinimum', ['maxlength' => '7',
                                'id' => 'detectionMinimum-' . $row,
                                'class' => 'inputfields tableInput',
                                'size' => '11',
                                'value' => $detectionMin,
                                'style' => 'display: none',
                                'label' => [
                                    'style' => 'display: in-line; cursor: pointer',
                                    'class' => 'btn btn-thin inputHide',
                                    'text' => $detectionMin . ' '
                                ]
                            ]);
							?>
						</td>
						<td>
							<?php
							$detectionMax = $benchmark->detectionMaximum;
							echo $this->Form->input('max-detectionMaximum', ['maxlength' => '7',
                                'id' => 'detectionMaximum-' . $row,
                                'class' => 'inputfields tableInput',
                                'size' => '11',
                                'value' => $detectionMax,
                                'style' => 'display: none',
                                'label' => [
                                    'style' => 'display: in-line; cursor: pointer',
                                    'class' => 'btn btn-thin inputHide',
                                    'text' => $detectionMax . ' '
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
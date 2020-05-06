<?php
if ($admin) {
	echo $this->Html->script("measurementSettings.js");
}
echo $this->Html->css("measurementBenchmarks.css");
echo $this->Html->css("cakemessages.css");
?>

<div class="message hidden" id="message"></div>

<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-scale" style="font-size: 20pt"></span>  Measurements
	<a href="/WQIS/pages/help#measurements">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt" title="Information" id="infoGlyph"></span>
	</a>
</p>
<table class="table table-striped table-responsive">
	<thead>
		<tr>
			<?php if ($admin) { echo "<th>Measure Key</th>"; } ?>
			<th>Measure Name</th>
			<th>Unit</th>
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
		<tr id="tr-<?= $benchmark->measureKey?>">
			<?php if ($admin) { echo "<td id=\"measure-" . $row . "\">" . $benchmark->measureKey . "</td>"; } ?>
			<td id="<?php echo "measureName-" . $row;?>"><?= $benchmark->measureName ?></td>
			<td id="<?php echo "td-" . $benchmark->measureKey . "-unit";?>">
			<?php
			$unit = $benchmark->unit;
			if ($admin) {
				echo $this->Form->control("unit", ["maxlength" => "7",
					"id" => "unit-" . $row,
					"class" => "inputfields tableInput",
					"size" => "11",
					"value" => $unit,
					"style" => "display: none",
					"label" => [
						"style" => "display: in-line; cursor: pointer",
						"class" => "btn btn-thin inputHide",
						"text" => $unit . " "
					]
				]);
			}
			else {
				echo $unit;
			}
			?>
			</td>
			<td id="<?php echo "td-" . $benchmark->measureKey . "-min";?>">
			<?php
			$min = $benchmark->benchmarkMinimum;
			if ($min != 0.0) {
				$min = number_format((float)$min, 3, ".", "");
			}
		
			if ($admin) {
				echo $this->Form->control("benchmarkMinimum", ["maxlength" => "7",
					"id" => "min-" . $row,
					"class" => "inputfields tableInput",
					"size" => "11",
					"value" => $min,
					"style" => "display: none",
					"label" => [
						"style" => "display: in-line; cursor: pointer",
						"class" => "btn btn-thin inputHide",
						"text" => $min . " "
					]
				]);
			}
			else {
				echo $min;
			}
			?>
			</td>
			<td id="<?php echo "td-" . $benchmark->measureKey . "-max"; ?>">
			<?php
				$max = $benchmark->benchmarkMaximum;
				if (!($benchmark->measureKey == "Ecoli") && !($benchmark->measureKey == "Turbidity")) {
					$max = number_format((float)$max, 3, ".", "");
				}
				
				if ($admin) {
					echo $this->Form->control("benchmarkMaximum", ["maxlength" => "7",
						"id" => "max-" . $row,
						"class" => "inputfields tableInput",
						"size" => "11",
						"value" => $max,
						"style" => "display: none",
						"label" => [
							"style" => "display: in-line; cursor: pointer",
							"class" => "btn btn-thin inputHide",
							"text" => $max . " "
						]
					]);
				}
				else {
					echo $max;
				}
				?>
			</td>
			<td>
			<?php
			$detectionMin = $benchmark->detectionMinimum;
			
			if ($admin) {
				echo $this->Form->control("detectionMinimum", ["maxlength" => "7",
					"id" => "detectionMinimum-" . $row,
					"class" => "inputfields tableInput",
					"size" => "11",
					"value" => $detectionMin,
					"style" => "display: none",
					"label" => [
						"style" => "display: in-line; cursor: pointer",
						"class" => "btn btn-thin inputHide",
						"text" => $detectionMin . " "
					]
				]);
			}
			else {
				echo $detectionMin;
			}
			?>
			</td>
			<td>
			<?php
			$detectionMax = $benchmark->detectionMaximum;
			
			if ($admin) {
				echo $this->Form->control("detectionMaximum", ["maxlength" => "7",
					"id" => "detectionMaximum-" . $row,
					"class" => "inputfields tableInput",
					"size" => "11",
					"value" => $detectionMax,
					"style" => "display: none",
					"label" => [
						"style" => "display: in-line; cursor: pointer",
						"class" => "btn btn-thin inputHide",
						"text" => $detectionMax . " "
					]
				]);
			}
			else {
				echo $detectionMax;
			}
			?>
			</td>
			<?php $row++; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
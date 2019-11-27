<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>

<?= $this->Html->css('loading.css') ?>

<?php
	//load variables from SESSION
	$sampleType = $_SESSION["tableType"];
	$siteLocation = $_SESSION["siteLocation"];

	//need to set a javascript variable listing the type of measurement
	echo "<script>var sampleType = \"" . $sampleType . "\";</script>";
    
	if ($admin) {
		echo $this->Html->script('tableedit.js');
    }
?>

<h3>
<?php
	$siteNumber = $this->Number->format($siteLocation->Site_Number);
	$siteName = h($siteLocation->Site_Name);
	$siteLocation = h($siteLocation->Site_Location);
	echo "$sampleType Measurements for $siteNumber $siteName - $siteLocation";
?>
</h3>
<table id='tableView' class="table table-striped table-responsive">
	<thead>
		<tr>
<?php
	if ($sampleType == "bacteria") {
		$tableHeaders = array("Date", "Sample number", "Ecoli raw count", "Ecoli (CFU/100 ml)", "Total Coliform Raw Count", "Total Coliform (CFU/100)", "Comments", "Actions");
		
		$fieldsPrototype = function($bacteriaSample) {return array($bacteriaSample->Date, $bacteriaSample->Sample_Number, $bacteriaSample->EcoliRawCount, $bacteriaSample->Ecoli, $bacteriaSample->TotalColiformRawCount, $bacteriaSample->TotalColiform);};
		$controlNames = array("Date", "samplenumber", "EcoliRawCount", "Ecoli", "TotalColiformRawCount", "TotalColiform");
		$lengths = array('11', '11', '5', '5', '5', '5');
		
		$commentName = "BacteriaComments";
		$commentPrototype = function($sample) {return $sample->BacteriaComments;};
	}
	elseif ($sampleType == "nutrient") {
		$tableHeaders = array("Date", "Sample number", "Nitrate/Nitrite (mg/L)", "Total Phosphorus (mg/L)", "Dissolved Reactive Phosphorus (mg/L)", "Ammonia (mg/L)", "Comments", "Actions");
		
		$fieldsPrototype = function($nutrientSample) {return array($nutrientSample->Date, $nutrientSample->Sample_Number, $nutrientSample->NitrateNitrite, $nutrientSample->Phosphorus, $nutrientSample->DRP, $nutrientSample->Ammonia);};
		$controlNames = array("Date", "samplenumber", "NitrateNitrite", "Phosphorus", "DRP", "Ammonia");
		$lengths = array('11', '11', '5', '5', '5', '5');
		
		$commentName = "NutrientComments";
		$commentPrototype = function($sample) {return $sample->NutrientComments;};
	}
	elseif ($sampleType == "pesticide") {
		$tableHeaders = array("Date", "Sample number", "Atrazine (µg/L)", "Alachlor (µg/L)", "Metolachlor (µg/L)", "Comments", "Actions");
		
		$fieldsPrototype = function($pesticideSample) {return array($pesticideSample->Date, $pesticideSample->Sample_Number, $pesticideSample->Atrazine, $pesticideSample->Alachlor, $pesticideSample->Metolachlor);};
		$controlNames = array("Date", "samplenumber", "Atrazine", "Alachlor", "Metolachlor");
		$lengths = array('11', '11', '5', '5', '5');
		
		$commentName = "PesticideComments";
		$commentPrototype = function($sample) {return $sample->PesticideComments;};
	}
	elseif ($sampleType == "physical") {
		$tableHeaders = array("Date", "Sample number", "Conductivity (mS/cm)", "Dissolved Oxygen (mg/L)", "Bridge to Water Height (in)", "pH", "Temperature (°C)", "Total Dissolved Solids (g/L)", "Turbidity (NTU)", "Comments", "Actions");
		
		$fieldsPrototype = function($physicalSample) {return array($physicalSample->Date, $physicalSample->Sample_Number, $physicalSample->Conductivity, $physicalSample->DO, $physicalSample->Bridge_to_Water_Height, $physicalSample->pH, $physicalSample->Water_Temp, $physicalSample->TDS, $physicalSample->Turbidity);};
		$controlNames = array("Date", "samplenumber", "Conductivity", "DO", "Bridge_to_Water_Height", "pH", "Water_Temp", "TDS", "Turbidity");
		$lengths = array('11', '11', '5', '5', '5', '4', '11', '4', '4');
		
		$commentName = "PhysicalComments";
		$commentPrototype = function($sample) {return $sample->PhysicalComments;};
	}
	
	for ($i=0; $i<count($tableHeaders); $i++) {
		$colHeader = $tableHeaders[$i];
		echo "<th>$colHeader</th>";
	}
?>
		</tr>
	</thead>
	<tbody>
<?php
	$row = 0;
	foreach ($samples as $sample):
		    ?>
		    <tr>
				<?php
				$fields = $fieldsPrototype($sample);
				for ($i=0; $i<count($fields); $i++) {
					?>
					<td>
					<div class="input text">
						<label style="display: in-line; cursor: pointer" class="btn btn-thin inputHide" for="<?php echo $controlNames[$i] . '-' . $row;?>"><?php echo $fields[$i];?> </label>
						<input type="text" name="<?php echo $controlNames[$i];?>-<?php echo $row;?>" maxlength=<?php echo $lengths[$i];?> size=<?php echo $lengths[$i];?> class="inputfields tableInput" style="display: none" id="<?php echo $controlNames[$i];?>-<?php echo $row;?>" value="<?php echo $fields[$i];?>"/>
					</div>
					</td>
					<?php
				}
				?>
				
				<td>
				<?=
				$this->Html->tag('span', "", [
				    'data-toggle' => 'modal',
				    'data-target' => '#myModal',
				    'class' => "comment glyphicon glyphicon-comment",
				    'id' => 'CommentIcon-' . $row,
				    'name' => 'CommentIcon-' . $row
				])
				?>
	    		</td>
				
	    		<td>
				<?=
				$this->Html->tag('span', $commentPrototype($sample), [
				    'id' => $commentName . '-' . $row,
				    'class' => $commentName . '-' . $row,
				    'hidden'
				])
				?>
				<?=
				$this->Html->tag('span', "", [
				    'class' => "delete glyphicon glyphicon-trash",
				    'id' => 'Delete-' . $row,
				    'name' => 'Delete-' . $row
				])
				?>
	    		</td>
			    <?php $row++; ?>
		    </tr>
		<?php endforeach;?>
	</tbody>
</table>
<hr>
<div class="container paginator">
	<ul class="row pagination">
		<div class="ml-2">
			<?= $this->Paginator->first('<< ' . __('first')) ?>
			<?= $this->Paginator->prev('< ' . __('previous ')) ?>
		</div>
		<div class="ml-2">
			<?= $this->Paginator->numbers() ?>
		</div>
		<div class="ml-2">
			<?= $this->Paginator->next(__('next') . ' >') ?>
			<?= $this->Paginator->last(__('last') . ' >>') ?>
		</div>
	</ul>
	<p class="row"><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Comments</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?=
					$this->Form->control('CommentInfo', ['maxlength' => '200',
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'data-row-number' => '',
					'class' => 'CommentInput',
					'id' => 'commentinfo',
					'name' => 'commentinfo',
					'value' => "",
					'type' => 'textarea',
					'label' => false
					])
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-basic" data-dismiss="modal" id="saveBtn">Save</button>
				<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
			</div>
		</div>
    </div>
</div>
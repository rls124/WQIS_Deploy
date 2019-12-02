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
	
	if ($sampleType == "bacteria") {
		$tableHeaders = array("Date", "Sample number", "Ecoli raw count", "Ecoli (CFU/100 ml)", "Total Coliform Raw Count", "Total Coliform (CFU/100)", "Comments");
		
		$fieldsPrototype = function($bacteriaSample) {return array($bacteriaSample->Date, $bacteriaSample->Sample_Number, $bacteriaSample->EcoliRawCount, $bacteriaSample->Ecoli, $bacteriaSample->TotalColiformRawCount, $bacteriaSample->TotalColiform, $bacteriaSample->BacteriaComments);};
		$controlNames = array("Date", "samplenumber", "EcoliRawCount", "Ecoli", "TotalColiformRawCount", "TotalColiform", "BacteriaComments");
		$lengths = array('11', '11', '5', '5', '5', '5', '200');
	}
	elseif ($sampleType == "nutrient") {
		$tableHeaders = array("Date", "Sample number", "Nitrate/Nitrite (mg/L)", "Total Phosphorus (mg/L)", "Dissolved Reactive Phosphorus (mg/L)", "Ammonia (mg/L)", "Comments");
		
		$fieldsPrototype = function($nutrientSample) {return array($nutrientSample->Date, $nutrientSample->Sample_Number, $nutrientSample->NitrateNitrite, $nutrientSample->Phosphorus, $nutrientSample->DRP, $nutrientSample->Ammonia, $nutrientSample->NutrientComments);};
		$controlNames = array("Date", "samplenumber", "NitrateNitrite", "Phosphorus", "DRP", "Ammonia", "NutrientComments");
		$lengths = array('11', '11', '5', '5', '5', '5', '200');
	}
	elseif ($sampleType == "pesticide") {
		$tableHeaders = array("Date", "Sample number", "Atrazine (µg/L)", "Alachlor (µg/L)", "Metolachlor (µg/L)", "Comments");
		
		$fieldsPrototype = function($pesticideSample) {return array($pesticideSample->Date, $pesticideSample->Sample_Number, $pesticideSample->Atrazine, $pesticideSample->Alachlor, $pesticideSample->Metolachlor, $pesticideSample->PesticideComments);};
		$controlNames = array("Date", "samplenumber", "Atrazine", "Alachlor", "Metolachlor", "PesticideComments");
		$lengths = array('11', '11', '5', '5', '5', '200');
	}
	elseif ($sampleType == "physical") {
		$tableHeaders = array("Date", "Sample number", "Conductivity (mS/cm)", "Dissolved Oxygen (mg/L)", "Bridge to Water Height (in)", "pH", "Temperature (°C)", "Total Dissolved Solids (g/L)", "Turbidity (NTU)", "Comments");
		
		$fieldsPrototype = function($physicalSample) {return array($physicalSample->Date, $physicalSample->Sample_Number, $physicalSample->Conductivity, $physicalSample->DO, $physicalSample->Bridge_to_Water_Height, $physicalSample->pH, $physicalSample->Water_Temp, $physicalSample->TDS, $physicalSample->Turbidity, $physicalSample->PesticideComments);};
		$controlNames = array("Date", "samplenumber", "Conductivity", "DO", "Bridge_to_Water_Height", "pH", "Water_Temp", "TDS", "Turbidity", "PhysicalComments");
		$lengths = array('11', '11', '5', '5', '5', '4', '11', '4', '4', '200');
	}

	$siteNumber = $this->Number->format($siteLocation->Site_Number);
	$siteName = h($siteLocation->Site_Name);
	$siteLocation = h($siteLocation->Site_Location);
	echo "<h3>$sampleType Measurements for $siteNumber $siteName - $siteLocation</h3>";
?>
<table id='tableView' class="table table-striped table-responsive">
	<thead>
		<tr>
<?php
	for ($i=0; $i<count($tableHeaders); $i++) {
		$colHeader = $tableHeaders[$i];
		echo "<th>$colHeader</th>";
	}
	
	if ($admin) {
		echo "<th>Actions</th>";
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
						<?php if ($admin) { ?>
							<label style="display: table-cell; cursor: pointer; white-space:normal !important;" class="btn btn-thin inputHide" for="<?php echo $controlNames[$i] . '-' . $row;?>"><?php echo $fields[$i];?> </label>
							<textarea rows="4" cols="50" class="tableInput" name="<?php echo $controlNames[$i];?>-<?php echo $row;?>" style="display: none" id="<?php echo $controlNames[$i];?>-<?php echo $row;?>"><?php echo $fields[$i];?></textarea>
						<?php
						}
						else {
						?>
						<span style="display: table-cell; white-space: normal !important;"><?php echo $fields[$i];?> </span>
						<?php } ?>
						</div>
					</td>
					<?php
				}
				?>
				
	    		<td>
				<?php
				if ($admin) { ?>
				<?=
				$this->Html->tag('span', "", [
				    'class' => "delete glyphicon glyphicon-trash",
				    'id' => 'Delete-' . $row,
				    'name' => 'Delete-' . $row
				])
				?>
				<?php } ?>
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
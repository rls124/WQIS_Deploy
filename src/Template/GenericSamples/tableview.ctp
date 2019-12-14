<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>
<?= $this->Html->css('loading.css') ?>

<?php
	//load variables from SESSION
	$sampleType = $_SESSION["tableType"];
	$siteLocation = $_SESSION["siteLocation"];
	
	$siteNumber = $this->Number->format($siteLocation->Site_Number);
	$siteName = h($siteLocation->Site_Name);
	$siteLocation = h($siteLocation->Site_Location);

	//need to set javascript variables for some of these values
	echo "<script>";
	echo "var sampleType = \"" . $sampleType . "\";";
	echo "var siteNumber = \"" . $siteNumber . "\";";
	echo "var amountEnter = \"" . $amountEnter . "\";";
	echo "var overUnderSelect = \"" . $overUnderSelect . "\";";
	echo "</script>";
    
	if ($admin) {
		echo $this->Html->script('tableedit.js');
    }
?>

<script>
$(document).ready(function () {
	$("#exportBtn").click(function () {
		var startDate = <?php echo "\"$startDate\""; ?>;
		var endDate = <?php echo "\"$endDate\""; ?>;
		var sites = [siteNumber];
		var measures = ['all'];

		$.ajax({
			type: "POST",
			url: "/WQIS/export/exportData",
			datatype: 'JSON',
			data: {
				'type': sampleType,
				'startDate': startDate,
				'endDate': endDate,
				'sites': sites,
				'measures': measures,
				'amountEnter': amountEnter,
				'overUnderSelect': overUnderSelect
			},
			success: function (response) {
				downloadFile(response, sampleType);
			},
			failure: function (response) {
				alert("Failed");
			}
		});	
	});
	
	function downloadFile(fileData, type) {
		if (fileData.length < 1) {
			return;
		}
		var csvContent = "data:text/csv;charset=utf-8,";
		var fields = Object.keys(fileData[0]);
		for (var i = 0; i < fileData.length; i++) {
			fileData[i]['Date'] = fileData[i]['Date'].substring(0, 10);
		}

		//If ID field exists, remove it
		if (fields[0] === "ID") {
			fields = fields.splice(1, fields.length);
		}
		//Make null values not have text
		var replacer = function (key, value) {
			return value === null ? '' : value;
		};

		var csv = fileData.map(function (row) {
			return fields.map(function (fieldName) {
				return JSON.stringify(row[fieldName], replacer);
			}).join(',');
		});
		fields[fields.indexOf('site_location_id')] = 'Site Number';
		// add header column
		csv.unshift(fields.join(','));

		csvContent += csv.join('\r\n');
		var encodedUri = encodeURI(csvContent);
		var link = document.createElement("a");
		link.setAttribute("href", encodedUri);
		var name = type + '_export.csv';
		link.setAttribute("download", name);
		document.body.appendChild(link);
		link.click();
	}
});
</script>

<?php	
	$commentName = ucfirst($sampleType) . "Comments";
	$commentData = function($sample, $commentName) {return $sample->$commentName;};
	
	if ($sampleType == "bacteria") {
		$tableHeaders = array("Date", "Sample number", "Ecoli raw count", "Ecoli (CFU/100 ml)", "Total Coliform Raw Count", "Total Coliform (CFU/100)", "Comments");
		
		$fieldsPrototype = function($bacteriaSample) {return array($bacteriaSample->Date, $bacteriaSample->Sample_Number, $bacteriaSample->EcoliRawCount, $bacteriaSample->Ecoli, $bacteriaSample->TotalColiformRawCount, $bacteriaSample->TotalColiform);};
		$controlNames = array("Date", "samplenumber", "EcoliRawCount", "Ecoli", "TotalColiformRawCount", "TotalColiform");
		$lengths = array('11', '11', '5', '5', '5', '5');
	}
	elseif ($sampleType == "nutrient") {
		$tableHeaders = array("Date", "Sample number", "Nitrate/Nitrite (mg/L)", "Total Phosphorus (mg/L)", "Dissolved Reactive Phosphorus (mg/L)", "Ammonia (mg/L)", "Comments");
		
		$fieldsPrototype = function($nutrientSample) {return array($nutrientSample->Date, $nutrientSample->Sample_Number, $nutrientSample->NitrateNitrite, $nutrientSample->Phosphorus, $nutrientSample->DRP, $nutrientSample->Ammonia);};
		$controlNames = array("Date", "samplenumber", "NitrateNitrite", "Phosphorus", "DRP", "Ammonia");
		$lengths = array('11', '11', '5', '5', '5', '5');
	}
	elseif ($sampleType == "pesticide") {
		$tableHeaders = array("Date", "Sample number", "Atrazine (µg/L)", "Alachlor (µg/L)", "Metolachlor (µg/L)", "Comments");
		
		$fieldsPrototype = function($pesticideSample) {return array($pesticideSample->Date, $pesticideSample->Sample_Number, $pesticideSample->Atrazine, $pesticideSample->Alachlor, $pesticideSample->Metolachlor);};
		$controlNames = array("Date", "samplenumber", "Atrazine", "Alachlor", "Metolachlor");
		$lengths = array('11', '11', '5', '5', '5');
	}
	elseif ($sampleType == "physical") {
		$tableHeaders = array("Date", "Sample number", "Conductivity (mS/cm)", "Dissolved Oxygen (mg/L)", "Bridge to Water Height (in)", "pH", "Temperature (°C)", "Total Dissolved Solids (g/L)", "Turbidity (NTU)", "Comments");
		
		$fieldsPrototype = function($physicalSample) {return array($physicalSample->Date, $physicalSample->Sample_Number, $physicalSample->Conductivity, $physicalSample->DO, $physicalSample->Bridge_to_Water_Height, $physicalSample->pH, $physicalSample->Water_Temp, $physicalSample->TDS, $physicalSample->Turbidity);};
		$controlNames = array("Date", "samplenumber", "Conductivity", "DO", "Bridge_to_Water_Height", "pH", "Water_Temp", "TDS", "Turbidity");
		$lengths = array('11', '11', '5', '5', '5', '4', '11', '4', '4');
	}

	echo "<h3>" . ucfirst($sampleType) . " measurements for $siteNumber $siteName - $siteLocation</h3>";
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
							<input type="text" name="<?php echo $controlNames[$i];?>-<?php echo $row;?>" maxlength=<?php echo $lengths[$i];?> size=<?php echo $lengths[$i];?> class="inputfields tableInput" style="display: none" id="<?php echo $controlNames[$i];?>-<?php echo $row;?>" value="<?php echo $fields[$i];?>"/>
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
					<div class="input text">
					<?php
					if ($admin) { ?>
						<label style="display: table-cell; cursor: pointer; white-space:normal !important;" class="btn btn-thin inputHide" for="<?php echo $commentName . '-' . $row;?>"><?php echo $commentData($sample, $commentName);?> </label>
						<textarea rows="4" cols="50" class="tableInput" name="<?php echo $commentName;?>-<?php echo $row;?>" style="display: none" id="<?php echo $commentName;?>-<?php echo $row;?>"><?php echo $commentData($sample, $commentName);?></textarea>
					<?php
					}
					else {
					?>
						<span style="display: table-cell; white-space: normal !important;"><?php echo $commentData($sample, $commentName);?> </span>
					<?php } ?>
					</div>
				</td>
				
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

<?=
	$this->Form->button('Export', [
	'label' => false,
	'type' => 'submit',
	'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-4 float-right',
	'id' => 'exportBtn'
	])
?>

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
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
	}
	elseif ($sampleType == "nutrient") {
		$tableHeaders = array("Date", "Sample number", "Nitrate/Nitrite (mg/L)", "Total Phosphorus (mg/L)", "Dissolved Reactive Phosphorus (mg/L)", "Ammonia (mg/L)", "Comments", "Actions");
	}
	elseif ($sampleType == "pesticide") {
		$tableHeaders = array("Date", "Sample number", "Atrazine (µg/L)", "Alachlor (µg/L)", "Metolachlor (µg/L)", "Comments", "Actions");
	}
	elseif ($sampleType == "wqm") {
		$tableHeaders = array("Date", "Sample number", "Conductivity (mS/cm)", "Dissolved Oxygen (mg/L)", "Bridge to Water Height (in)", "pH", "Temperature (°C)", "Total Dissolved Solids (g/L)", "Turbidity (NTU)", "Comments", "Actions");
	}
	
	for ($i=0; $i<count($tableHeaders); $i++) {
		$colHeader = $tableHeaders[$i];
		echo "<th>$colHeader</th>";
	}
	
	echo "</tr>";
	echo "</thead>";
	
	
	//-----------

	if ($sampleType == "bacteria") {
		include "bactTable.ctp";
	}
	elseif ($sampleType == "nutrient") {
		include "nutrientTable.ctp";
	}
	elseif ($sampleType == "pesticide") {
		include "pesticideTable.ctp";
	}
	elseif ($sampleType == "wqm") {
		include "waterQualityTable.ctp";
	}
	?>
        
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<?= $this->Html->css("visualization.css") ?>
<?= $this->Html->css("chartview.css") ?>

<?= $this->Html->script("lib/d3/d3.js") ?>
<?= $this->Html->script("charting.js") ?>
<?= $this->Html->script('datePickers.js') ?>

<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-stats" style="font-size: 20pt;"></span>  <?php echo $chartType;?> Charting
	<a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
		<span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
	</a>
</p>
<hr>

<div class="collapse" id="collapseInfo">
	<div class="info card card-body">
		<p style="text-align: left">The St. Joseph River Watershed Initiative and its partners have been collecting water quality data since 2002. While all of these data are available for viewing, you may wish to limit your date range to a few years at a time to optimize viewing of charts.</p>  
	</div>
</div>
<br/>
<div class="row container">
<?=
$this->Form->control('site', [
	'label' => false,
	'type' => 'text',
	'style' => 'display: none;',
	'id' => 'site'
])
?>
	<div class="mb-2 col-md-3">
		<h2>Site(s)</h2>
	</div>
	<div class="mb-3 col-md-9 scrollCheckbox scrollsites">
	<?php
	foreach ($siteLocations as $siteLocation) {
		$siteNumber = $this->Number->format($siteLocation->Site_Number);
		$siteName = h($siteLocation->Site_Name);
		$siteLocation = h($siteLocation->Site_Location);
		echo("<label for=sites-$siteNumber>");
		echo("<input type='checkbox' name='sites[]' value='$siteNumber' id='sites-$siteNumber'>");
		echo(" $siteNumber $siteName - $siteLocation</label>");
	}
	?>
	</div>
</div>
<div class="row container">
	<div class="mb-2 col-md-3">
		<h2>Measure</h2>
	</div>
	<div class="mb-2 col-lg-9 mSelect">

<?php
if ($chartType == "bacteria") {
	echo($this->Form->select('measurementSelect', [
		'ecoli' => 'E. Coli (CFU/100 mil)'
		], [
		'label' => 'Measurement',
		'id' => 'measurementSelect',
		'class' => 'form-control select'
		]
	));
}
elseif ($chartType == "nutrient") {
	echo($this->Form->select('measurementSelect', [
		'nitrateNitrite' => 'Nitrate/Nitrite (mg/L)',
		'phosphorus' => 'Total Phosphorus (mg/L)',
		'drp' => 'Dissolved Reactive Phosphorus (mg/L)',
		'ammonia' => 'Ammonia (mg/L)'
		], [
		'label' => 'Measurement',
		'id' => 'measurementSelect',
		'class' => 'form-control select'
		]
	));
}
elseif ($chartType == "pesticide") {
	echo($this->Form->select('measurementSelect', [
		'alachlor' => 'Alachlor (µg/L)',
		'atrazine' => 'Atrazine (µg/L)',
		'metolachlor' => 'Metolachlor (µg/L)',
		], [
		'label' => 'Measurement',
		'id' => 'measurementSelect',
		'class' => 'form-control select'
		]
	));
}
elseif ($chartType == "physical") {
	echo($this->Form->select('measurementSelect', [
		'conductivity' => 'Conductivity (mS/cm)',
		'do' => 'Dissolved Oxygen (mg/L)',
		'ph' => 'pH',
		'bridge_to_water_height' => 'Bridge to Water Height (in)',
		'water_temp' => 'Water Temperature (°C)',
		'tds' => 'Total Dissolved Solids (g/L)',
		'turbidity' => 'Turbidity (NTU)',
		], [
		'label' => 'Measurement',
		'id' => 'measurementSelect',
		'class' => 'form-control select'
		]
	));
}
?>

</div>
	</div>
        <div class="row container">
            <div class="col-md-3">
                <h2>Date Range</h2>
            </div>
            <div class="col-md-4 mSelect">
				<?=
					$this->Form->control('startDate', [
						'label' => false,
						'type' => 'text',
						'class' => 'form-control date-picker col-lg-12',
						'id' => 'startDate',
						'placeholder' => 'mm/dd/yyyy'
					])
				?>
            </div>
            <div class="mb-3 col-md-1">
                <h2>to</h2>
            </div>
            <div class="mb-3 col-md-4 mSelect">
				<?=
					$this->Form->control('endDate', [
						'label' => false,
						'type' => 'text',
						'class' => 'form-control date-picker col-lg-12',
						'id' => 'endDate',
						'placeholder' => 'mm/dd/yyyy'
					])
				?>
            </div>
        </div>

<div class="row buttongroup">
<?=
$this->Form->button('Bar Chart', [
	'label' => false,
	'type' => 'button',
	'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-3',
	'id' => 'chartBtn'
])
?>
<span class="col-md-1"></span>
<?=
$this->Form->button('Line Chart', [
	'label' => false,
	'type' => 'button',
	'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-3',
	'id' => 'lineBtn'
])
?>
<span class="col-md-1"></span>
<?=
$this->Form->button('View Table', [
	'label' => false,
	'type' => 'submit',
	'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-3',
	'id' => 'tableBtn'
])
?>
</div>
<?= $this->Form->end() ?>

<hr/>
<div class="row">
	<div class="col-md-12 mb-3 chartBox" id="dashboard">
		<p class="centeredText chartTitle" id="chartTitle">An error has occurred</p>
		<svg class="chart" id="chart"></svg>
	</div>
</div>
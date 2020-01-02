<?= $this->Html->script("charting.js") ?>
<?= $this->Html->script('dateautofill.js') ?>
<?= $this->Html->css('chartSelection.css') ?>
<?= $this->Html->script('mapping.js') ?>
<?= $this->Html->script('konami.js') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<?= $this->Html->script('chartjs-plugin-annotation.js') ?>

<div>
	<div id="mySidebar" class="sidebar">
		<h3 class="pt-3 centeredText">Narrow results</h3>
	
		<fieldset>
			<h5>Sites:</h5>
			<select class="js-example-placeholder-multiple form-control" id="sites" name="site[]" multiple="multiple" style="width: 100%">
				<?php
					//populate the site drop down box
					foreach ($siteLocations as $siteLocation) {
						$siteNumber = $this->Number->format($siteLocation->Site_Number);
						$siteName = h($siteLocation->Site_Name);
						$siteLocation = h($siteLocation->Site_Location);
						echo "<option value=$siteNumber title=\"$siteLocation\">$siteNumber $siteName</option>";
					}
				?>
			</select>
		
			<hr/>
		
			<h5>Categories:</h5>
			<?=
			$this->Form->select('categorySelect', [
				'bacteria' => 'Bacteria',
				'nutrient' => 'Nutrient',
				'pesticide' => 'Pesticide',
				'physical' => 'Physical Properties'
				], [
				'label' => 'Category',
				'id' => 'categorySelect',
				'class' => 'form-control select'
				]
			)
			?>
		
			<hr/>
		
			<div>
				<h5>From:</h5>
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
			
			<div>
				<h5>To:</h5>
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
			
			<hr/>
			
			<div>
				<h5>Where</h5>
				<?=
				$this->Form->select('measurementSelect', [
					'select' => 'Select a measure',
					'ecoli' => 'E. Coli (CFU/100 mil)'
					], [
					'label' => 'Measurement',
					'id' => 'measurementSelect',
					'class' => 'form-control select'
					]
				)
				?>
			</div>
			<div>
				<?=
				$this->Form->select('overUnderSelect', [
					'over' => 'Over',
					'under' => 'Under',
					'equal' => 'Equal To'
					], [
					'label' => 'Search',
					'id' => 'overUnderSelect',
					'class' => 'form-control select'
					]
					)
				?>
			</div>
			<div>
				<?=
				$this->Form->control('amountEnter', [
					'label' => false,
					'type' => 'text',
					'class' => 'form-control input col-lg-12',
					'id' => 'amountEnter',
					'placeholder' => 'Get Benchmark'
				])
				?>
			</div>
			
			<hr/>
			
			<div>
				<h5>Display fields:</h5>
				<ul id="checkboxList">
					<li>
						<input type="checkbox" value="ecoli">
							Ecoli
						</input>
					</li>
				</ul>
			</div>
			
			<button type="button" id="updateButton">Update</button>
			<button type="button" id="resetButton">Reset</button>
		</fieldset>
		<?= $this->Form->end() ?>
	</div>

	<div class="col-lg-12" id="main">
		<button class="btn btn-basic btn-lg mb-3 mt-3" id="searchButton">&#9776; Search</button> 

		<div class="card">
			<div class="card-header">
				<h4><a class="collapsible-panel card-link" data-toggle="collapse" href="#collapseOne">Map</a></h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse show">
				<div class="panel-body" id="mapContainer">
					<div class="mb-3" id="map" style="width:100%; height:500px; border: solid black thin;"></div>
				</div>
			</div>
		</div>
		
		<div class="card">
			<div class="card-header">
				<h4><a class="collapsible-panel card-link" data-toggle="collapse" href="#collapseTwo">Timeline</a></h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse show">
				<div class="panel-body">
					<div id="chartsLayoutSelect" style="display: none;">
						<button type="button" id="chartsInlineButton">In-line</button>
						<button type="button" id="chartsGridButton">Grid</button>
					</div>
					<div id="chartDiv" style="text-align: center;"></div>
				</div>
			</div>
		</div>
		
		<div class="card">
			<div class="card-header">
				<h4><a class="collapsible-panel card-link" data-toggle="collapse" href="#collapseThree">Table</a></h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse show">
				<div class="panel-body">
					<div id="tableDiv" style="text-align: center;"></div>
				</div>
			</div>
		</div>
		
		<?=
		$this->Form->button('Export', [
			'label' => false,
			'type' => 'submit',
			'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-4 float-right',
			'id' => 'exportBtn'
		])
		?>
	</div>
	</div>
</div>

<script async defer src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBwcJIWDoWbEgt7mX_j5CXGevgWvQPh6bc&callback=initMap' type="text/javascript"></script>
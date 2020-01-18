<?= $this->Html->css('chartSelection.css') ?>
<?= $this->Html->script('konami.js') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<?= $this->Html->script('chartjs-plugin-annotation.js') ?>
<script defer src="../js/charting.js"></script>
<script defer src="../js/lib/a"></script>

<link rel="stylesheet" href="https://js.arcgis.com/4.14/esri/themes/light/main.css" />

<script>
var admin = <?php echo $admin?>;
</script>

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
					'Ecoli' => 'E. Coli (CFU/100 mil)',
					'TotalColiform' => 'Coliform (CFU/100 mil)'
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
					'>=' => 'Over',
					'<=' => 'Under',
					'==' => 'Equal To'
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
				<ul id="checkboxList" style="list-style-type:none;">
					<li>
						<input type="checkbox" value="all" id="allCheckbox" checked><label for="allCheckbox">All</label>
					</li>
					<li>
						<input class="measurementCheckbox" type="checkbox" id="EcoliCheckbox" value="Ecoli" checked><label for="Ecoli">Ecoli</label>
					</li>
					<li>
						<input class="measurementCheckbox" type="checkbox" id="TotalColiformCheckbox" value="TotalColiform" checked><label for="TotalColiformCheckbox">TotalColiform</label>
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
					<div id="map"></div>
					<div class="row">
						<div class="col-sm-2">
							<input type="checkbox" id="watershedsLayer" checked /> Watersheds
							<input type="checkbox" id="drainsLayer" /> Drains
						</div>
						<div class="col-sm">
							Use basemap
							<select id="selectBasemap">
								<option value="gray">Gray</option>
								<option value="satellite">Satellite</option>
								<option value="osm">Streets</option>
								<option value="hybrid">Hybrid</option>
								<option value="terrain">Terrain</option>
							</select>
						</div>
					</div>
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
						<input type="checkbox" id="showBenchmarks" value="showBenchmarks" checked>Show benchmark lines
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
					Show 
					<select id="numRowsDropdown">
						<option value=10>10</option>
						<option value=25 selected="selected">25</option>
						<option value=100>100</option>
						<option value=500>500</option>
						<option value=-1>All</option>
					</select>
					results
					<div id="tableDiv" style="text-align: center;"></div>

					<div>
						<button type="button" id="firstPageButton">First</button>
						<button type="button" id="previousPageButton">Previous</button>
						Page <input type="text" id="pageNumBox" name="pageNumBox" value="1" size=3></input> of <span id="totalPages">x</span>
						<button type="button" id="nextPageButton">Next</button>
						<button type="button" id="lastPageButton">Last</button>
					</div>
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

<script async defer src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBwcJIWDoWbEgt7mX_j5CXGevgWvQPh6bc&callback=initMap' type="text/javascript"></script>
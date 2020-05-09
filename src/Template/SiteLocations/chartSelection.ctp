<?= $this->Html->css("chartSelection.css") ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<?= $this->Html->script("chartjs-plugin-annotation.js") ?>
<script defer src="../js/charting.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel="stylesheet" />
<link rel="stylesheet" href="https://js.arcgis.com/4.14/esri/themes/light/main.css">
<script defer src="https://js.arcgis.com/4.14/"></script>

<?php if (isset($runTutorial)) { ?>
<script defer src="../js/tutorial.js"></script>
<script src="https://unpkg.com/driver.js/dist/driver.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/driver.js/dist/driver.min.css">
<?php }
echo "<script>";

if ($admin) {
	echo "var admin = true;";
}
echo "var measurementSettings=" . json_encode($measurementSettings) . ";";
echo "var groups=" . json_encode($groups) . ";";
echo "var mapData=" . json_encode($mapData) . ";";

if (isset($_GET["site"])) {
	echo "var preselectSite=" . $_GET["site"] . ";";
}
else {
	echo "var preselectSite=null;";
}

if (isset($_GET["group"])) {
	echo "var preselectGroup=" . $_GET["group"] . ";";
}
else {
	echo "var preselectGroup=null;";
}
echo "</script>";
?>

<div class="sidebarContainer">
	<div id="sidebarInner">
		<div id="searchBox">
		<fieldset>
			<h6>Sites:</h6>
			<select class="form-control" id="sites" name="site[]" multiple="multiple" style="width: 100%"></select>
			<input type="checkbox" id="aggregateGroup"><label for="aggregateGroup">Aggregate</label>
		
			<hr/>
		
			<h6>Measurements:</h6>
			<?=
			$this->Form->select("categorySelect", [
				"bacteria" => "Bacteria",
				"nutrient" => "Nutrient",
				"pesticide" => "Pesticide",
				"physical" => "Physical Properties"
				], [
				"label" => "Category",
				"id" => "categorySelect",
				"class" => "form-control select"
				]
			)
			?>
			<div>
				<ul id="checkboxList" style="list-style-type:none; padding-left: 0;">
					<li>
						<input type="checkbox" value="all" id="allCheckbox" checked><label for="allCheckbox">All</label>
					</li>
					<li>
						<input class="measurementCheckbox" type="checkbox" id="EcoliCheckbox" value="Ecoli" checked><label for="Ecoli">E. Coli</label>
					</li>
					<li>
						<input class="measurementCheckbox" type="checkbox" id="TotalColiformCheckbox" value="TotalColiform" checked><label for="TotalColiformCheckbox">Coliform</label>
					</li>
				</ul>
			</div>
			<hr/>
			
			<div>
				<h6>From:</h6>
				<?=
				$this->Form->control("startDate", [
					"label" => false,
					"type" => "text",
					"class" => "form-control date-picker col-lg-12",
					"id" => "startDate",
					"placeholder" => "mm/dd/yyyy"
				])
				?>
			</div>
				
			<div>
				<h6>To:</h6>
				<?=
				$this->Form->control("endDate", [
					"label" => false,
					"type" => "text",
					"class" => "form-control date-picker col-lg-12",
					"id" => "endDate",
					"placeholder" => "mm/dd/yyyy"
				])
				?>
			</div>
				
			<hr/>
				
			<div>
				<h6>Filter by (optional):</h6>
				<?=
				$this->Form->select("measurementSelect", [
					"select" => "Select a measure",
					"Ecoli" => "E. Coli",
					"TotalColiform" => "Coliform"
					], [
					"label" => "Measurement",
					"id" => "measurementSelect",
					"class" => "form-control select"
					]
				)
				?>
			</div>
			<div>
			<?=
			$this->Form->select("overUnderSelect", [
				">=" => "Over",
				"<=" => "Under",
				"=" => "Equal To"
				], [
				"label" => "Search",
				"id" => "overUnderSelect",
				"class" => "form-control select"
				]
			)
			?>
			</div>
			<div>
			<?=
			$this->Form->control("amountEnter", [
				"label" => false,
				"type" => "text",
				"class" => "form-control input col-lg-12",
				"id" => "amountEnter",
				"placeholder" => "Get Benchmark"
			])
			?>
			</div>
			
			<div id="buttonGroup">
				<button type="button" id="updateButton" class="btn btn-ctrl">Update</button>
				<button type="button" id="resetButton" class="btn btn-ctrl">Reset</button>
			</div>
		</fieldset>
		<?= $this->Form->end() ?>
		</div>
	</div>
	
	<div class="sidebarToggle" id="sidebarToggle" style="margin-top: 15px; z-index: 1; cursor: pointer;">
		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>
	</div>
</div>

<div class="col-lg-12" id="main">
	<div class="card">
		<div class="card-header" id="mapCard">
			<h4><a class="collapsible-panel card-link" data-toggle="collapse" href="#collapseMap">Map</a></h4>
		</div>
		<div id="collapseMap" class="panel-collapse collapse show">
			<div class="panel-body" id="mapContainer">
				<div id="legend">
					<div id="watershedsLegend">
						<strong>Watersheds:</strong>&nbsp;
						<div class="color-box" style="background-color: #469F8E;"></div> St. Joseph &nbsp;
						<div class="color-box" style="background-color: #DA64DD;"></div> St. Marys &nbsp;
						<div class="color-box" style="background-color: #84FCFC;"></div> Upper Maumee &nbsp;
						<div class="color-box" style="background-color: #F4AC3D;"></div> Auglaize
					</div>
					<div id="floodplainsLegend">
						<strong>Floodplains:</strong>&nbsp;
						<div class="color-box" style="background-color: #E7E44B;"></div> Floodway &nbsp;
						<div class="color-box" style="background-color: #8CDDFB;"></div> 1% Annual Chance Flood Hazard&nbsp;
						<div class="color-box" style="background-color: #F4AC3D;"></div> 0.2% Annual Chance, Protected by Levee &nbsp;
						<div class="color-box" style="background-color: #D42C1F;"></div> 0.2% Annual Chance Flood Hazard
					</div>
				</div>
				<div id="map"></div>
				<div id="mapSettings">
					<div id="layerBar">
						<input type="checkbox" id="watershedsLayer" checked /> Watersheds
						<input type="checkbox" id="drainsLayer" /> Drains
						<input type="checkbox" id="riverLayer" /> Rivers/Streams
						<input type="checkbox" id="impairedLayer" /> IDEM - Impaired Waters
						<input type="checkbox" id="bodiesLayer" /> Water Bodies
						<input type="checkbox" id="floodLayer" /> Floodplains
						<input type="checkbox" id="damLayer" /> Dams
						<input type="checkbox" id="wellLayer" /> Wells
						<input type="checkbox" id="wetlandLayer" /> Wetlands and Deepwater Habitats
					</div>
					<div>
						Basemap
						<select id="selectBasemap">
							<option value="satellite">Satellite</option>
							<option value="gray">Gray</option>
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
		<div class="card-header" id="timelineCard">
			<h4><a class="collapsible-panel card-link" data-toggle="collapse" href="#collapseTimeline">Timeline</a></h4>
		</div>
		<div id="collapseTimeline" class="panel-collapse collapse show">
			<div class="panel-body">
				<div id="chartsLayoutSelect" style="display: none">
					<button type="button" id="chartsInlineButton" class="btn btn-sm btn-ctrl">In-line</button>
					<button type="button" id="chartsGridButton" class="btn btn-sm btn-ctrl">Grid</button>
					<select id="chartType">
						<option value="scatter">Scatter</option>
						<option value="line">Line</option>
					</select>
					<input type="checkbox" id="showBenchmarks" value="showBenchmarks" checked>Show benchmark lines
				</div>
				<div id="chartDiv" style="text-align: center;"></div>
				<span id="chartsNoData">No data to display</span>
			</div>
		</div>
	</div>
		
	<div class="card">
		<div class="card-header" id="tableCard">
			<h4><a class="collapsible-panel card-link" data-toggle="collapse" href="#collapseTable">Table</a></h4>
		</div>
		<div id="collapseTable" class="panel-collapse collapse show">
			<div class="panel-body">
				<div id="tableSettingsTop" style="display: none">
					<span class="totalResults">x</span> Results &nbsp;&nbsp;&nbsp; Show
					<select id="numRowsDropdownTop">
						<option value=10>10</option>
						<option value=25 selected="selected">25</option>
						<option value=100>100</option>
						<option value=500>500</option>
						<option value=-1>All</option>
					</select>
					
					&nbsp;&nbsp;&nbsp;
					
					<button type="button" class="firstPageButton btn btn-sm btn-ctrl"><<</button>
					<button type="button" class="previousPageButton btn btn-sm btn-ctrl"><</button>
					<select id="pageSelectorTop"></select>
					<button type="button" class="nextPageButton btn btn-sm btn-ctrl">></button>
					<button type="button" class="lastPageButton btn btn-sm btn-ctrl">>></button>
				</div>
				
				<div id="tableDiv"></div>
				<span id="tableNoData">No data to display</span>

				<div id="tableSettingsBottom" style="display: none">
					<span class="totalResults">x</span> Results &nbsp;&nbsp;&nbsp; Show
					<select id="numRowsDropdownBottom">
						<option value=10>10</option>
						<option value=25 selected="selected">25</option>
						<option value=100>100</option>
						<option value=500>500</option>
						<option value=-1>All</option>
					</select>
					
					&nbsp;&nbsp;&nbsp;
					
					<button type="button" class="firstPageButton btn btn-sm btn-ctrl"><<</button>
					<button type="button" class="previousPageButton btn btn-sm btn-ctrl"><</button>
					<select id="pageSelectorBottom"></select>
					<button type="button" class="nextPageButton btn btn-sm btn-ctrl">></button>
					<button type="button" class="lastPageButton btn btn-sm btn-ctrl">>></button>
				</div>
			</div>
		</div>
	</div>
		
	<?=
	$this->Form->button("Export", [
		"label" => false,
		"type" => "submit",
		"class" => "btn btn-basic btn-lg mb-3 mt-3 col-md-4 float-right",
		"id" => "exportBtn",
		"disabled" => true
	])
	?>
</div>

<!-- measurement selection modal for single-graph comparisons -->
<div class="modal" id="compareToModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Compare to:</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div id="compareTargetOptions"></div>

			<div class="modal-footer">
				<button type="button" class="btn" id="clearCompare">Clear</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
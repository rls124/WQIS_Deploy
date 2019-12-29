<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<?= $this->Html->script('dateautofill.js') ?>
<?= $this->Html->css('chartSelection.css') ?>
<?= $this->Html->css("visualization.css") ?>
<?= $this->Html->css("chartview.css") ?>
<?= $this->Html->script("lib/d3/d3.js") ?>
<?= $this->Html->script("charting.js") ?>
<?= $this->Html->script('chartSelectionValidation.js') ?>
<?= $this->Html->script('tableedit.js') ?>
<?= $this->Html->script('konami.js') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>

<div>
	<div id="mySidebar" class="sidebar">
		<h3 class="pt-3 centeredText">Narrow results</h3>
	
		<fieldset>
			<h5>Sites:</h5>
			<select class="js-example-placeholder-multiple form-control" id="site" name="site[]" multiple="multiple" style="width: 100%">
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
			
			<input type="button" id="updateButton">Update</input>
		</fieldset>
	</div>

	<div class="col-lg-12" id="main">
		<button class="toggleButton" onclick="toggleSidebar()">&#9776; Search</button> 
	
		<button type="button" class="collapsible"><h3 class="pt-3">Map</h3></button>
		<div id="mapContainer">
		
		
		<div class='mb-3' id='map' style='width:100%; height:500px; border: solid black thin;'></div>
		<div class='mb-3' id='easteregg' style='width:100%; border: solid black thin; display: none;'>
			<div id="menu">
				<p id="start"><a href="javascript:play();">Press Space to Play</a></p>
				<p><canvas id="upcoming"></canvas></p>
				<p>score <span id="score">00000</span></p>
				<p>rows <span id="rows">0</span></p>
				<p>Level <span id="level">1</span></p>
			</div>
			<canvas id="canvas"></canvas>
		</div>
		</div>

		<?= $this->Form->end() ?>

		<button type="button" class="collapsible"><h3 class="pt-3">Line graph</h3></button>
		<div id="chartDiv" style="text-align: center;"></div>
		
		
		<button type="button" class="collapsible"><h3 class="pt-3">Table</h3></button>
		<div id="tableDiv" style="text-align: center;"></div>
		
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

<script>
//set the width of the sidebar to 250px and the left margin of the page content to 250px
function toggleSidebar() {
	if (document.getElementById("mySidebar").style.width == "450px") {
		document.getElementById("mySidebar").style.width = "0";
		document.getElementById("main").style.marginLeft = "0";
	}
	else {
		//open the sidebar
		document.getElementById("mySidebar").style.width = "450px";
		document.getElementById("main").style.marginLeft = "450px";
	}
}

$(document).ready(function () {
	$("#exportBtn").click(function () {
		var sampleType = $('#categorySelect').val();		
		var startDate = $('#startDate').val();
		var endDate = $('#endDate').val();
		var sites = [$('#site').val()];
		
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
				'measures': measures
				//'amountEnter': amountEnter,
				//'overUnderSelect': overUnderSelect
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

		//if ID field exists, remove it
		if (fields[0] === "ID") {
			fields = fields.splice(1, fields.length);
		}
		
		//make null values not have text
		var replacer = function (key, value) {
			return value === null ? '' : value;
		};

		var csv = fileData.map(function (row) {
			return fields.map(function (fieldName) {
				return JSON.stringify(row[fieldName], replacer);
			}).join(',');
		});
		fields[fields.indexOf('site_location_id')] = 'Site Number';
		
		//add header column
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

$('#site').select2({
	closeOnSelect: false,
	placeholder: "Select sites",
	width: 'resolve'
});

//collapse or expand all collapsible divs when the appropriate button is clicked
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    }
	else {
      content.style.display = "block";
    }
  });
}
</script>

<script async defer src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBwcJIWDoWbEgt7mX_j5CXGevgWvQPh6bc&callback=initMap' type="text/javascript"></script>
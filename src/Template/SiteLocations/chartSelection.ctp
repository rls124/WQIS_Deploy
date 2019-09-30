<link rel="stylesheet" href="../css/ol.css">
<link rel="stylesheet" href="../css/fontawesome-all.min.css">
<link rel="stylesheet" href="../css/ol3-layerswitcher.css">
<link rel="stylesheet" href="../css/map.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<?= $this->Html->script('dateautofill.js') ?>
<?= $this->Html->script('chartSelectionValidation.js') ?>
<?= $this->Html->css('chartSelection.css') ?>

<div class="container roundGreyBox">
    <?= $this->Form->create('chartselection', ['url' => ['controller' => 'BacteriaSamples', 'action' => 'tableview'], 'id' => 'chartSelect']) ?>
    <fieldset>
        <h3 class="pt-3 centeredText">Collection Site</h3>
        <hr>
        <select class="form-control select" id="site" name="site">
            <option value="select" selected="selected">Select Collection Site</option>

            <?php
                //populate the site drop down box
                foreach ($siteLocations as $siteLocation) {
                    $siteNumber = $this->Number->format($siteLocation->Site_Number);
                    $siteName = h($siteLocation->Site_Name);
                    $siteLocation = h($siteLocation->Site_Location);
                    echo "<option value=$siteNumber>$siteNumber $siteName - $siteLocation</option>";
                }
            ?>

        </select>
        <br>
    </fieldset>

    <fieldset>
        <div class="card-deck">
            <div class="card">
                <h3 class="centeredText card-title" id="measurementsHeading">Measurements <?= $this->Html->link('What do these mean?', ['controller' => 'pages', 'action' => 'waterqualitymeaning'], ['id' => 'wqisMeaning']) ?></h3>
                <hr style="width: 100%; margin: 0px 0px 25px 0px">
                <div class="card-deck">
                    <!-- Category Select -->
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">Categories</h5>
                        <?=
                            $this->Form->select('categorySelect', [
                                'bacteria' => 'Bacteria',
                                'nutrient' => 'Nutrient',
                                'pesticide' => 'Pesticide',
                                'wqm' => 'Physical Properties'
                                ], [
                                'label' => 'Category',
                                'id' => 'categorySelect',
                                'class' => 'form-control select'
                                ]
                            )
                        ?>
                    </div>
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">Measure</h5>
                        <!-- Measure Select -->
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
                </div>
            </div>
            <div class="card">
                <h3 class="centeredText card-title" id="dateRangeHeading">Date Range</h3>
                <hr style="width:100%; margin: 0px 0px 25px 0px">
                <div class="card-deck">
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">From</h5>
                        <?=
                            $this->Form->control('startdate', [
                                'label' => false,
                                'type' => 'text',
                                'class' => 'form-control date-picker col-lg-12',
                                'id' => 'startdate',
                                'placeholder' => 'mm/dd/yyyy'
                            ])
                        ?>
                    </div>
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">To</h5>
                        <?=
                            $this->Form->control('enddate', [
                                'label' => false,
                                'type' => 'text',
                                'class' => 'form-control date-picker col-lg-12',
                                'id' => 'enddate',
                                'placeholder' => 'mm/dd/yyyy'
                            ])
                        ?>
                    </div>
                </div>
            </div>
        </div>

		<div class="container">
			<div class="row display-flex">
				<div id="map" class="col-lg-10">
					<div id="popup" class="ol-popup">
						<a href="#" id="popup-closer" class="ol-popup-closer"></a>
						<div id="popup-content"></div>
					</div>
				</div>
				<div class="col-lg-2 panel panel-primary">
					<div class="panel-body" style="overflow-y: scroll; height: 430px;">
					<ul class="list-group">
					<?php
					//populate the site list box
					foreach ($siteLocations as $siteLocation) {
						$siteNumber = $this->Number->format($siteLocation->Site_Number);
						$siteName = h($siteLocation->Site_Name);
						$siteLocation = h($siteLocation->Site_Location);
						echo "<li class=\"list-group-item\" id=\"$siteLocation\" onclick=\"clickedLocationButton(this.id)\">$siteName - $siteLocation</li>";
					}
					?>
					</ul>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			function clickedLocationButton(id) {
				var el = document.getElementById(id);
				if (el.style.backgroundColor == "grey") {
					el.style.backgroundColor = "white";
				}
				else {
					el.style.backgroundColor = "grey";
				}
			}
		</script>
		
        <script src="../js/map_expressions.js"></script>
        <script src="../js/polyfills.js"></script>
        <script src="../js/functions.js"></script>
        <script src="../js/ol.js"></script>
        <script src="http://cdn.polyfill.io/v2/polyfill.min.js?features=Element.prototype.classList,URL"></script>
        <script src="../js/ol3-layerswitcher.js"></script>
		
		<script>
			json_sampleData_1 = initMap();
		</script>
		
        <script src="../js/map.js"></script>
        <script src="../js/Autolinker.min.js"></script>
		
    <div class="container text-center">
        <?=
            $this->Form->button('View Chart', [
                'templates' => [
                    'inputContainer' => '{{content}}',
                    'label' => false
                ],
                'class' => 'btn mb-3 btn-basic btn-lg chartselect-btn',
                'id' => 'viewChartBtn',
                'name' => 'viewChartBtn'
            ]);
        ?>

        <?=
            $this->Form->button('View Table', [
                'templates' => [
                    'inputContainer' => '{{content}}',
                    'label' => false
                ],
                'class' => 'btn mb-3 btn-basic btn-lg chartselect-btn',
                'id' => 'viewTableBtn',
                'name' => 'viewTableBtn'
            ]);
        ?>
    </div>

    <?= $this->Form->end() ?>
</div>

<script>
    $('#viewChartBtn').click(function () {
        changeURL('chartview');
    });
    $('#viewTableBtn').click(function () {
        changeURL('tableview');
    });
    
    $('body').on('submit',function() {
        if ($('#site :selected').val() === 'select') {
            alert("Please select a site");
            return false;
        }
    });

    $("#categorySelect").change(function () {
        var entryType = $(this).val();
        var tableLocation;
        switch (entryType) {
            case 'bacteria':
                tableLocation = "<?= $this->Html->Url->build(['controller' => 'BacteriaSamples', 'action' => 'tableview']); ?>";
                chartLocation = "<?= $this->Html->Url->build(['controller' => 'BacteriaSamples', 'action' => 'chartview']); ?>";
                break;
            case 'nutrient':
                tableLocation = "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'tableview']); ?>";
                chartLocation = "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'chartview']); ?>";
                break;
            case 'pesticide':
                tableLocation = "<?= $this->Html->Url->build(['controller' => 'PesticideSamples', 'action' => 'tableview']); ?>";
                chartLocation = "<?= $this->Html->Url->build(['controller' => 'PesticideSamples', 'action' => 'chartview']); ?>";
                break;
            case 'wqm':
                tableLocation = "<?= $this->Html->Url->build(['controller' => 'WaterQualitySamples', 'action' => 'tableview']); ?>";
                chartLocation = "<?= $this->Html->Url->build(['controller' => 'WaterQualitySamples', 'action' => 'chartview']); ?>";
                break;
            default:
                tableLocation = "javascript:void(0);";
                chartLocation = "javascript:void(0);";
                break;
        }
        $("#chartSelect").attr("action", tableLocation);
    });
    function changeURL(actionType) {
        var category = $("#categorySelect").val();

        var location;
        if (actionType === 'chartview') {
            switch (category) {
                case 'bacteria':
                    location = "<?= $this->Html->Url->build(['controller' => 'BacteriaSamples', 'action' => 'chartview']); ?>";
                    break;
                case 'nutrient':
                    location = "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'chartview']); ?>";
                    break;
                case 'pesticide':
                    location = "<?= $this->Html->Url->build(['controller' => 'PesticideSamples', 'action' => 'chartview']); ?>";
                    break;
                case 'wqm':
                    location = "<?= $this->Html->Url->build(['controller' => 'WaterQualitySamples', 'action' => 'chartview']); ?>";
                    break;
                default:
                    location = "javascript:void(0);";
                    break;
            }
        }
		else if (actionType === 'tableview') {
            switch (category) {
                case 'bacteria':
                    location = "<?= $this->Html->Url->build(['controller' => 'BacteriaSamples', 'action' => 'tableview']); ?>";
                    break;
                case 'nutrient':
                    location = "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'tableview']); ?>";
                    break;
                case 'pesticide':
                    location = "<?= $this->Html->Url->build(['controller' => 'PesticideSamples', 'action' => 'tableview']); ?>";
                    break;
                case 'wqm':
                    location = "<?= $this->Html->Url->build(['controller' => 'WaterQualitySamples', 'action' => 'tableview']); ?>";
                    break;
                default:
                    location = "javascript:void(0);";
                    break;
            }
        }

        $("#chartSelect").attr("action", location);
    }
</script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
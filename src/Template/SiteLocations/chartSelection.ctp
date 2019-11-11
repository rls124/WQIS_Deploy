<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<?= $this->Html->script('dateautofill.js') ?>

<?= $this->Html->script('chartSelectionValidation.js') ?>

<?= $this->Html->css('chartSelection.css') ?>


    <?= $this->Form->create('chartselection', ['url' => ['controller' => 'GenericSamples', 'action' => 'tableview'], 'id' => 'chartSelect']) ?>
    <fieldset>
        <h3 class="pt-3 centeredText">Collection Site</h3>
        <hr>
        <select class="form-control select" id="site" name="site">
            <option value="select" selected="selected">Select Collection Site</option>

            <?php
                //This is for populating the site drop down box.
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
        <br>
        <!--<img class="mb-3" src="../img/SampleMap.jpg" alt="This is where the map would go" style="height:400px; width:100%; border: solid thin black;">-->
        <!--<iframe id='map' src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d385168.20958135213!2d-85.09480212578119!3d41.0443596614833!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sus!4v1523901620332" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>    </fieldset>-->
        <div class='mb-3' id='map' style='width:100%; height:500px; border: solid black thin'></div>
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
	
	
	
<script async defer src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBwcJIWDoWbEgt7mX_j5CXGevgWvQPh6bc&callback=initMap' type="text/javascript"></script>
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
	
	$(document).ready(function() {
		var tableLocation = "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'tableview']); ?>";
		var chartLocation = "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'chartview']); ?>";
		$("#chartSelect").attr("action", tableLocation);
	});

    function changeURL(actionType) {
		var location;
		if (actionType == 'tableview') {
			location = "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'tableview']); ?>";
		}
		else {
			location = "<?= $this->Html->Url->build(['controller' => 'GenericSamples', 'action' => 'chartview']); ?>";
		}

        $("#chartSelect").attr("action", location);
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<?= $this->Html->css("export.css") ?>

<?= $this->Html->script("export.js") ?>
<?= $this->Html->script('datePickers.js') ?>

    <p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'>
        <span class="glyphicon glyphicon-download-alt" style="font-size: 20pt;"></span>  Export
    </p>
    <hr>
    <fieldset>
        <h5 class="pt-3 centeredText">Collection Site</h5>
        <hr>
        <div class="scrollCheckbox scrollsites">
            <label for="sites-all">
                <input checked type="checkbox" name="sites[]" value="all" id="sites-all">
                All Site Locations
            </label>
	    <label for='sites-stJoe'>
		<input type='checkbox' name='rivers[]' value='all' id='sites-stJoe'>
		Saint Joe River
	    </label>
	    <label for='sites-stMary'>
		<input type='checkbox' name='rivers[]' value='all' id='sites-stMary'>
		St. Marys River
	    </label>
	    <label for='sites-upperMaumee'>
		<input type='checkbox' name='rivers[]' value='all' id='sites-upperMaumee'>
		Upper Maumee
	    </label>
	    <label for='sites-auglaize'>
		<input type='checkbox' name='rivers[]' value='all' id='sites-auglaize'>
		Auglaize
	    </label>
	    <?php
		foreach ($siteLocations as $siteLocation) {
		    $siteNumber = $this->Number->format($siteLocation->Site_Number);
		    $siteName = h($siteLocation->Site_Name);
		    $siteLocation = h($siteLocation->Site_Location);
		    echo("<label for=sites-$siteNumber>");
		    echo("<input type='checkbox' name='sites[]' value='$siteNumber' id='sites-$siteNumber'>");
		    echo("$siteNumber $siteName - $siteLocation</label>");
		}
	    ?>
        </div>
        <br>
    </fieldset>
    <hr>
    <fieldset>
        <div class="card-deck">
            <div class="card">
                <!--<hr style="width: 100%; margin: 0px 0px 25px 0px">-->
                <div class="card-deck">
                    <!-- Category Select -->
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">Categories</h5>
			<?=
			    $this->Form->select('typeInput', [
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
                    </div>
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">Measure</h5>
                        <!-- Measure Select -->
                        <div class="scrollCheckbox scrollmeasures" id="measurementSelect">
                            <label for='measure-all'>
                                <input checked type='checkbox' name='measure[]' value='all' id='measure-all'>
                                All Measures
                            </label>
                            <label for='measure-ecoli'>
                                <input type='checkbox' name='measure[]' value='ecoli' id='measure-ecoli'>
                                E. Coli (CFU/100 mil)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <!--<hr style="width:100%; margin: 0px 0px 25px 0px">-->
                <div class="card-deck">
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">From</h5>
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
                    <div class="card mb-3">
                        <h5 class="centeredText card-title">To</h5>
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
                <div class="">
		    <?=
			$this->Form->button('Export', [
			    'label' => false,
			    'type' => 'submit',
			    'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-4 float-right',
			    'disabled',
			    'id' => 'exportBtn'
			])
		    ?>
                </div>
            </div>
        </div>
    </fieldset>
<p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-stats" style="font-size: 20pt;"></span>  Physical Properties Charting
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
			?>
        </div>
    </div>
        <div class="row container">
            <div class="col-md-3">
                <h2>Date Range</h2>
            </div>
            <div class="col-md-4 mSelect">
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
            <div class="mb-3 col-md-1">
                <h2>to</h2>
                <!--spacer-->
            </div>
            <div class="mb-3 col-md-4 mSelect">
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
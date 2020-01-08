<?php if ($mode == "invalid") { ?>
	<h3>You need to specify a form type</h3>
	<a href="javascript:history.back()">Go Back</a>
<?php }
else if ($admin == false) { ?>
	<h3>You must be an administrator to access this page</h3>
	<a href="javascript:history.back()">Go Back</a>
<?php }
else if ($mode == "entry") {?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<?= $this->Html->css('entryForm.css') ?>
	<?= $this->Html->script('entryForm.js') ?>
	
	<?= $this->Form->create($sample) ?>
	<fieldset>
		<p class="centeredText" style="font-size:2.5rem;">
			<span class="glyphicon glyphicon-list-alt" style="font-size:20pt;"></span>  <?php echo $formType; ?> entry form
		</p>
		<hr/>
		<div class="form-group row">
			<?=
				$this->Form->control('Date', [
					'label' => [
						'class' => 'col-lg-1 label-reg text-right centerLabel mt-4'
					],
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'type' => 'text',
					'class' => "form-control date-picker entryControl col-lg-2 textinput mt-3",
					'placeholder' => 'mm/dd/yyyy'
				])
			?>
        </div>
        <div class="container-fluid">
            <div class="mb-3 infoPanel">
                <table class="table table-responsive">
                    <thead>
                        <tr>
						<?php
						if ($formType == "bacteria") {
							$colHeaders = ["Site", "Sample Number", "Ecoli Raw Count", "Ecoli<br>(CFU/100 ml)", "Total Coliform<br>Raw Count", "Total Coliform<br>(CFU/100 ml)", "Comments", "Actions"];
							$controlNames = ["ecolirawcount-0", "ecoli-0", "totalcoliformrawcount-0", "totalcoliform-0", "bacteriacomments-0"];
						}
						elseif ($formType == "nutrient") {
							$colHeaders = ["Site", "Sample Number", "Nitrate/Nitrite<br>(mg/L)", "Total Phosphorus<br>(mg/L)", "Dissolved Reactive Phosphorus<br>(mg/L)", "Ammonia<br>(mg/L)", "Comments", "Actions"];
							$controlNames = ["phosphorus-0", "nitratenitrite-0", "drp-0", "ammonia-0", "nutrientcomments-0"];
						}
						elseif ($formType == "pesticide") {
							$colHeaders = ["Site", "Sample Number", "Atrazine<br>(µg/L)", "Alachlor<br>(µg/L)", "Metochlor<br>(µg/L)", "Comments", "Actions"];
							$controlNames = ["atrazine-0", "alachlor-0", "metolachlor-0", "pesticidecomments-0"];
						}
						elseif ($formType == "physical") {
							$colHeaders = ["Site", "Sample Number", "Time", "Bridge to Water Height<br>(in)", "pH", "Water Temp<br>(°C)", "Conductivity<br>(mS/cm)", "TDS<br>(g/L)", "DO<br>(mg/L)", "Turbidity<br>(NTU)", "Comments", "Actions"];
							$controlNames = ["time-0", "bridge_to_water_height-0", "ph-0", "water_temp-0", "conductivity-0", "tds-0", "do-0", "turbidity-0", "physicalcomments-0"];
						}

						for ($i=0; $i<sizeof($colHeaders); $i++) {
							echo "<th>" . $colHeaders[$i] . "</th>";
						}
						?>	
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="row-0">
                            <td>
                                <select class="form-control entryControl siteselect" id="site_location_id-0" name="site_location_id-0">
                                    <option value="" selected="selected">Site</option>
									<?php
										foreach ($siteLocations as $siteLocation) {
											$siteNumber = $this->Number->format($siteLocation->Site_Number);
											$siteName = h($siteLocation->Site_Name);
											$siteLocation = h($siteLocation->Site_Location);
											echo "<option value=$siteNumber>$siteNumber $siteName - $siteLocation</option>";
										}
									?>
                                </select>
                            </td>
							<?=
								$this->Form->control('sample_number-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'id' => 'sample_number-0',
									'class' => 'form-control entryControl samplenumber',
									'readonly' => true
								])
							?>
	
							<?php
							for ($i=0; $i<sizeof($controlNames); $i++) {
								echo $this->Form->control($controlNames[$i], [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'text',
									'class' => 'form-control entryControl'
								]);
							}
							?>
	
							<td>
								<?=
								$this->Html->tag('span', "", [
									'class' => "delete glyphicon glyphicon-trash",
									'id' => 'Delete-0',
									'name' => 'Delete-0',
									'hidden'
								])
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
	
			<?=
				$this->Form->hidden('totalrows', [
					'value' => '0',
					'id' => 'totalrows'
				])
			?>

			<?=
				$this->Form->button('Submit ' . $formType . ' Measurements', [
					'class' => 'btn btn-basic mb-3',
					'style' => 'float: right;'
				])
			?>

			<?=
				$this->Form->button('Add Monitored Sites', [
					'class' => 'btn btn-basic mb-3 mr-2',
					'type' => 'button',
					'id' => 'addMonitoredSites',
					'style' => 'float: right;'
				])
			?>
			<?=
				$this->Form->button('Add a Site', [
					'class' => 'btn btn-basic mb-3 mr-2',
					'type' => 'button',
					'id' => 'addSite',
					'style' => 'float: right;'
				])
			?>
        </div>
	</fieldset>
	<?= $this->Form->end() ?>
<?php } ?>
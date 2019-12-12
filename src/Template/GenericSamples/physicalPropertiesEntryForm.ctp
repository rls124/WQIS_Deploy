<?= $this->Form->create($sample) ?>
<fieldset>
	<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-list-alt" style="font-size:20pt;"></span>  Water Quality Meter Data Entry Form
		<a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
			<span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
		</a>
	</p>
	<hr>
	<div class="collapse" id="collapseInfo">
		<div class="card card-body">
                <p>This form is used to enter bacteria levels (E. Coli raw count) for one or more sites taken on a particular date.</p>
                <ol>
                    <li>Select or enter a date.</li>
                    <li>enter a time, using 24 hour clock notation.  So 1:45 PM is 13:45.</li>
                    <li>Select a site. Sample Number will automatically be generated.</li>
                    <li>Enter Conductivity. Currently accepted values are between 0.000 and 50.000. If no reading is available this field may be left empty.</li>
                    <li>Enter Dissolved Oxygen level. Currently accepted values are between 4.000 and 12.000. If no reading is available this field may be left empty.<li>
                    <li>Enter pH level. Currently accepted values are between 6.500 and 9.000. If no reading is available this field may be left empty.</li>
                    <li>Enter Temperature. Currently accepted values are between -5.00 and 30.00. If no reading is available this field may be left empty.</li>
                    <li>Enter Total Dissolved Solids level. Currently accepted values are between 0.001 and 9.999. If no reading is available this field may be left empty.</li>
                    <li>Enter Turbidity level. Currently accepted values are between 0 and 2000. If no reading is available this field may be left empty.</li>

                    <li>Enter Comments, if desired.</li>
                </ol>
                <p>To record data for another site, press the Add Site button.</p>
            </div>
        </div>

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
					'class' => "form-control date-picker entryControl col-lg-2 textinput mt-3", //'form-control date-picker entryControl',
					'placeholder' => 'mm/dd/yyyy'
				])
			?>
        </div>
        <div class="container-fluid">
            <div class="mb-3 infoPanel">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Site</th>
                            <th>Sample<br>Number</th>
							<th>Time<br></th>
							<th>Bridge to Water Height<br>(in)</th>
                            <th>pH</th>
                            <th>Water Temp<br>(°C)</th>
                            <th>Conductivity<br>(mS/cm)</th>
                            <th>TDS<br>(g/L)</th>
                            <th>DO<br>(mg/L)</th>
                            <th>Turbidity<br>(NTU)</th>
                            <th>Comments</th>
                            <th>Actions</th>
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
									'class' => 'form-control entryControl samplenumber',
									'id' => 'sample_number-0',
									'readonly' => true
								])
							?>
							<?=
								$this->Form->control('time-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'text',
									'class' => 'form-control entryControl',
									'pattern' => "[012]?[0-9]:[0-9][0-9]"
								])
							?>
							<?=
								$this->Form->control('bridge_to_water_height-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('ph-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('water_temp-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('conductivity-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('tds-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('do-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('turbidity-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('physicalcomments-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'text',
									'class' => 'form-control entryControl'
								])
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
				$this->Form->button('Submit Physical Property Measurements', [
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
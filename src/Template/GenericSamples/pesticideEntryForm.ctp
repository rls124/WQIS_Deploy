<?= $this->Form->create($pesticideSample) ?>
<fieldset>
	<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-list-alt" style="font-size:20pt;"></span>  Pesticide Entry Form
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
                    <li>Select a site. Sample Number will automatically be generated.</li>
                    <li>Enter Atrazine level. Currently accepted values are between 0.046 and 6.610. If no reading is available this field may be left empty.</li>
                    <li>Enter Alachlor level. Currently accepted values are between 0.050 and 3.170. If no reading is available this field may be left empty.<li>
                    <li>Enter Metolachlor level. Currently accepted values are between 0.050 and 4.260. If no reading is available this field may be left empty.</li>
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
                            <th>Sample Number</th>
                            <th>Atrazine<br>(µg/L)</th>
                            <th>Alachlor<br>(µg/L)</th>
                            <th>Metochlor<br>(µg/L)</th>
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
									'id' => 'sample_number-0',
									'class' => 'form-control entryControl samplenumber',
									'readonly' => true
								])
							?>
							<?=
								$this->Form->control('atrazine-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('alachlor-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('metolachlor-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control entryControl'
								])
							?>
							<?=
								$this->Form->control('pesticidecomments-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'text',
									'class' => 'form-control entryControl',
									'placeholder' => 'Comments...'
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
				$this->Form->button('Submit Pesticide Measurements', [
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
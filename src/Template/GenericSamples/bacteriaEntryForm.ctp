<div class="container-fluid roundGreyBox">
	<?= $this->Form->create($sample) ?>
    <fieldset>

        <p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-list-alt" style="font-size:20pt;"></span>  Bacteria Entry Form
            <a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
                <span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
            </a></p>
        <hr>

        <div class="collapse" id="collapseInfo">
            <div class="card card-body">
                <p>This form is used to enter bacteria levels (E. Coli raw count) for one or more sites taken on a particular date.</p>
                <ol>
                    <li>Select or enter a date.</li>
                    <li>Select a site. Sample Number will automatically be generated.</li>
                    <li>Select E. coli. raw count. E. coli level will automatically be generated. If no reading is available this field may be left empty.</li>
                    <li>Select Total coliform raw count. Total coliform level will automatically be generated. If no reading is available this field may be left empty.</li>
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
					'class' => "form-control date-picker entryControl col-lg-2 textinput mt-3 date", //'form-control date-picker entryControl',
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
                            <th>Sample_Number</th>
                            <th>Ecoli Raw Count</th>
                            <th>Ecoli<br>(CFU/100 ml)</th>
                            <th>Total Coliform<br>Raw Count</th>
                            <th>Total Coliform<br>(CFU/100 ml)</th>
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
                            <td>
								<?=
									$this->Form->select('ecolirawcount-0', $rawCount, [
										'id' => 'ecolirawcount-0',
										'empty' => 'ND',
										'class' => 'form-control entryControl entryDropDown'
									])
								?>
                            </td>
							<?=
								$this->Form->control('ecoli-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control',
									'readonly' => true
								])
							?>
                            <td>
								<?=
									$this->Form->select('totalcoliformrawcount-0', $rawCount, [
										'id' => 'totalcoliformrawcount-0',
										'empty' => 'ND',
										'class' => 'form-control entryControl entryDropDown'
									])
								?>
                            </td>
							<?=
								$this->Form->control('totalcoliform-0', [
									'templates' => [
										'inputContainer' => '<td>{{content}}</td>',
										'label' => false
									],
									'type' => 'number',
									'class' => 'form-control',
									'readonly' => true
								])
							?>
							<?=
								$this->Form->control('comments-0', [
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
				$this->Form->button('Submit Bacteria Measurements', [
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
</div>
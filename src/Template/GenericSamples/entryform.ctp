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
                            <th>Site</th>
                            <th>Sample Number</th>
	<?php
	if ($formType == "bacteria") {
		include "bacteriaEntryForm.ctp";
	}
	elseif ($formType == "nutrient") {
		include "nutrientEntryForm.ctp";
	}
	elseif ($formType == "pesticide") {
		include "pesticideEntryForm.ctp";
	}
	elseif ($formType == "physical") {
		include "physicalPropertiesEntryForm.ctp";
	}
	else {
		?>
		<h3>You need to specify a form type</h3>
		<a href="javascript:history.back()">Go Back</a>
		<?php
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
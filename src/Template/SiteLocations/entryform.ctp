<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<?= $this->Html->css('entryForm.css') ?>
<?= $this->Html->script('siteEntryForm.js') ?>

<?=
$this->Form->create($siteLocation, [
	'id' => 'siteEntryForm'
])
?>
<fieldset>

<p class="centeredText" style="font-size: 2.5rem; margin-bottom: 0px;"><span class="glyphicon glyphicon-map-marker" style="font-size: 20pt;"></span>  Add Site Form</p>
	<h6 class="centeredText">This form is used to add a new water quality measurement site.</h6>
	<h6 class="requiredText centeredText">*All fields are required.</h6>
	<hr>
	<div class="container-fluid">
	<div class="form-group row">
	<?=
		$this->Form->input('Site Number', [
		'label' => [
			'class' => 'col-lg-2 label-reg text-right centerLabel'
		],
		'templates' => [
			'inputContainer' => '{{content}}'
		],
		'class' => "form-control col-lg-10 textinput",
		'oninput' => "ensureInput();",
		'name' => "Site_Number",
		'id' => "Site_Number"
		]);
	?>
	</div>
	<div class="form-group row">
	<?=
		$this->Form->input('Site Name', [
			'label' => [
				'class' => 'col-lg-2 label-reg text-right centerLabel'
			],
			'templates' => [
				'inputContainer' => '{{content}}'
			],
			'class' => "form-control col-lg-10 textinput",
			'oninput' => "ensureInput();",
			'name' => "Site_Name",
			'id' => "Site_Name"
		]);
	?>
	</div>
	<div class="form-group row">
		<?=
			$this->Form->input('Site Location', [
			'label' => [
				'class' => 'col-lg-2 label-reg text-right centerLabel'
			],
			'templates' => [
				'inputContainer' => '{{content}}'
			],
			'class' => "form-control col-lg-10 textinput",
			'oninput' => "ensureInput();",
			'name' => "Site_Location",
			'id' => "Site_Location"
		    ]);
		?>
	</div>
	<div class="form-group row">
		<?=
		    $this->Form->input('Longitude', [
			'label' => [
			    'class' => 'col-lg-2 label-reg text-right centerLabel'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control col-lg-10 textinput",
			'oninput' => "ensureInput();",
			'name' => "Longitude",
			'id' => "Longitude"
		    ]);
		?>
	</div>
	<div class="form-group row">
		<?=
		    $this->Form->input('Latitude', [
			'label' => [
			    'class' => 'col-lg-2 label-reg text-right centerLabel'
			],
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'class' => "form-control col-lg-10 textinput",
			'oninput' => "ensureInput();",
			'name' => "Latitude",
			'id' => "Latitude"
		    ]);
		?>
	</div>

	<?=
		$this->Form->button('Add New Site', [
			'class' => 'btn btn-basic mb-3',
			'id' => 'siteAddBtn',
			'disabled' => "true",
			'style' => 'float: right;'
		])
	?>
</div>
</fieldset>
<?= $this->Form->end() ?>

<?= $this->Html->script('entryForm.js') ?>
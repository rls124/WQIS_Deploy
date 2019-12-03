<?php
if ($admin) { ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

	<?= $this->Html->css('entryForm.css') ?>
	<?= $this->Html->script('entryForm.js') ?>

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
}
else {
	?>
	<h3>You must be an administrator to access this page</h3>
	<a href="javascript:history.back()">Go Back</a>
	<?php
}
?>
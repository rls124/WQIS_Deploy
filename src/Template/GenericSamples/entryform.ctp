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
elseif ($formType == "wqm") {
	include "wqmEntryForm.ctp";
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
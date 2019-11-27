<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<?= $this->Html->css("visualization.css") ?>
<?= $this->Html->css("chartview.css") ?>

<?= $this->Html->script("lib/d3/d3.js") ?>
<?= $this->Html->script("charting.js") ?>
<?= $this->Html->script('datePickers.js') ?>

<?php
if ($chartType == "bacteria") {
	include "bactChart.ctp";
}
elseif ($chartType == "nutrient") {
	include "nutrientChart.ctp";
}
elseif ($chartType == "pesticide") {
	include "pesticideChart.ctp";
}
elseif ($chartType == "physical") {
	include "physicalPropertiesChart.ctp";
}
?>


    <hr/>
    <div class="row">
        <div class="col-md-12 mb-3 chartBox" id="dashboard">
            <p class="centeredText chartTitle" id="chartTitle">If you're seeing this, you goofed.</p>
            <svg class="chart" id="chart"></svg>
        </div>
    </div>
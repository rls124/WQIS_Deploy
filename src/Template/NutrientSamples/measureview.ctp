<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<div class = "container roundGreyBox">
    <h3><?= $measureName ?> Samples for
	<?php
	    $siteNumber = $this->Number->format($siteLocation->Site_Number);
	    $siteName = h($siteLocation->Site_Name);
	    $siteLocation = h($siteLocation->Site_Location);
	    echo "$siteNumber $siteName - $siteLocation";
	?>
	<?=
	    $this->Form->button('View Chart', [
		'label' => false,
		'type' => 'submit',
		'class' => 'btn btn-basic btn-lg mb-3 mt-3 col-md-2 float-right',
		'id' => 'viewChartBtn'
	    ])
	?>
    </h3>
    <table id='tableView'  class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sample<br>Number</th>
		<?php echo "<th>$measureName</th>" ?>
            </tr>
        </thead>
        <tbody>
	    <?php
		$row = 0;
		foreach ($nutrientSamples as $nutrientSample):
		    ?>
		    <tr>
			<td><?= h($nutrientSample->Date) ?></td>
			<td><?= $nutrientSample->Sample_Number ?></td>
			<td><?= $this->Number->format($nutrientSample->measure) ?></td>

		    </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <div class="container paginator">
        <ul class="row pagination">
            <div class="ml-2">
		<?= $this->Paginator->first('<< ' . __('first')) ?>
		<?= $this->Paginator->prev('< ' . __('previous ')) ?>
            </div>
            <div class="ml-2">
		<?= $this->Paginator->numbers() ?>
            </div>
            <div class="ml-2">
		<?= $this->Paginator->next(__('next') . ' >') ?>
		<?= $this->Paginator->last(__('last') . ' >>') ?>
            </div>
        </ul>
        <p class="row"><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
<script>
    $("#viewChartBtn").click(function () {
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "<?= $this->Html->Url->build(['controller' => 'NutrientSamples', 'action' => 'chartview']); ?>");
	var startDate = "<?= $startdate ?>";
	var endDate = "<?= $enddate ?>";
	var params = {startdate: convertDate(startDate), enddate: convertDate(endDate), measurementSelect: "<?= $measureName ?>", site: "<?= $siteNumber ?>"};

	for (var key in params) {
	    if (params.hasOwnProperty(key)) {
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", key);
		hiddenField.setAttribute("value", params[key]);

		form.appendChild(hiddenField);
	    }
	}

	document.body.appendChild(form);
	form.submit();
    });
    function convertDate(date) {
	return date.substr(4, 2) + "/" + date.substr(6, 2) + "/" + date.substr(0, 4);
    }
</script>
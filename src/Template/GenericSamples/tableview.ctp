<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<?= $this->Html->css('loading.css') ?>

<?php
	//load variables from SESSION
	$sampleType = $_SESSION["tableType"];
	$siteLocation = $_SESSION["siteLocation"];

	//need to set a javascript variable listing the type of measurement. Kinda kludge, meh
	echo "<script>var sampleType = \"" . $sampleType . "\";</script>";
    
	if ($admin) {
		echo $this->Html->script('tableedit.js');
    }
?>

<div class = "container roundGreyBox">
    <h3><?php echo $sampleType; ?> Measurements for
	<?php
	    $siteNumber = $this->Number->format($siteLocation->Site_Number);
	    $siteName = h($siteLocation->Site_Name);
	    $siteLocation = h($siteLocation->Site_Location);
	    echo "$siteNumber $siteName - $siteLocation";
	?>
    </h3>
    <table id='tableView' class="table table-striped table-responsive">
	
	<?php
	if ($sampleType == "bacteria") {		
		include "bactTable.ctp";
	}
	elseif ($sampleType == "nutrient") {
		include "nutrientTable.ctp";
	}
	elseif ($sampleType == "pesticide") {
		include "pesticideTable.ctp";
	}
	elseif ($sampleType == "wqm") {
		include "waterQualityTable.ctp";
	}
	
	?>
        
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	    <div class="modal-header">
                <h4 class="modal-title">Comments</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
		
		<?=
		    $this->Form->control('CommentInfo', ['maxlength' => '200',
			'templates' => [
			    'inputContainer' => '{{content}}'
			],
			'data-row-number' => '',
			'class' => 'CommentInput',
			'id' => 'commentinfo',
			'name' => 'commentinfo',
			'value' => "",
			'type' => 'textarea',
			'label' => false
		    ])
		?>
	    </div>
	    <div class="modal-footer">
		<button type="button" class="btn btn-default btn-basic" data-dismiss="modal" id="saveBtn">Save</button>
		<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
	    </div>
	</div>
    </div>
</div>
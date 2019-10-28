<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<?= $this->Html->script('feedback.js') ?>
<?= $this->Html->css('userManagement.css') ?>
<?= $this->Html->css('loading.css') ?>

<div class="container roundGreyBox">

    <p class="centeredText" id="wqisHeading" style='font-size:2.5rem;'><span class="glyphicon glyphicon-list-alt" style="font-size: 20pt;"></span>  Feedback
            <a data-toggle="collapse" href="#collapseInfo" role="button" aria-expanded="false" aria-controls="collapseInfo">
                <span class="glyphicon glyphicon-question-sign" style="font-size:18pt;" data-toggle="tooltip" title="Information" id="infoGlyph"></span>
            </a></p>
    
    <div class="collapse" id="collapseInfo">
        <div class="card card-body">
            <p> This page is used to view feedback that non-admin users have left. </p>
            <ul>
                <li>To delete a comment, click the delete icon in the row containing the comment to delete.</li>
            </ul>
        </div>
    </div>
    <hr>
    <table id='tableView'  class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Date</th>
                <th>Feedback</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="feedbackTable">
	    <?php
		$row = 0;
		foreach ($FeedbackText as $feedbackData):
		    ?>
		    <tr id='tr-<?= $feedbackData->ID ?>'>
			<td id='<?php echo 'td-' . $feedbackData->ID . '-date'; ?>'><?= $feedbackData->Date ?></td>
			<td id='<?php echo 'td-' . $feedbackData->ID . '-feedback'; ?>'><?= $feedbackData->Feedback ?></td>
                        <td><a id="delete-tooltip" data-toggle="tooltip" title="Delete Feedback">
			    <?=
			    $this->Html->tag('span', "", [
				'class' => "delete glyphicon glyphicon-trash",
				'id' => 'delete-' . $feedbackData->ID,
				'name' => 'delete-' . $feedbackData->ID
			    ])
			    ?>
                            </a>
			</td>
			<?php
			$row++;
			?>
		    </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
</div>
   
<?php include "../Layout/loadingSpinner.ctp"; ?>
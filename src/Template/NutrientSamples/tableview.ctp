<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<?php
    if ($admin) {
	echo $this->Html->script('tableedit.js');
    }
?>
<div class = "container roundGreyBox">
    <h3>Nutrient Measurements for
	<?php
	    $siteNumber = $this->Number->format($siteLocation->Site_Number);
	    $siteName = h($siteLocation->Site_Name);
	    $siteLocation = h($siteLocation->Site_Location);
	    echo "$siteNumber $siteName - $siteLocation";
	?>
    </h3>
    <table id='tableView' class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sample<br>Number</th>
                <th>Nitrate/Nitrite (mg/L)</th>
                <th>Total Phosphorus (mg/L)</th>
                <th>Dissolved Reactive Phosphorus (mg/L)</th>
		<?php if ($admin) { ?>
			<th>Actions</th>
		    <?php } ?>
            </tr>
        </thead>
        <tbody>
	    <?php
		$row = 0;
		foreach ($nutrientSamples as $nutrientSample):
		    ?>
		    <tr>
			<?php if (!$admin) { ?>
	    		<td><?= h($nutrientSample->Date) ?></td>
	    		<td><?= $nutrientSample->Sample_Number ?></td>
	    		<td><?= $this->Number->format($nutrientSample->NitrateNitrite) ?></td>
	    		<td><?= $this->Number->format($nutrientSample->Phosphorus) ?></td>
	    		<td><?= $this->Number->format($nutrientSample->DRP) ?></td>
			<?php } ?>
			<?php if ($admin) { ?>
	    		<td><?=
				$this->Form->input('Date-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'value' => $nutrientSample->Date,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $nutrientSample->Date . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->input('samplenumber-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $nutrientSample->Sample_Number,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $nutrientSample->Sample_Number . ' '
				]])
				?>
	    		</td>
	    		<td><?=
				$this->Form->input('NitrateNitrite-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $nutrientSample->NitrateNitrite,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $nutrientSample->NitrateNitrite . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->input('Phosphorus-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $nutrientSample->Phosphorus,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $nutrientSample->Phosphorus . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->input('DRP-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $nutrientSample->DRP,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $nutrientSample->DRP . ' '
				    ]
				])
				?>
	    		</td>

	    		<td class="actions">
				<?=
				$this->Html->tag('span', $nutrientSample->Comments, [
				    'id' => 'Comments-' . $row,
				    'class' => 'Comments-' . $row,
				    'hidden'
				])
				?>

				<?=
				$this->Html->tag('span', "", [
				    'data-toggle' => 'modal',
				    'data-target' => '#myModal',
				    'class' => "comment glyphicon glyphicon-comment",
				    'id' => 'CommentIcon-' . $row,
				    'name' => 'CommentIcon-' . $row
				])
				?>

				<?=
				$this->Html->tag('span', "", [
				    'class' => "delete glyphicon glyphicon-trash",
				    'id' => 'Delete-' . $row,
				    'name' => 'Delete-' . $row
				])
				?>
	    		</td>
			    <?php
			    $row++;
			}
			?>
		    </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
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
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Comments</h4>
	    </div>
	    <div class="modal-body">
		<?=
		    $this->Form->input('CommentInfo', ['maxlength' => '200',
			'size' => '200',
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
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	</div>

    </div>
</div>

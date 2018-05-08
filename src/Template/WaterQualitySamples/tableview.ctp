<link href="../css/tableView.css" rel="stylesheet" type="text/css"/>

<?php
    if ($admin) {
	echo $this->Html->script('tableedit.js');
    }
?>
<div class = "container roundGreyBox">
    <h3>Water Quality Meter Data for
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
                <th>Conductivity<br>(mS/cm)</th>
                <th>Dissolved Oxygen<br>(mg/L)</th>
                <th>pH</th>
                <th>Temperature<br>(°C)</th>
                <th>Total Dissolved Solids<br>(g/L)</th>
                <th>Turbidity<br>(NTU)</th>
		<?php if ($admin) { ?>
			<th>Actions</th>
		    <?php } ?>
            </tr>
        </thead>
        <tbody>
	    <?php
		$row = 0;
		foreach ($wqmSamples as $wqmSample):
		    ?>
		    <tr>
			<?php if (!$admin) { ?>
	    		<td><?= h($wqmSample->Date) ?></td>
	    		<td><?= $wqmSample->Sample_Number ?></td>
	    		<td><?= $this->Number->format($wqmSample->Conductivity) ?></td>
	    		<td><?= $this->Number->format($wqmSample->DO) ?></td>
	    		<td><?= $this->Number->format($wqmSample->pH) ?></td>
	    		<td><?= $this->Number->format($wqmSample->Water_Temp) ?></td>
	    		<td><?= $this->Number->format($wqmSample->TDS) ?></td>
	    		<td><?= $this->Number->format($wqmSample->Turbidity) ?></td>
			<?php } ?>
			<?php if ($admin) { ?>
	    		<td>
				<?=
				$this->Form->input('Date-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'value' => $wqmSample->Date,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->Date . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('samplenumber-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->Sample_Number,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->Sample_Number . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('Conductivity-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->Conductivity,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->Conductivity . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('DO-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->DO,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->DO . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('pH-' . $row, ['maxlength' => '54',
				    'size' => '4',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->pH,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->pH . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('Water_Temp-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->Water_Temp,
				    'style' => 'display: none',
				    'id' => 'Water_Temp-' . $row,
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->Water_Temp . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('TDS-' . $row, ['maxlength' => '4',
				    'size' => '4',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->TDS,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->TDS . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->input('Turbidity-' . $row, ['maxlength' => '4',
				    'size' => '4',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->Turbidity,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->Turbidity . ' '
				    ]
				])
				?>
	    		</td>
	    		<td class="actions">
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

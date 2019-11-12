        <tbody>
	    <?php
		$row = 0;
		foreach ($samples as $bacteriaSample):
		    ?>
		    <tr>
	    		<td>
				<?=
				$this->Form->control('Date-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'value' => $bacteriaSample->Date,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $bacteriaSample->Date . ' '
				    ]
				]);
				?>
	    		</td>
	    		<td><?=
				$this->Form->control('samplenumber-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $bacteriaSample->Sample_Number,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $bacteriaSample->Sample_Number . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->control('EcoliRawCount-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $bacteriaSample->EcoliRawCount,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $bacteriaSample->EcoliRawCount . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->control('Ecoli-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $bacteriaSample->Ecoli,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $bacteriaSample->Ecoli . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->control('TotalColiformRawCount-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $bacteriaSample->TotalColiformRawCount,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $bacteriaSample->TotalColiformRawCount . ' '
				    ]
				])
				?>
	    		</td>
	    		<td><?=
				$this->Form->control('TotalColiform-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $bacteriaSample->TotalColiform,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $bacteriaSample->TotalColiform . ' '
				    ]
				])
				?>
	    		</td>
				<td>
				<?=
				$this->Html->tag('span', "", [
				    'data-toggle' => 'modal',
				    'data-target' => '#myModal',
				    'class' => "comment glyphicon glyphicon-comment",
				    'id' => 'CommentIcon-' . $row,
				    'name' => 'CommentIcon-' . $row
				])
				?>
	    		</td>
	    		<td class="actions">
				<?=
				$this->Html->tag('span', $bacteriaSample->BacteriaComments, [
				    'id' => 'BacteriaComments-' . $row,
				    'class' => 'BacteriaComments-' . $row,
				    'hidden'
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
			    <?php $row++; ?>
		    </tr>
		<?php endforeach; ?>
        </tbody>
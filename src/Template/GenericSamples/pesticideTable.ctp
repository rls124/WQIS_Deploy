        <tbody>
	    <?php
		$row = 0;
		foreach ($samples as $pesticideSample):
		    ?>
		    <tr>
	    		<td>
				<?=
				$this->Form->control('Date-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'value' => $pesticideSample->Date,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $pesticideSample->Date . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('samplenumber-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $pesticideSample->Sample_Number,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $pesticideSample->Sample_Number . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('Atrazine-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $pesticideSample->Atrazine,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $pesticideSample->Atrazine . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('Alachlor-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $pesticideSample->Alachlor,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $pesticideSample->Alachlor . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('Metolachlor-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $pesticideSample->Metolachlor,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $pesticideSample->Metolachlor . ' '
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
				$this->Html->tag('span', $pesticideSample->PesticideComments, [
				    'id' => 'PesticideComments-' . $row,
				    'class' => 'PesticideComments-' . $row,
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
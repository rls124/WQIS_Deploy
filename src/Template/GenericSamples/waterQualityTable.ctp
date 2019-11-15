        <tbody>
	    <?php
		$row = 0;
		foreach ($samples as $wqmSample):
		    ?>
		    <tr>
	    		<td>
				<?=
				$this->Form->control('Date-' . $row, ['maxlength' => '11',
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
				$this->Form->control('samplenumber-' . $row, ['maxlength' => '11',
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
				$this->Form->control('Conductivity-' . $row, ['maxlength' => '5',
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
				$this->Form->control('DO-' . $row, ['maxlength' => '5',
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
				$this->Form->control('Bridge_to_Water_Height-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $wqmSample->Bridge_to_Water_Height,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $wqmSample->Bridge_to_Water_Height . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('pH-' . $row, ['maxlength' => '54',
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
				$this->Form->control('Water_Temp-' . $row, ['maxlength' => '11',
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
				$this->Form->control('TDS-' . $row, ['maxlength' => '4',
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
				$this->Form->control('Turbidity-' . $row, ['maxlength' => '4',
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
				$this->Html->tag('span', $wqmSample->WaterQualityComments, [
				    'id' => 'WaterQualityComments-' . $row,
				    'class' => 'WaterQualityComments-' . $row,
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
        <tbody>
	    <?php
		$row = 0;
		foreach ($samples as $physicalSample):
		    ?>
		    <tr>
	    		<td>
				<?=
				$this->Form->control('Date-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'value' => $physicalSample->Date,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->Date . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('samplenumber-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->Sample_Number,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->Sample_Number . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('Conductivity-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->Conductivity,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->Conductivity . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('DO-' . $row, ['maxlength' => '5',
				    'size' => '5',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->DO,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->DO . ' '
				    ]
				])
				?>
	    		</td>
				<td>
					<div class="input text">
						<label style="display: in-line; cursor: pointer" class="btn btn-thin inputHide" for="bridge_to_water_height-<?php echo $row;?>"><?php echo $physicalSample->Bridge_to_Water_Height;?> </label>
						<input type="text" name="Bridge_to_Water_Height-<?php echo $row;?>" maxlength="5" size="5" class="inputfields tableInput" style="display: none" id="bridge_to_water_height-<?php echo $row;?>" value="<?php echo $physicalSample->Bridge_to_Water_Height;?>"/>
					</div>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('pH-' . $row, ['maxlength' => '54',
				    'size' => '4',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->pH,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->pH . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('Water_Temp-' . $row, ['maxlength' => '11',
				    'size' => '11',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->Water_Temp,
				    'style' => 'display: none',
				    'id' => 'Water_Temp-' . $row,
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->Water_Temp . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('TDS-' . $row, ['maxlength' => '4',
				    'size' => '4',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->TDS,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->TDS . ' '
				    ]
				])
				?>
	    		</td>
	    		<td>
				<?=
				$this->Form->control('Turbidity-' . $row, ['maxlength' => '4',
				    'size' => '4',
				    'class' => 'inputfields tableInput',
				    'value' => $physicalSample->Turbidity,
				    'style' => 'display: none',
				    'label' => [
					'style' => 'display: in-line; cursor: pointer',
					'class' => 'btn btn-thin inputHide',
					'text' => $physicalSample->Turbidity . ' '
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
				$this->Html->tag('span', $physicalSample->PhysicalComments, [
				    'id' => 'PhysicalComments-' . $row,
				    'class' => 'PhysicalComments-' . $row,
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
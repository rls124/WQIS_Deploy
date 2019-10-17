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
			<th>Actions</th>
            </tr>
        </thead>
        <tbody>
	    <?php
		$row = 0;
		foreach ($wqmSamples as $wqmSample):
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
	    		<td class="actions">
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
<thead>
            <tr>
                <th>Date</th>
                <th>Sample<br>Number</th>
                <th>Nitrate/Nitrite (mg/L)</th>
                <th>Total Phosphorus (mg/L)</th>
                <th>Dissolved Reactive Phosphorus (mg/L)</th>
			<th>Actions</th>
            </tr>
        </thead>
        <tbody>
	    <?php
		$row = 0;
		foreach ($samples as $nutrientSample):
		    ?>
		    <tr>
	    		<td><?=
				$this->Form->control('Date-' . $row, ['maxlength' => '11',
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
				$this->Form->control('samplenumber-' . $row, ['maxlength' => '11',
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
				$this->Form->control('NitrateNitrite-' . $row, ['maxlength' => '5',
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
				$this->Form->control('Phosphorus-' . $row, ['maxlength' => '5',
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
				$this->Form->control('DRP-' . $row, ['maxlength' => '5',
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
			    <?php $row++; ?>
		    </tr>
    <?php endforeach; ?>
        </tbody>
<?php
for ($i=0; $i<sizeof($controlNames); $i++) {
	echo $this->Form->control($controlNames[$i], [
		'templates' => [
			'inputContainer' => '<td>{{content}}</td>',
			'label' => false
		],
		'type' => 'text',
		'class' => 'form-control entryControl'
	]);
}
?>
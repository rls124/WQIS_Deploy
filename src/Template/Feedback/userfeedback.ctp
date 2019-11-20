<?= $this->Html->css('feedback.css') ?>

<?=
	$this->Form->create(false, [
		'id' => 'feedbackForm'
		]
	)
?>
<br />
<center><h1>Feedback</h1></center>
<center><p>We appreciate you taking the time to visit our site. If you have any suggestions on how to improve it, please leave a comment below!</p></center>

<div class="textarea">
<?=
	$this->Form->textarea('feedback', [
		'id' => 'feedbackID',
		'templates' => [
			'inputContainer' => '{{content}}'
		],
		'rows' => '10',
		'cols' => '117'
	])
?>
</div>
<?= $this->Form->button(__('Submit'), ['class' => 'btn mb-3  btn-basic btn-lg sub-btn', 'id' => 'submit-btn', 'style' => 'float: right'], ['action' => 'userfeedback']); ?>
<?= $this->Form->end() ?>
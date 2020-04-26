<?php
if ($admin) {
?>
<?= $this->Html->script("contact.js") ?>
<?= $this->Html->css("userManagement.css") ?>
<?= $this->Html->css("loading.css") ?>

<p class="centeredText" id="wqisHeading" style="font-size:2.5rem;"><span class="glyphicon glyphicon-list-alt" style="font-size: 20pt;"></span>  Feedback</p>
<hr>
<table id="tableView" class="table table-striped table-responsive">
	<thead>
		<tr>
			<th>Date</th>
			<th>User</th>
			<th>Name</th>
			<th>Email</th>
			<th>Feedback</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody id="feedbackTable">
	<?php
		$row = 0;
		foreach ($FeedbackText as $feedbackData):
			?>
			<tr id="tr-<?= $feedbackData->ID ?>">
				<td><?= $feedbackData->Date ?></td>
				<td>
					<?php
					if ($feedbackData->User) {
						echo $feedbackData->User;
					}
					else {
						echo "<i>Not logged in</i>";
					}
					?>
				</td>
				<td><?= $feedbackData->Name ?></td>
				<td><?= $feedbackData->Email ?></td>
				<td><?= $feedbackData->Feedback ?></td>
				<td>
					<a data-toggle="tooltip" title="Delete Feedback">
					<?=
						$this->Html->tag("span", "", [
							"class" => "delete glyphicon glyphicon-trash",
							"id" => "delete-" . $feedbackData->ID,
						])
					?>
					</a>
				</td>
			<?php
			$row++;
			?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php
}
else {
	?>
	<h3>You must be an administrator to access this page</h3>
	<a href="javascript:history.back()">Go Back</a>
	<?php
}
?>
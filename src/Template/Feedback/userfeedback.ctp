<?= $this->Html->css("feedback.css") ?>

<br/>
<center><h1>Feedback</h1></center>
<center><p>We appreciate you taking the time to visit our site. If you have any suggestions on how to improve it, please leave a comment below!</p></center>

<form>
	<div style="text-align: center">
		<div style="display: inline-block">
			<?php if (!$loggedIn) { ?>
			<div style="text-align: left">
				<b>Name (optional):</b>
				<input type="text" class="form-control" id="name" name="name">
			</div>
			<div style="text-align: left">
				<b>Email (optional):</b>
				<input type="email" class="form-control form-control-sm" name="email">
			</div>
			<?php } ?>
			<div style="text-align: left">
				<b>Message:</b>
			</div>
			
			<textarea class="form-control" id="feedbackID" name="feedback" rows="5" cols="100"></textarea>
		</div>
		<div>
			<button type="submit" class="btn mb-3  btn-basic btn-lg sub-btn" id="submit-btn" formmethod="post" action="userfeedback">Submit</button>
		</div>
	</div>
</form>
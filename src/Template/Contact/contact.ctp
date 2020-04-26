<div id="message" class="message hidden"></div>

<p class="centeredText" style="font-size:2.5rem;"><span class="glyphicon glyphicon-home" style="font-size:20pt;"></span>  Contact us</p>
<p class="centeredText">
	We appreciate you taking the time to visit our site. If you have any suggestions on how to improve it, or have a question, please contact us using the form below!</p></center>
</p>
<hr>

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
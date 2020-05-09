$(document).ready(function () {
	//allow dropdowns that have a link as the root element to be clicked
	$(".dropdown").click(function(event) {
		var tgt = event.target.href;
		if (tgt[tgt.length-1] != "#") { //if the last char is # that means its a dropdown that has to be clicked to activate and doesn't actually have a destination
			location.href = tgt;
		}
	});
});
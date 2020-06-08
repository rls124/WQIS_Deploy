$(document).ready(function () {
	$("input:file").change(function () {
		var fileName = $(this).val();
		fileName = fileName.replace(/^.*[\\\/]/, "");
		document.getElementById("FileUploadLabel").style.color = "black";
		$("#FileUploadLabel").html(fileName);
	});
	
	$("#entryType").change(function () {
		$("#EntryFormBtn").prop("disabled", ($(this).val() == "")); //enable the button if any entry form type is selected, disable it otherwise
	});

	$("#chooseFileButton").change(function () {
		$("#submitFile").prop("disabled", false);
	});
	
	$("#entryForm").attr("action", "/WQIS/samples/entryform");
	$("#fileupload").attr("action", "/WQIS/samples/uploadlog");
});

$(function(){
	$("#fileupload").submit(function(){
		$("input[type='submit']", this)
			.val("Please Wait...")
			.attr("disabled", "disabled");
	
		$("#loadingIcon").css("visibility", "visible");
		return true;
	});
});
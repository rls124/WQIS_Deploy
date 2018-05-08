function ensureInput() {
    with (document.forms.siteEntryForm) {
	var btn = document.getElementById("siteAddBtn");
	if (Site_Number.value != "" && Site_Name.value != "" && Site_Location.value != "" &&
		Longitude.value != "" && Latitude.value != "") {
	    btn.disabled = false;
	} else {
	    btn.disabled = true;
	}
    }
}

$(document).ready(function () {
    $("#Site_Number")
	    .popover({trigger: "focus", title: 'Site Number Example', placement: "top", html: true,
		content: "Ex: 167"})
	    .blur(function () {
		$(this).popover('hide');
	    });
    $("#Site_Name")
	    .popover({trigger: "focus", title: 'Site Name Example', placement: "top", html: true,
		content: "Ex: Tonkel Road"})
	    .blur(function () {
		$(this).popover('hide');
	    });
    $("#Site_Location")
	    .popover({trigger: "focus", title: 'Site Location Example', placement: "top", html: true,
		content: "Ex: Dibbling Ditch"})
	    .blur(function () {
		$(this).popover('hide');
	    });
    $("#Longitude")
	    .popover({trigger: "focus", title: 'Longitude Example', placement: "top", html: true,
		content: "Enter as decimal degrees, e.g. -85.0767720000"})
	    .blur(function () {
		$(this).popover('hide');
	    });
    $("#Latitude")
	    .popover({trigger: "focus", title: 'Latitude Example', placement: "top", html: true,
		content: "Enter as decimal degrees, e.g. 41.2185890000"})
	    .blur(function () {
		$(this).popover('hide');
	    });
});
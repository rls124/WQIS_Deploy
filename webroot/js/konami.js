var Konami = function (callback) {
	var konami = {
		addEvent: function (obj, type, fn, ref_obj) {
			if (obj.addEventListener) {
				obj.addEventListener(type, fn, false);
			}
		},
		removeEvent: function (obj, eventName, eventCallback) {
			if (obj.removeEventListener) {
				obj.removeEventListener(eventName, eventCallback);
			}
			else if (obj.attachEvent) {
				obj.detachEvent(eventName);
			}
		},
		input: "",
		pattern: "38384040373937396665",
		keydownHandler: function (e, ref_obj) {
			if (ref_obj) {
				konami = ref_obj;
			} // IE
			konami.input += e ? e.keyCode : event.keyCode;
			if (konami.input.length > konami.pattern.length) {
				konami.input = konami.input.substr((konami.input.length - konami.pattern.length));
			}
			if (konami.input === konami.pattern) {
				konami.code(konami._currentLink);
				konami.input = '';
				e.preventDefault();
				return false;
			}
		},
		load: function (link) {
			this._currentLink = link;
			this.addEvent(document, "keydown", this.keydownHandler, this);
		},
		unload: function () {
			this.removeEvent(document, 'keydown', this.keydownHandler);
		},
		code: function (link) {
			window.location = link
		}
	}

	typeof callback === "string" && konami.load(callback);
	if (typeof callback === "function") {
		konami.code = callback;
		konami.load();
	}

	return konami;
};

if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
	module.exports = Konami;
}
else {
	if (typeof define === 'function' && define.amd) {
		define([], function() {
			return Konami;
		});
	}
	else {
		window.Konami = Konami;
	}
}
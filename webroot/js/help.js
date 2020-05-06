//If a user clicks on a help link on another page, it'll jump to the relevant section, but the heading gets cut off by the fixed navbar. So we need to do some wizardry to offset that
(function(document, history, location) {
	var HISTORY_SUPPORT = !!(history && history.pushState);

	var anchorScrolls = {
		ANCHOR_REGEX: /^#[^ ]+$/,
		OFFSET_HEIGHT_PX: 50,

		//establish events, and fix initial scroll position if a hash is provided
		init: function() {
			this.scrollToCurrent();
			window.addEventListener("hashchange", this.scrollToCurrent.bind(this));
			document.body.addEventListener("click", this.delegateAnchors.bind(this));
		},

		//return the offset amount to deduct from the normal scroll position. Modify as appropriate to allow for dynamic calculations
		getFixedOffset: function() {
			return this.OFFSET_HEIGHT_PX;
		},

		//if the provided href is an anchor which resolves to an element on the page, scroll to it
		scrollIfAnchor: function(href, pushToHistory) {
			var match, rect, anchorOffset;

			if (!this.ANCHOR_REGEX.test(href)) {
				return false;
			}

			match = document.getElementById(href.slice(1));

			if (match) {
				rect = match.getBoundingClientRect();
				anchorOffset = window.pageYOffset + rect.top - this.getFixedOffset();
				window.scrollTo(window.pageXOffset, anchorOffset);

				//add the state to history as-per normal anchor links
				if (HISTORY_SUPPORT && pushToHistory) {
					history.pushState({}, document.title, location.pathname + href);
				}
			}

			return !!match;
		},

		//attempt to scroll to the current location's hash
		scrollToCurrent: function() {
			this.scrollIfAnchor(window.location.hash);
		},

		//if the click event's target was an anchor, fix the scroll position
		delegateAnchors: function(e) {
			var elem = e.target;

			if (elem.nodeName === "A" && this.scrollIfAnchor(elem.getAttribute("href"), true)) {
				e.preventDefault();
			}
		}
	};

	window.addEventListener("DOMContentLoaded", anchorScrolls.init.bind(anchorScrolls));
})(window.document, window.history, window.location);
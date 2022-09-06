jQuery(function() {
	var el_notice, msg_id = el_notice, btn_dismiss;

	// Display the notice after the page was loaded.
	function initialize() {
		if (! jQuery(".frash-notice:visible").length) {
			el_notice = jQuery(".frash-notice");
			msg_id = el_notice.find("input[name=msg_id]").val();
			btn_dismiss = el_notice.find(".frash-notice-dismiss");

			// Dismiss the notice without any action.
			btn_dismiss.click(function(ev) {
				ev.preventDefault();
				notify_wordpress("wdev_notice_dismiss", btn_dismiss.data("msg"));
			});

			// Display the notification.
			el_notice.fadeIn(500);
		}
	}

	// Hide the notice after a CTA button was clicked
	function remove_notice() {
		el_notice.fadeTo(100 , 0, function() {
			el_notice.slideUp(100, function() {
				el_notice.remove();
			});
		});
	}

	// Notify WordPress about the users choice and close the message.
	function notify_wordpress(action, message) {
		var ajax_data = {};

		if ('0' !== msg_id) {
			el_notice.attr("data-message", message);
			el_notice.addClass("loading");

			ajax_data.msg_id = msg_id;
			ajax_data.action = action;
			jQuery.post(
				window.ajaxurl,
				ajax_data,
				remove_notice
			);
		} else {
			remove_notice();
		}
	}

	window.setTimeout(initialize, 550);
});

/**
 * JS used for UI of WPMUDEV plugins.
 */

/*
 * Initialize the Dashboard once the page is fully loaded.
 */
jQuery(function() {
	// Small layout fix (changes the global background color).
	jQuery("html").addClass("wpmud-html");

	// Add event handlers to show overlay dialogs.
	jQuery(".wpmud").on("click", "a[rel=dialog]", showDialog);

	// Open any auto-show overlays.
	jQuery("dialog.auto-show").first().each(showDialog);

	// Select code content on click.
	jQuery(".wpmud").on("click", "code, pre, .sel-all", selectOnClick);

	// Disable the one-time-click buttons on first click.
	jQuery(".wpmud").on("click", ".one-click", disableOnClick);

	// Start animation when clicking on spinner-icons.
	jQuery(".wpmud").on("click", ".has-spinner", spinOnClick);

	// Make Ajax-submit buttons behave as Ajax-submit buttons.
	jQuery(".wpmud").on("click", ".as-ajax", ajaxSubmitLink);

	// Handle close buttons inside boxes.
	jQuery(".wpmud").on("click", ".can-close .close", closeElement);

	// Initialize all tab-areas.
	jQuery(".wpmud .tabs").each(function(){
		WDP.wpmuTabs(this);
	});

	// Initialize all vertical tab-areas.
	jQuery(".wpmud .vertical-tabs").each(function(){
		WDP.wpmuVerticalTabs(this);
	});

	// Convert all select lists to fancy WPMU Select lists.
	jQuery(".wpmud select").each(function(){
		WDP.wpmuSelect(this);
	});

	// Convert all all search-fields to WPMU search areas.
	jQuery(".wpmud input[type=search]").each(function(){
		WDP.wpmuSearchfield(this);
	});

	// Check the page URL for special actions
	checkLocalRoutes();

	// Add new jQuery function jQuery().loading(true|false)
	jQuery.fn.extend({
		loading: function(state, message) {
			if (undefined === state) { state = true; }
			this.each(function() {
				if (state) {
					jQuery(this).addClass("loading");
				} else {
					jQuery(this).removeClass("loading");
				}
			});
			if (state && message) {
				jQuery(".wpmud-loading-info").remove();
				message = "<p><span class='loading'></span> " + message + "</p>";
				jQuery("<div></div>")
					.addClass("wpmud-loading-info")
					.appendTo("body")
					.html(message);
			} else if (!state) {
				jQuery(".wpmud-loading-info").remove();
			}
			return this;
		}
	});

	// When a rel=dialog element was clicked we find and open the target dialog.
	function showDialog(ev) {
		var el = jQuery(this);
		var args = {};

		if (el.data("width")) { args.width = el.data("width"); }
		if (el.data("height")) { args.height = el.data("height"); }
		if (el.data("class")) { args.class = el.data("class"); }
		if (el.data("title")) { args.title = el.data("title"); }

		if (el.is("dialog")) {
			WDP.showOverlay("#" + el.attr("id"), args);
		} else if (el.attr("href")) {
			WDP.showOverlay(el.attr("href"), args);
		}
		return false;
	}

	// Select all text inside the element.
	function selectOnClick(ev) {
		WDP.selectText(this);
	}

	// Disable the element on click.
	function disableOnClick(ev) {
		var form, el = jQuery(this);

		window.setTimeout(function() {
			el.prop("disabled", true).addClass("disabled").loading(true);

			if (el.hasClass("button")) {
				form = el.closest("form");
				if ( form.length ) {
					form.find(":input").prop("disabled", true).addClass("disabled");
					form.prop("disabled", true).addClass("disabled");
				}
			}
		}, 20);
	}

	// Start animating the element on click.
	function spinOnClick(ev) {
		var icon, el = jQuery(this);
		if (el.hasClass("spin-on-click")) { icon = el; }
		else { icon = el.find(".spin-on-click"); }
		icon.addClass("spin");
	}

	// Submit a link as ajax request instead of refreshing the window.
	function ajaxSubmitLink(ev) {
		var el = jQuery(this),
			url = el.attr("href");

		if (! url) { return false; }

		el.addClass("loading disabled").prop("disabled", true);
		jQuery.post(url)
			.always(function() {
				el.removeClass("loading disabled").prop("disabled", false);
			}).fail(function() {
				WDP.showError({"message": false});
			});

		return false;
	}

	// Parses the hash-tag in the current address bar.
	function checkLocalRoutes() {
		var route = window.location.hash.substr(1);
			parts = route.split("=");

		WDP.localRoutes = {
			"action": false,
			"param": false
		};
		if (! route.length ) { return; }

		WDP.localRoutes.action = parts[0];
		if (parts.length > 1) {
			WDP.localRoutes.param = parts[1];
		}
	}

	// Closes an element (i.e. hides and removes it)
	function closeElement() {
		var box = jQuery(this).closest(".can-close");

		box.css({height: box.outerHeight() });
		box.addClass("animated collapse");

		function removeElementBox() {
			box.remove();
		}

		window.setTimeout(removeElementBox, 450);
	}
});

/*
 * Define Dashboard namespace with all the functions.
 * WDP = WPMUDEV Dashboard Plugin
 */
window.WDP = window.WDP || {};

/**
 * Display a modal overlay on the screen.
 * Only one overlay can be displayed at once.
 *
 * The dialog source must be (or contain) an <dialog> element.
 * Only the <dialog> element is parsed and displayed in the overlay.
 *
 * @since  4.0.0
 * @param  string dialogSource Either CSS class/ID, URL or HTML string.
 *         - ID must start with a hash '#'.
 *         - Class must start with a dot '.'.
 *         - URL contains '://' (absolute URL).
 *         - URL starts with slash '/' (relative URL).
 *         - Everything else is considered HTML string.
 * @param  array args Optional arguments, like callbacks
 *         @var callback  onShow
 *         @var int       width (only for iframes)
 *         @var int       height (only for iframes)
 *         @var string    class
 *         @var string    title
 */
WDP.showOverlay = function(dialogSource, args) {
	var retry = false;

	if ('object' !== typeof args) { args = {}; }
	args.onShow = args.onShow || false;

	// 1.) fetch the dialog code from the appropriate source.
	if ('#' === dialogSource[0] || '.' === dialogSource[0]) {
		/*
		 * Type 1: CSS selector
		 * The page contains a <dialog> element that is instantly displayed.
		 */
		var dialog = jQuery('dialog' + dialogSource);
		showTheDialog(dialog);
	} else if (-1 !== dialogSource.indexOf('://') || '/' === dialogSource[0]) {
		var type;
		if ('/' === dialogSource[0]) { type = 'ajax'; }
		else if (0 === dialogSource.indexOf(WDP.data.site_url)) { type = 'ajax'; }
		else { type = 'iframe'; }

		if ('ajax' === type) {
			/*
			 * Type 2a: AJAX handler
			 * The URL is relative or starts with the WordPress site_url. The
			 * URL is called as ajax handler. Result can be either HTML code or
			 * a JSON object with attributes `obj.success` and `obj.data.html`
			 * In either case, the returned HTML needs to contain a <dialog> tag
			 */
			jQuery.get(
				dialogSource,
				'',
				function(resp) {
					var el;
					if ('{' === resp[0]) { resp = jQuery.parseJSON(resp); }
					if ('object' === typeof resp) {
						if (resp && resp.success && resp.data.html) {
							el = jQuery(resp.data.html);
						}
					} else {
						el = jQuery(resp);
					}

					if (!el || !el.length) { return; }
					if (el.is('dialog')) { showTheDialog(el); }
					else { showTheDialog(el.find('dialog')); }
				}
			);
		} else if ('iframe' === type) {
			/*
			 * Type 2b: iframe container
			 * An external URL is loaded inside an iframe which is displayed
			 * inside the dialog. The external URL may return any content.
			 */
			var iframe = jQuery('<div><iframe class="fullsize"></iframe></div>');
			iframe.find('iframe').attr('src', dialogSource);
			if (args.width) { iframe.find('iframe').attr('width', args.width); }
			if (args.height) { iframe.find('iframe').attr('height', args.height); }
			showTheDialog(iframe);
		}
	} else {
		/*
		 * Type 3: Plain HTML code
		 * The dialog source is plain HTML code that is parsed and displayed;
		 * the code needs to contain an <dialog> element.
		 */
		var el = jQuery(dialogSource);
		if (el.is('dialog')) { showTheDialog(el); }
		else { showTheDialog(el.find('dialog')); }
	}

	// 2.) Render the dialog.
	function showTheDialog(dialog) {
		if ( ! dialog.length ) { return; }
		if ( ! WDP.prepareOverlay() ) {
			if ( ! retry ) {
				retry = true;
				WDP.closeOverlay();
				window.setTimeout(function() { showTheDialog(dialog); }, 610);
			}
			return;
		}

		if (! args.title) {
			args.title = dialog.attr('title');
		}
		if (args.class) {
			dialog.addClass(args.class);
		}

		WDP.overlay.box_title.find('h3').html(args.title);
		WDP.overlay.box_content.html(dialog.html());

		WDP.overlay.wrapper.addClass(dialog.attr('class'));
		if (dialog.hasClass('no-close')) {
			WDP.overlay.wrapper.addClass('no-close');
			WDP.overlay.close.remove();
		}
		if (dialog.find('.title-action').length) {
			WDP.overlay.box_content.find('.title-action').appendTo(WDP.overlay.box_title);
		}

		WDP.overlay.box_content.on('click', '.close', WDP.closeOverlay);
		jQuery(window).on('resize', WDP.positionOverlay);

		WDP.overlay.container.addClass('has-overlay');
		WDP.overlay.wrapper.show();
		WDP.overlay.box.addClass('bounce-in');
		WDP.overlay.back.addClass('fade-in');
		WDP.overlay.visible = true;

		WDP.positionOverlay();

		window.setTimeout(function(){
			WDP.overlay.box.removeClass('bounce-in');
			WDP.overlay.back.removeClass('fade-in');
		}, 1000);

		if ('function' === typeof args.onShow) { args.onShow(); }
	}

	return WDP;
};

/**
 * Closes the current modal overlay again.
 *
 * @since  4.0.0
 */
WDP.closeOverlay = function() {
	if ( WDP.prepareOverlay() ) { return WDP; }

	WDP.overlay.container.removeClass('has-overlay');
	WDP.overlay.box.addClass('bounce-out');
	WDP.overlay.back.addClass('fade-out');
	jQuery(window).off('resize', WDP.positionOverlay);

	window.setTimeout(function() {
		WDP.overlay.wrapper.hide()
	}, 550);
	window.setTimeout(function() {
		WDP.overlay.wrapper.remove();
		WDP.overlay.wrapper = null;
		WDP.overlay.visible = false;
	}, 600);

	return WDP;
};

/**
 * Updates the position of the overlay to keep it vertically centered on the
 * screen.
 *
 * @since  4.0.0
 */
WDP.positionOverlay = function() {
	var availHeight, needHeight, newOffset;

	if ( WDP.prepareOverlay() ) { return WDP; }

	availHeight = WDP.overlay.scroll.height();
	needHeight = WDP.overlay.box.outerHeight();
	newOffset = (availHeight - needHeight) / 2;

	if ( newOffset < 20 ) { newOffset = 20; }
	WDP.overlay.box.css({ marginTop: newOffset });

	return WDP;
};

/**
 * Creates all the DOM elements needed to display the overlay element.
 *
 * @since  4.0.0
 * @return bool True if the modal is ready to be displayed.
 */
WDP.prepareOverlay = function() {
	var offset = jQuery('#wpcontent').offset();

	WDP.overlay = WDP.overlay || {};

	if ( WDP.overlay.visible ) { return false; }

	if ( ! WDP.overlay.wrapper ) {
		WDP.overlay.container = jQuery('.wpmud-html');
		WDP.overlay.wrapper = jQuery('<div class="dev-overlay"></div>');
		WDP.overlay.back = jQuery('<div class="back"></div>');
		WDP.overlay.scroll = jQuery('<div class="box-scroll"></div>');
		WDP.overlay.box = jQuery('<div class="box"></div>');
		WDP.overlay.box_title = jQuery('<div class="title"><h3></h3></div>');
		WDP.overlay.box_content = jQuery('<div class="content"></div>');
		WDP.overlay.close = jQuery('<div class="close">&times;</div>');

		WDP.overlay.back.appendTo(WDP.overlay.wrapper);
		WDP.overlay.scroll.appendTo(WDP.overlay.wrapper);
		WDP.overlay.box.appendTo(WDP.overlay.scroll);
		WDP.overlay.box_title.appendTo(WDP.overlay.box);
		WDP.overlay.box_content.appendTo(WDP.overlay.box);
		WDP.overlay.close.appendTo(WDP.overlay.box_title);
		WDP.overlay.wrapper.appendTo('body');

		WDP.overlay.close.click(WDP.closeOverlay);
	}

	return true;
};

/**
 * Select all text inside the HTML element. This can be a div/code/input/etc.
 *
 * @since  4.0.0
 * @param  object el The HTML element.
 */
WDP.selectText = function(el) {
	var range, jq;
	jq = jQuery( el );
	el = jq[0];

	if ( jq.is(':input') ) {
		jq.focus().select();
	} else if ( document.selection ) {
		range = document.body.createTextRange();
		range.moveToElementText(el);
		range.select();
	} else if ( window.getSelection ) {
		range = document.createRange();
		range.selectNode(el);
		window.getSelection().addRange(range);
	}

	return WDP;
};

/**
 * Initialize the functions of a tab-area
 *
 * @since  4.0.0
 * @param  object el The tab-area container element.
 */
WDP.wpmuTabs = function(el) {
	var jq = jQuery(el).closest('.tabs');

	if (! jq.length) { return; }

	// Resize the tab-area after short delay.
	function resizeArea() {
		window.setTimeout(resizeAreaHandler, 20);
	}

	// Resize the tab area to match the current tab.
	function resizeAreaHandler() {
		var current = jq.find('.tab > input:checked').parent(),
			content = current.find('.content');

		jq.height(content.outerHeight() + current.outerHeight() - 6);
	}

	// Updates the URL hash to keep tab open during page refresh
	function updateHash() {
		var current = jq.find('.tab > input:checked');

		if (current.attr('id').length) {
			WDP.updateHash(current.attr('id'));
		}
		resizeArea();
	}

	// Open the tab that is specified in window URL hash
	function switchTab() {
		var curTab,
			route = window.location.hash.replace( /[^\w-_]/g, '' );

		if (route) {
			curTab = jq.find('input#' + route);

			if (curTab.length && ! curTab.prop('checked')) {
				curTab.prop('checked', true);
				scrollWindow();
			}
		}
	}

	// Scroll the window to top of the tab list.
	function scrollWindow() {
		resizeArea();
		jQuery('html, body').scrollTop(
			jq.offset().top
			- parseInt( jQuery('html').css('paddingTop') )
			- 20
		);
	}

	// Constructor.
	function init() {
		jq.on('click', '.tab > input[type=radio]', updateHash);
		jQuery(window).on('hashchange', switchTab);

		resizeArea();
		switchTab();
	}

	init();

	return WDP;
};

/**
 * Initialize the functions of a vertical tab-area
 *
 * @since  4.0.0
 * @param  object el The tab-area container element.
 */
WDP.wpmuVerticalTabs = function(el) {
	var jq = jQuery(el).closest('.vertical-tabs'),
		minHeight = 0;

	if (! jq.length) { return; }

	// Resize the tab-area after short delay.
	function resizeArea() {
		window.setTimeout(resizeAreaHandler, 20);
	}

	// Resize the tab area to match the current tab.
	function resizeAreaHandler() {
		var current = jq.find('.tab > input:checked').parent(),
			content = current.find('.content'),
			newHeight = content.outerHeight();

		if (newHeight < minHeight) { newHeight = minHeight; }
		content.css({'min-height': minHeight});
		jq.height(newHeight);
	}

	// Find the height of the tab labels
	function calcMinHeight() {
		minHeight = 0;
		jq.find('.tab > label:visible').each(function() {
			minHeight += jQuery(this).outerHeight();
		});
	}

	// Updates the URL hash to keep tab open during page refresh
	function updateHash() {
		var current = jq.find('.tab > input:checked');

		if (current.attr('id').length) {
			WDP.updateHash(current.attr('id'));
		}
		resizeArea();
	}

	// Open the tab that is specified in window URL hash
	function switchTab() {
		var curTab,
			route = window.location.hash.replace( /[^\w-_]/g, '' );

		if (route) {
			curTab = jq.find('input#' + route);

			if (curTab.length && ! curTab.prop('checked')) {
				curTab.prop('checked', true);
				scrollWindow();
			}
		}
	}

	// Scroll the window to top of the tab list.
	function scrollWindow() {
		resizeArea();
		jQuery('html, body').scrollTop(
			jq.offset().top
			- parseInt( jQuery('html').css('paddingTop') )
			- 20
		);
	}

	// Constructor.
	function init() {
		jq.on('click', '.tab > input[type=radio]', updateHash);
		jQuery(window).on('resize', calcMinHeight);
		jQuery(window).on('hashchange', switchTab);

		calcMinHeight();
		resizeArea();
		switchTab();
	}

	init();

	return WDP;
};

/**
 * Update a normal select list to a fancy WPMU DEV select list!
 *
 * @since  4.0.0
 * @param  object el The select element.
 */
WDP.wpmuSelect = function(el) {
	var jq = jQuery(el),
		wrap, handle, list, value, items;

	if (! jq.is("select")) { return; }
	if (jq.closest(".select-container").length) { return; }

	// Add the DOM elements to style the select list.
	function setupElement() {
		jq.wrap("<div class='select-container'>");
		jq.hide();

		wrap = jq.parent();
		handle = jQuery("<span class='dropdown-handle'><i class='wdv-icon wdv-icon-reorder'></i></span>").prependTo(wrap);
		list = jQuery("<div class='select-list-container'></div>").appendTo(wrap);
		value = jQuery("<div class='list-value'>&nbsp;</div>").appendTo(list);
		items = jQuery("<ul class='list-results'></ul>").appendTo(list);

		wrap.addClass(jq.attr("class"));
	}

	// Add all the options to the new DOM elements.
	function populateList() {
		items.empty();
		jq.find("option").each(function onPopulateLoop() {
			var opt = jQuery(this),
				item;
			item = jQuery("<li></li>").appendTo(items);
			item.text(opt.text());
			item.data("value", opt.val());

			if (opt.val() == jq.val()) {
				selectItem(item);
			}
		});
	}

	// Toggle the dropdown state between open/closed.
	function stateToggle() {
		if (! wrap.hasClass("active")) {
			stateOpen();
		} else {
			stateClose();
		}
	}

	// Close the dropdown list.
	function stateClose(item) {
		if (!item) { item = wrap; }
		item.removeClass("active");
		item.closest("tr").removeClass("select-open");
	}

	// Open the dropdown list.
	function stateOpen() {
		jQuery(".select-container.active").each(function() {
			stateClose(jQuery(this));
		});
		wrap.addClass("active");
		wrap.closest("tr").addClass("select-open");
	}

	// Visually mark the specified option as "selected".
	function selectItem(opt) {
		value.text(opt.text());

		jQuery(".current", items).removeClass("current");
		opt.addClass("current");
		stateClose();

		// Also update the select list value.
		jq.val(opt.data("value"));
		jq.trigger("change");
	}

	// Element constructor.
	function init() {
		var sel_id;

		setupElement();
		populateList();
		items.on("click", function onItemClick(ev) {
			var opt = jQuery(ev.target);
			selectItem(opt);
		});

		handle.on("click", stateToggle);
		value.on("click", stateToggle);
		jq.on("focus", stateOpen);

		jQuery(document).click(function onOutsideClick(ev) {
			var jq = jQuery(ev.target),
				sel_id;

			if (jq.closest(".select-container").length) { return; }
			if (jq.is("label") && jq.attr("for")) {
				sel_id = jq.attr("for");
				if (jQuery("select#" + sel_id).length) { return; }
			}

			stateClose();
		});

		sel_id = jq.attr("id");
		if (sel_id) {
			jQuery("label[for=" + sel_id + "]").on("click", stateOpen);
		}
	}

	init();

	return WDP;
};

/**
 * Initialize the search-areas.
 *
 * @since  4.0.0
 * @param  object el The search input element.
 */
WDP.wpmuSearchfield = function(el) {
	var jq = jQuery(el),
		tmrDelay = 0,
		lastVal = '',
		tmrHide = false,
		hasResults = false,
		search, wrap, inpbox, emptyMsg, emptybox, resbox, reslist, curitem;

	if (! jq.is('input[type="search"]')) { return; }

	// Add the DOM elements to style the select list.
	function setupElement() {
		var classes = jq.attr('class');

		jq.prop('autocomplete', 'off');
		jq.wrap('<div class="search-box">');
		wrap = jq.parent();

		if ( classes ) {
			wrap.addClass(classes);
		}

		jq.wrap('<div class="input-box">');
		inpbox = jq.parent();
		inpbox.append('<i class="search-icon dev-icon dev-icon-search"></i>');

		curitem = jQuery('<div class="current-item"></div>');
		curitem.appendTo(inpbox);

		resbox = jQuery('<div class="search-results"></div>');
		reslist = jQuery('<ul></ul>');
		reslist.appendTo(resbox);
		resbox.appendTo(wrap);

		emptybox = jQuery('<div class="no-results"></div>');
		emptybox.appendTo(wrap);
		emptybox.hide();
	}

	// Start a timer on each keystroke. When the timer runs out a 'search' event
	// is triggered.
	function startDelay() {
		clearDelay();

		// Ignore if value did not change (i.e. cursor keys, shift, return, ...)
		if (lastVal === jq.val()) { return; }
		lastVal = jq.val();

		tmrDelay = window.setTimeout(function() { jq.trigger('search'); }, 400);
	}

	// On key-DOWN we clear the timer.
	function clearDelay() {
		if (tmrDelay) {
			window.clearTimeout(tmrDelay);
			tmrDelay = 0;
		}
	}

	// Toggle the progress state of the search box.
	function doingProgress(state) {
		if (state) {
			wrap.addClass('progress');
		} else {
			wrap.removeClass('progress');
		}
	}

	// Clear/Hide the search-results list.
	function clearResults(clearFilter) {
		reslist.empty();
		curitem.hide();
		jq.show();
		hasResults = false;
		resultsVisible(false);

		if (clearFilter) {
			if (search) {
				jq.val(search);
			} else {
				jq.val('');
			}
		}
	}

	// Populate/Show the search-results list.
	function showResults(items) {
		clearResults(false);

		if (! items || ! items.length) {
			resultsVisible(true);
			return;
		}

		for (var i = 0; i < items.length; i += 1) {
			var li = jQuery('<li></li>'),
				item = items[i];

			if (! item.label) { continue; }
			li.html('<span class="item-label">' + item.label + '</span>');

			if (item.thumb) {
				li.prepend('<span class="thumb" style="background-image:url(' + item.thumb + ')">');
			}
			if (item.id) {
				li.attr('data-id', item.id);
				li.addClass('item item-' + item.id);
			}

			reslist.append(li);
			hasResults = true;
		}
		resultsVisible(true);
	}

	// Toggle visibility of the results.
	function resultsVisible(state) {
		emptybox.hide();

		if (! hasResults) {
			resbox.hide();

			if (jq.val() && state) {
				if (jq.data('no-empty-msg') || wrap.hasClass('progress')) {
					emptybox.hide();
				} else {
					if (!emptyMsg || !emptyMsg.length) {
						if (jq.data('empty-msg')) {
							emptyMsg = jq.data('empty-msg');
						} else {
							emptyMsg = WDP.lang.empty_search;
						}
						emptybox.text(emptyMsg);
					}
					emptybox.show();
				}
			}
			return;
		}

		if (state) {
			if (tmrHide) {
				window.clearTimeout(tmrHide);
				tmrHide = false;
			}

			resbox.show();
		} else {
			tmrHide = window.setTimeout(function() {
				resbox.hide();
				tmrHide = false;
			}, 300 );
		}
	}

	// Visually select a single search item
	function selectItem(item) {
		var item_label, title;

		if (item) {
			title = '';
			item_label = '';

			if (item.find('.thumb').length) {
				title += item.find('.thumb')[0].outerHTML;
			}
			if (item.find('.title').length) {
				item_label = item.find('.title').text();
				title += item.find('.title')[0].outerHTML;
			} else {
				item_label = item.find('.item-label').text();
				title += item.find('.item-label').html();
			}

			curitem.html(title);
			search = jq.val();
			jq.hide();
			curitem.show();
			resbox.hide();

			if (item.data('id')) {
				jq.val(item.data('id'));
			} else {
				jq.val(item_label);
			}
			jq.trigger('item:select');
		} else {
			jq.val(search);
			curitem.hide();
			jq.show();
			window.setTimeout(function(){jq.focus();}, 20);
			jq.trigger('item:clear');
		}
	}

	// Constructor.
	function init() {
		setupElement();
		clearResults(true);

		jq.on('keydown', clearDelay);
		jq.on('keyup', startDelay);

		jq.on('focus', function() { resultsVisible(true) } );
		jq.on('blur', function() { resultsVisible(false) } );

		jq.on('progress:start', function() { doingProgress(true); });
		jq.on('progress:stop', function() { doingProgress(false); });

		jq.on('results:clear', function() { clearResults(true); });
		jq.on('results:show', function(ev, data) { showResults(data); });

		wrap.on('click', '.search-results .item', function() { selectItem(jQuery(this)); });
		curitem.on('click', function() { selectItem(false); });

		wrap.on('click', '.search-icon', function() { jq.trigger('search'); jq.focus(); });
	}

	init();

	return WDP;
};

/**
 * Displays a message in the top of the window.
 *
 * @since  4.0.0
 */
WDP.showMessage = function(action) {
	var me = this;
	initDom();

	// Options can also be passed in as object now :)
	if (action instanceof Object) {
		for (var key in action) {
			if (!action.hasOwnProperty(key)) {continue;}
			WDP.showMessage(key, action[key]);
		}
		return;
	}

	if (WDP.data._msg) {
		me.msg = WDP.data._msg;
	}

	switch (action) {
		case "type":
			switch ( arguments[1] ) {
				case "success":
				case "ok":
				case "green":
					WDP.data._msg = me.msgSuccess;
					break;

				case "error":
				case "err":
				case "red":
					WDP.data._msg = me.msgError;
					break;
			}
			break;

		case "message":
			var text = arguments[1];
			if (!text || !text.length) {
				me.msg.find(".extra-text").html("").hide();
				me.msg.find(".default-text").show();
			} else {
				me.msg.find(".extra-text").html(text).show();
				me.msg.find(".default-text").hide();
			}
			break;

		case "delay":
			var new_delay = parseInt( arguments[1] );
			if (!new_delay) {
				me.delay = 0;
			} else if (isNaN(new_delay) || new_delay < 2000) {
				me.delay = 3000;
			} else {
				me.delay = new_delay;
			}
			break;

		case "icon":
			if (false === arguments[1] || 0 === arguments[1]) {
				me.msg.find(".the-msg-icon").hide();
			} else {
				me.msg.find(".the-msg-icon").show();
			}
			break;

		case "hide":
			if (false === arguments[1] || 0 === arguments[1]) { break; }
			hideMessage();
			break;

		case "show":
		default:
			if (false === arguments[1] || 0 === arguments[1]) { break; }
			showMessage();
			break;
	}

	// Dreate the DOM elements.
	function initDom() {
		if (WDP.data._message_dom_done) { return; }
		WDP.data._message_dom_done = true;

		if (! WDP.lang.default_msg_ok) {
			WDP.lang.default_msg_ok = "Okay, we saved your changes!";
		}
		if (! WDP.lang.default_msg_err) {
			WDP.lang.default_msg_err = "Oops, we could not do this...";
		}

		jQuery("body").append(
			'<div class="update-notice ok" id="wdp-success" style="display:none">' +
			'<span class="the-msg-icon check-animation"></span>' +
			'<p><span class="default-text">' + WDP.lang.default_msg_ok + '</span>' +
			'<span class="extra-text" style="display:none"></span></p>' +
			'<span class="close">&times;</span>' +
			'</div>'
		);

		jQuery("body").append(
			'<div class="update-notice err" id="wdp-error" style="display:none">' +
			'<i class="the-msg-icon wdv-icon wdv-icon-warning-sign"></i>' +
			'<p><span class="default-text">' + WDP.lang.default_msg_err + '</span>' +
			'<span class="extra-text" style="display:none"></span></p>' +
			'<span class="close">&times;</span>' +
			'</div>'
		);

		me.msgSuccess = jQuery("#wdp-success");
		me.msgError = jQuery("#wdp-error");
		me.msg = me.msgSuccess;
		me.delay = 3000;
	}

	// Show current message.
	function showMessage() {
		var tmr = me.msg.data("tmr");

		hideMessage();
		if (tmr) {
			window.setTimeout(WDP.showMessage, 20);
			return;
		}

		me.msg.show();
		me.msg.one("click", ".close", hideMessage);

		// Hide the update notice box after a short time.
		if (me.delay) {
			tmr = window.setTimeout(function() {
				me.msg.fadeOut();
				me.msg.data("tmr", false);
			}, me.delay);
			me.msg.data("tmr", tmr);
			me.msg.find(".close").hide();
		} else {
			me.msg.find(".close").show();
			tmr = false;
			me.msg.data("tmr", false);
		}
	}

	// Hide all messages.
	function hideMessage() {
		var tmr;

		// Success message.
		tmr = me.msgSuccess.data("tmr");
		if (tmr) {
			window.clearTimeout(tmr);
			me.msgSuccess.data("tmr", false);
		}
		me.msgSuccess.hide();

		// Error message.
		tmr = me.msgError.data("tmr");
		if (tmr) {
			window.clearTimeout(tmr);
			me.msgError.data("tmr", false);
		}
		me.msgError.hide();
	}

	return WDP;
};

/**
 * Displays the "Changes saved" message in the top of the window.
 *
 * @since  4.0.0
 */
WDP.showSuccess = function(message) {
	var args = {
		"type": "success",
		"delay": 3000,
		"icon": true,
		"message": false,
		"show": false
	};

	if (message instanceof Object) {
		WDP.showMessage(args);
		message.show = true;
		WDP.showMessage(message);
	} else if ("string" === typeof message) {
		args.message = message;
		WDP.showMessage(args);
	}

	return WDP;
};

/**
 * Displays the "Did not work" message in the top of the window.
 *
 * @since  4.0.0
 */
WDP.showError = function(message) {
	var args = {
		"type": "error",
		"delay": false,
		"icon": true,
		"message": false,
		"show": true
	};

	if (message instanceof Object) {
		WDP.showMessage(args);
		message.show = true;
		WDP.showMessage(message);
	} else if ("string" === typeof message) {
		args.message = message;
		WDP.showMessage(args);
	}

	return WDP;
};

/**
 * Updates the hash-value in the current windows URL without scrolling to the
 * element.
 *
 * @since  4.0.3
 */
WDP.updateHash = function(newHash) {
	newHash = newHash.replace( /^#/, '' );

	var fx,
		node = jQuery( '#' + newHash );

	if (node.length) {
		// Remove the ID value from the actual element.
		node.attr('id', '');

		// Create a dummy element at current position with the specific ID.
		fx = jQuery('<div></div>')
			.css({
				position: 'absolute',
				visibility: 'hidden',
				top: jQuery(document).scrollTop() + 'px'
			})
			.attr('id', newHash)
			.appendTo(document.body);
	}

	// Change hash value in the URL. Browser will scroll to _current position_.
	document.location.hash = newHash;

	// Undo the changes from first part.
	if (node.length) {
		fx.remove();
		node.attr('id', newHash);
	}
};

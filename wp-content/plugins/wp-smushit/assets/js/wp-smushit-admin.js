/**
 * Processes bulk smushing
 *
 * @author Saurabh Shukla <saurabh@incsub.com>
 * @author Umesh Kumar <umeshsingla05@gmail.com>
 *
 */
var WP_Smush = WP_Smush || {};

/**
 * Show/hide the progress bar for Smushing/Restore/SuperSmush
 *
 * @param cur_ele
 * @param txt Message to be displayed
 * @param state show/hide
 */
var progress_bar = function (cur_ele, txt, state) {

    //Update Progress bar text and show it
    var progress_button = cur_ele.parents().eq(1).find('.wp-smush-progress');

    if ('show' == state) {
        progress_button.find('span').html(txt);
        progress_button.removeClass('hidden');
    } else {
        progress_button.find('span').html(wp_smush_msgs.all_done);
        progress_button.hide();
    }
};
jQuery(function ($) {
    var smushAddParams = function (url, data) {
        if (!$.isEmptyObject(data)) {
            url += ( url.indexOf('?') >= 0 ? '&' : '?' ) + $.param(data);
        }

        return url;
    };
    // url for smushing
    WP_Smush.errors = [];
    WP_Smush.timeout = wp_smushit_data.timeout;
    /**
     * Checks for the specified param in URL
     * @param arg
     * @returns {*}
     */
    WP_Smush.geturlparam = function (arg) {
        var $sPageURL = window.location.search.substring(1);
        var $sURLVariables = $sPageURL.split('&');

        for (var i = 0; i < $sURLVariables.length; i++) {
            var $sParameterName = $sURLVariables[i].split('=');
            if ($sParameterName[0] == arg) {
                return $sParameterName[1];
            }
        }
    };

    WP_Smush.Smush = function ($button, bulk, smush_type) {
        var self = this;
        var skip_resmush = $button.data('smush');
        //If smush attribute is not defined, Need not skip resmush ids
        skip_resmush = ( ( typeof skip_resmush == typeof undefined ) || skip_resmush == false ) ? false : true;

        this.init = function () {
            this.$button = $($button[0]);
            this.is_bulk = typeof bulk ? bulk : false;
            this.url = ajaxurl;
            this.$log = $(".smush-final-log");
            this.deferred = jQuery.Deferred();
            this.deferred.errors = [];

            //If button has resmush class, and we do have ids that needs to resmushed, put them in the list
            this.ids = wp_smushit_data.resmush.length > 0 && !skip_resmush ? ( wp_smushit_data.unsmushed.length > 0 ? wp_smushit_data.resmush.concat(wp_smushit_data.unsmushed) :  wp_smushit_data.resmush ): wp_smushit_data.unsmushed;

            this.is_bulk_resmush = wp_smushit_data.resmush.length > 0 && !skip_resmush ? true : false;

            this.$status = this.$button.parent().find('.smush-status');

            //Added for NextGen support
            this.smush_type = typeof smush_type ? smush_type : false;
            this.single_ajax_suffix = this.smush_type ? 'smush_manual_nextgen' : 'wp_smushit_manual';
            this.bulk_ajax_suffix = this.smush_type ? 'wp_smushit_nextgen_bulk' : 'wp_smushit_bulk';
            this.url = this.is_bulk ? smushAddParams(this.url, {action: this.bulk_ajax_suffix}) : smushAddParams(this.url, {action: this.single_ajax_suffix});
        };

        /** Send Ajax request for smushing the image **/
        WP_Smush.ajax = function (is_bulk_resmush, $id, $send_url, $getnxt, nonce) {
            "use strict";
            var param = {
                is_bulk_resmush: is_bulk_resmush,
                attachment_id: $id,
                get_next: $getnxt,
                _nonce: nonce
            };
            param = jQuery.param(param);
            return $.ajax({
                type: "GET",
                data: param,
                url: $send_url,
                timeout: WP_Smush.timeout,
                dataType: 'json'
            });
        };

        //Show loader in button for single and bulk smush
        this.start = function () {

            this.$button.attr('disabled', 'disabled');
            this.$button.addClass('wp-smush-started');

            this.bulk_start();
            this.single_start();
        };

        this.bulk_start = function () {
            if (!this.is_bulk) return;

            //Hide the Bulk Div
            $('.wp-smush-bulk-wrapper').hide();

            //Show the Progress Bar
            $('.bulk-smush-wrapper .wp-smush-bulk-progress-bar-wrapper').show();

            //Remove any Global Notices if there
            $('.wp-smush-notice.wp-smush-resmush-message').remove();
        };

        this.single_start = function () {
            if (this.is_bulk) return;
            this.show_loader();
            this.$status.removeClass("error");
        };

        this.enable_button = function () {
            this.$button.prop("disabled", false);
            //For Bulk process, Enable other buttons
            $('button.wp-smush-all').removeAttr('disabled');
            $('button.wp-smush-scan').removeAttr('disabled');
        };

        this.show_loader = function () {
            progress_bar(this.$button, wp_smush_msgs.smushing, 'show');
        };

        this.hide_loader = function () {
            progress_bar(this.$button, wp_smush_msgs.smushing, 'hide');
        };

        this.single_done = function () {
            if (this.is_bulk) return;

            this.hide_loader();
            this.request.done(function (response) {
                if (typeof response.data != 'undefined') {
                    //Append the smush stats or error
                    self.$status.html(response.data);
                    if (response.success && response.data !== "Not processed") {
                        self.$status.removeClass('hidden');
                        self.$button.parent().removeClass('unsmushed').addClass('smushed');
                        self.$button.remove();
                    } else {
                        self.$status.addClass("error");
                    }
                    self.$status.html(response.data.status);
                    //Check if stats div exists
                    var parent = self.$status.parent();
                    var stats_div = parent.find('.smush-stats-wrapper');
                    if ('undefined' != stats_div && stats_div.length) {
                        stats_div.replaceWith(response.data.stats);
                    } else {
                        parent.append(response.data.stats);
                    }
                }
                self.enable_button();
            }).error(function (response) {
                self.$status.html(response.data);
                self.$status.addClass("error");
                self.enable_button();
            });

        };

        /** After the Bulk Smushing has been Finished **/
        this.bulk_done = function () {
            if (!this.is_bulk) return;

            //Enable the button
            this.enable_button();

            //Show Notice
            if (self.ids.length == 0) {
                $('.bulk-smush-wrapper .wp-smush-all-done').show();
                $('.wp-smush-bulk-wrapper').hide();
            } else {
                if ($('.bulk-smush-wrapper .wp-smush-resmush-notice').length > 0) {
                    $('.bulk-smush-wrapper .wp-smush-resmush-notice').show();
                } else {
                    $('.bulk-smush-wrapper .wp-smush-remaining').show();
                }
                $('.wp-smush-bulk-wrapper').show();
            }

            //Hide the Progress Bar
            $('.wp-smush-bulk-progress-bar-wrapper').hide();

            //Enable Resmush and scan button
            $('.wp-resmush.wp-smush-action, .wp-smush-scan').removeAttr('disabled');
        };

        this.is_resolved = function () {
            "use strict";
            return this.deferred.state() === "resolved";
        };

        this.free_exceeded = function () {
            //Hide the Progress bar and show the Bulk smush wrapper
            $('.wp-smush-bulk-progress-bar-wrapper').hide();

            if (self.ids.length > 0) {
                //Show Bulk wrapper
                $('.wp-smush-bulk-wrapper ').show();
            } else {
                $('.wp-smush-notice.wp-smush-all-done').show();
            }
        };

        this.update_remaining_count = function () {
            if (this.is_bulk_resmush) {
                //ReSmush Notice
                if ($('.wp-smush-resmush-notice .wp-smush-remaining-count').length && 'undefined' != typeof self.ids) {
                    $('.wp-smush-resmush-notice .wp-smush-remaining-count').html(self.ids.length);
                }
            } else {
                //Smush Notice
                if ($('.bulk-smush-wrapper .wp-smush-remaining-count').length && 'undefined' != typeof self.ids) {
                    $('.bulk-smush-wrapper .wp-smush-remaining-count').html(self.ids.length);
                }
            }
        }

        this.update_progress = function (_res) {
            //If not bulk
            if (!this.is_bulk_resmush && !this.is_bulk) {
                return;
            }

            var progress = '';

            if (!this.is_bulk_resmush) {
                if (_res && ( 'undefined' == typeof _res.data || 'undefined' == typeof _res.data.stats )) {
                    return;
                }
                //handle progress for normal bulk smush
                progress = ( _res.data.stats.smushed / _res.data.stats.total) * 100;
            } else {
                //If the Request was successful, Update the progress bar
                if (_res.success) {
                    //Handle progress for Super smush progress bar
                    if (wp_smushit_data.resmush.length > 0) {
                        //Update the Count
                        $('.wp-smush-images-remaining').html(wp_smushit_data.resmush.length);
                    } else if (wp_smushit_data.resmush.length == 0 && this.ids.length == 0 ) {
                        //If all images are resmushed, show the All Smushed message

                        //Show All Smushed
                        $('.bulk-resmush-wrapper .wp-smush-all-done').removeClass('hidden');

                        //Hide Everything else
                        $('.wp-smush-resmush-wrap, .wp-smush-bulk-progress-bar-wrapper').hide();
                    }
                }

                //handle progress for normal bulk smush
                //Set Progress Bar width
                if ('undefined' !== typeof self.ids && 'undefined' !== typeof wp_smushit_data.count_total && wp_smushit_data.count_total > 0) {
                    progress = ( ( wp_smushit_data.count_total - self.ids.length ) / wp_smushit_data.count_total ) * 100;
                }
            }

            //Show Bulk Wrapper and Smush Notice
            if (self.ids.length == 0) {
                //Hide the bulk wrapper
                $('.wp-smush-bulk-wrapper').hide();
                //Show All done notice
                $('.wp-smush-notice.wp-smush-all-done').show();
            }

            //Update Total Images Tooltip
            if( 'undefined' !== typeof _res.data.stats.tooltip_text && '' != _res.data.stats.tooltip_text ) {
                $('.wp-smush-stats .smushed-count').attr('tooltip', _res.data.stats.tooltip_text );
            }

            //Update remaining count
            self.update_remaining_count();

            //if we have received the progress data, update the stats else skip
            if ('undefined' != typeof _res.data.stats) {

                //Update stats
                $('.smush-total-reduction-percent .wp-smush-stats').html(_res.data.stats.percent);
                $('.smush-total-reduction-bytes .wp-smush-stats').html(_res.data.stats.human);

                $('.smush-attachments .wp-smush-stats .smushed-count, .wp-smush-images-smushed').html(_res.data.stats.smushed);
                if ($('.super-smush-attachments .smushed-count').length && 'undefined' != typeof _res.data.stats.super_smushed) {
                    $('.super-smush-attachments .smushed-count').html(_res.data.stats.super_smushed);
                }

                // increase the progress bar
                this._update_progress(_res.data.stats.smushed, progress);
            }
        };

        this._update_progress = function (count, width) {
            "use strict";
            if (!this.is_bulk && !this.is_bulk_resmush) {
                return;
            }
            //Update the Progress Bar Width
            // get the progress bar
            var $progress_bar = jQuery('.bulk-smush-wrapper .wp-smush-progress-inner');
            if ($progress_bar.length < 1) {
                return;
            }
            // increase progress
            $progress_bar.css('width', width + '%');

        };

        //Whether to send the ajax requests further or not
        this.continue = function () {
            var continue_smush = self.$button.attr('continue_smush');

            if (typeof continue_smush == typeof undefined) {
                continue_smush = true;
            }

            if ('false' == continue_smush || !continue_smush) {
                continue_smush = false;
            }

            return continue_smush && this.ids.length > 0 && this.is_bulk;
        };

        this.increment_errors = function (id) {
            WP_Smush.errors.push(id);
        };

        //Send ajax request for smushing single and bulk, call update_progress on ajax response
        this.call_ajax = function () {
            var nonce_value = '';
            this.current_id = this.is_bulk ? this.ids.shift() : this.$button.data("id"); //remove from array while processing so we can continue where left off

            //Remove the id from respective variable as well
            this.update_smush_ids( this.current_id );

            var nonce_field = this.$button.parent().find('#_wp_smush_nonce');
            if (nonce_field) {
                nonce_value = nonce_field.val();
            }

            this.request = WP_Smush.ajax(this.is_bulk_resmush, this.current_id, this.url, 0, nonce_value)
                .error(function () {
                    self.increment_errors(self.current_id);
                }).done(function (res) {
                    //Increase the error count if any
                    if (typeof res.success === "undefined" || ( typeof res.success !== "undefined" && res.success === false && res.data.error !== 'bulk_request_image_limit_exceeded' )) {
                        self.increment_errors(self.current_id);
                    }
                    //If no response or success is false, do not process further
                    if (typeof res == 'undefined' || !res || !res.success) {
                        if ('undefined' !== typeof res && 'undefined' !== typeof res.data && typeof res.data.error_msg !== 'undefined') {
                            //Print the error on screen
                            self.$log.append(res.data.error_msg);
                            self.$log.removeClass('hidden');
                        }
                    }

                    if (typeof res.data !== "undefined" && res.data.error == 'bulk_request_image_limit_exceeded' && !self.is_resolved()) {
                        //Add a data attribute to the smush button, to stop sending ajax
                        self.$button.attr('continue_smush', false);

                        self.free_exceeded();

                        //Reinsert the current id
                        wp_smushit_data.unsmushed.push(self.current_id);

                        //Update the remaining count to length of remaining ids + 1 (Current id)
                        self.update_remaining_count();
                    } else {

                        if (self.is_bulk && res.success) {
                            self.update_progress(res);
                        }
                    }
                    self.single_done();
                }).complete(function () {
                    if (!self.continue() || !self.is_bulk) {
                        //Calls deferred.done()
                        self.deferred.resolve();
                    } else {
                        self.call_ajax();
                    }
                });

            self.deferred.errors = WP_Smush.errors;
            return self.deferred;
        };

        this.init(arguments);

        //Send ajax request for single and bulk smushing
        this.run = function () {

            // if we have a definite number of ids
            if (this.is_bulk && this.ids.length > 0) {
                this.call_ajax();
            }

            if (!this.is_bulk)
                this.call_ajax();

        };

        //Show bulk smush errors, and disable bulk smush button on completion
        this.bind_deferred_events = function () {

            this.deferred.done(function () {

                self.$button.removeAttr('continue_smush');

                if (WP_Smush.errors.length) {
                    var error_message = '<div class="wp-smush-ajax-error">' + wp_smush_msgs.error_in_bulk.replace("{{errors}}", WP_Smush.errors.length) + '</div>';
                    //Remove any existing notice
                    $('.wp-smush-ajax-error').remove();
                    self.$log.prepend(error_message);
                }

                self.bulk_done();

                //Re enable the buttons
                $('.wp-smush-button:not(.wp-smush-finished), .wp-smush-scan').removeAttr('disabled');
            });

        };
        /** Handles the Cancel button Click
         *
         * Update the UI, and enables the bulk smush button
         *
         **/
        this.cancel_ajax = function () {
            $('.wp-smush-cancel-bulk').on('click', function () {
                //Add a data attribute to the smush button, to stop sending ajax
                self.$button.attr('continue_smush', false);

                self.request.abort();
                self.enable_button();
                self.$button.removeClass('wp-smush-started');
                $('.wp-smush-bulk-wrapper').show();

                //Hide the Progress Bar
                $('.wp-smush-bulk-progress-bar-wrapper').hide();
            });
        };
        /**
         * Remove the current id from unsmushed/resmush variable
         * @param current_id
         */
        this.update_smush_ids = function( current_id ) {
            if ('undefined' !== typeof wp_smushit_data.unsmushed && wp_smushit_data.unsmushed.length > 0) {
                var u_index = wp_smushit_data.unsmushed.indexOf(current_id);
                if (u_index > -1) {
                    wp_smushit_data.unsmushed.splice(u_index, 1);
                }
            }
            //remove from the resmush list
            if ('undefined' !== typeof wp_smushit_data.resmush && wp_smushit_data.resmush.length > 0) {
                var index = wp_smushit_data.resmush.indexOf(current_id);
                if (index > -1) {
                    wp_smushit_data.resmush.splice(index, 1);
                }
            }
        }

        this.start();
        this.run();
        this.bind_deferred_events();

        //Handle Cancel Ajax
        this.cancel_ajax();

        return this.deferred;
    };

    /**
     * Handle the Bulk Smush/ Bulk Resmush button click
     */
    $('body').on('click', 'button.wp-smush-all', function (e) {

        // prevent the default action
        e.preventDefault();

        $('.wp-smush-notice.wp-smush-settings-updated').remove();

        //Disable Resmush and scan button
        $('.wp-resmush.wp-smush-action, .wp-smush-scan, .wp-smush-button').attr('disabled', 'disabled');

        //Check for ids, if there is none (Unsmushed or lossless), don't call smush function
        if (typeof wp_smushit_data == 'undefined' ||
            ( wp_smushit_data.unsmushed.length == 0 && wp_smushit_data.resmush.length == 0 )
        ) {

            return false;

        }

        $(".wp-smush-remaining").hide();

        new WP_Smush.Smush($(this), true);


    });

    /** Disable the action links **/
    var disable_links = function (c_element) {

        var parent = c_element.parent();
        //reduce parent opacity
        parent.css({'opacity': '0.5'});
        //Disable Links
        parent.find('a').attr('disabled', 'disabled');
    };

    /** Enable the Action Links **/
    var enable_links = function (c_element) {

        var parent = c_element.parent();

        //reduce parent opacity
        parent.css({'opacity': '1'});
        //Disable Links
        parent.find('a').removeAttr('disabled');
    };
    /**
     * Restore image request with a specified action for Media Library / NextGen Gallery
     * @param e
     * @param current_button
     * @param smush_action
     * @returns {boolean}
     */
    var process_smush_action = function (e, current_button, smush_action) {

        //If disabled
        if ('disabled' == current_button.attr('disabled')) {
            return false;
        }

        e.preventDefault();

        //Remove Error
        $('.wp-smush-error').remove();

        //Hide stats
        $('.smush-stats-wrapper').hide();

        //Get the image ID and nonce
        var params = {
            action: smush_action,
            attachment_id: current_button.data('id'),
            _nonce: current_button.data('nonce')
        };

        //Reduce the opacity of stats and disable the click
        disable_links(current_button);

        progress_bar(current_button, wp_smush_msgs.smushing, 'show');

        //Restore the image
        $.post(ajaxurl, params, function (r) {

            progress_bar(current_button, wp_smush_msgs.smushing, 'hide');

            //reset all functionality
            enable_links(current_button);

            if (r.success && 'undefined' != typeof( r.data.button )) {
                //Show the smush button, and remove stats and restore option
                current_button.parents().eq(1).html(r.data.button);
            } else {
                if (r.data.message) {
                    //show error
                    current_button.parent().append(r.data.message);
                }
            }
        })
    };

    /**
     * Validates the Resize Width and Height against the Largest Thumbnail Width and Height
     *
     * @param wrapper_div jQuery object for the whole setting row wrapper div
     * @param width_only Whether to validate only width
     * @param height_only Validate only Height
     * @returns {boolean} All Good or not
     *
     */
    var validate_resize_settings = function (wrapper_div, width_only, height_only) {
        var resize_checkbox = wrapper_div.find('#wp-smush-resize');
        if (!height_only) {
            var width_input = wrapper_div.find('#wp-smush-resize_width');
            var width_error_note = wrapper_div.find('.wp-smush-size-info.wp-smush-update-width');
        }
        if (!width_only) {
            var height_input = wrapper_div.find('#wp-smush-resize_height');
            var height_error_note = wrapper_div.find('.wp-smush-size-info.wp-smush-update-height');
        }

        var width_error = false;
        var height_error = false

        //If resize settings is not enabled, return true
        if (!resize_checkbox.is(':checked')) {
            return true;
        }

        //Check if we have localised width and height
        if ('undefined' == typeof (wp_smushit_data.resize_sizes) || 'undefined' == typeof (wp_smushit_data.resize_sizes.width)) {
            //Rely on server validation
            return true;
        }

        //Check for width
        if ( !height_only && 'undefined' != typeof width_input && parseInt(wp_smushit_data.resize_sizes.width) > parseInt(width_input.val())) {
            width_input.addClass('error');
            width_error_note.show('slow');
            width_error = true;
        } else {
            //Remove error class
            width_input.removeClass('error');
            width_error_note.hide();
            if (height_input.hasClass('error')) {
                height_error_note.show('slow');
            }
        }

        //Check for height
        if (!width_only && 'undefined' != typeof height_input && parseInt(wp_smushit_data.resize_sizes.height) > parseInt(height_input.val())) {
            height_input.addClass('error');
            //If we are not showing the width error already
            if (!width_error) {
                height_error_note.show('slow');
            }
            height_error = true;
        } else {
            //Remove error class
            height_input.removeClass('error');
            height_error_note.hide();
            if (width_input.hasClass('error')) {
                width_error_note.show('slow');
            }
        }

        if (width_error || height_error) {
            return false;
        }
        return true;

    };

    /**
     * Update the progress bar width if we have images that needs to be resmushed
     * @param unsmushed_count
     * @returns {boolean}
     */
    var update_progress_bar_resmush = function (unsmushed_count) {

        if ('undefined' == typeof unsmushed_count) {
            return false;
        }

        var smushed_count = wp_smushit_data.count_total - unsmushed_count;

        //Update the Progress Bar Width
        // get the progress bar
        var $progress_bar = jQuery('.bulk-smush-wrapper .wp-smush-progress-inner');
        if ($progress_bar.length < 1) {
            return;
        }

        var width = ( smushed_count / wp_smushit_data.count_total ) * 100;

        // increase progress
        $progress_bar.css('width', width + '%');
    };

    /**
     * Handle the Smush Stats link click
     */
    $('body').on('click', 'a.smush-stats-details', function (e) {

        //If disabled
        if ('disabled' == $(this).attr('disabled')) {
            return false;
        }

        // prevent the default action
        e.preventDefault();
        //Replace the `+` with a `-`
        var slide_symbol = $(this).find('.stats-toggle');
        $(this).parents().eq(1).find('.smush-stats-wrapper').slideToggle();
        slide_symbol.text(slide_symbol.text() == '+' ? '-' : '+');


    });

    /** Handle smush button click **/
    $('body').on('click', '.wp-smush-send:not(.wp-smush-resmush)', function (e) {

        // prevent the default action
        e.preventDefault();
        new WP_Smush.Smush($(this), false);
    });

    /** Handle NextGen Gallery smush button click **/
    $('body').on('click', '.wp-smush-nextgen-send', function (e) {

        // prevent the default action
        e.preventDefault();
        new WP_Smush.Smush($(this), false, 'nextgen');
    });

    /** Handle NextGen Gallery Bulk smush button click **/
    $('body').on('click', '.wp-smush-nextgen-bulk', function (e) {

        // prevent the default action
        e.preventDefault();

        //Check for ids, if there is none (Unsmushed or lossless), don't call smush function
        if (typeof wp_smushit_data == 'undefined' ||
            ( wp_smushit_data.unsmushed.length == 0 && wp_smushit_data.resmush.length == 0 )
        ) {

            return false;

        }

        jQuery('.wp-smush-button, .wp-smush-scan').attr('disabled', 'disabled');
        $(".wp-smush-notice.wp-smush-remaining").hide();
        new WP_Smush.Smush($(this), true, 'nextgen');

    });

    /** Restore: Media Library **/
    $('body').on('click', '.wp-smush-action.wp-smush-restore', function (e) {
        var current_button = $(this);
        var smush_action = 'smush_restore_image';
        process_smush_action(e, current_button, smush_action);
    });

    /** Resmush: Media Library **/
    $('body').on('click', '.wp-smush-action.wp-smush-resmush', function (e) {
        var current_button = $(this);
        var smush_action = 'smush_resmush_image';
        process_smush_action(e, current_button, smush_action);
    });

    /** Restore: NextGen Gallery **/
    $('body').on('click', '.wp-smush-action.wp-smush-nextgen-restore', function (e) {
        var current_button = $(this);
        var smush_action = 'smush_restore_nextgen_image';
        process_smush_action(e, current_button, smush_action);
    });

    /** Resmush: NextGen Gallery **/
    $('body').on('click', '.wp-smush-action.wp-smush-nextgen-resmush', function (e) {
        var current_button = $(this);
        var smush_action = 'smush_resmush_nextgen_image';
        process_smush_action(e, current_button, smush_action);
    });

    //Scan For resmushing images
    $('body').on('click', '.wp-smush-scan', function (e) {

        e.preventDefault();

        var button = jQuery(this);
        var spinner = button.parent().find('.spinner');

        //Check if type is set in data attributes
        var scan_type = button.data('type');
        scan_type = 'undefined' == typeof scan_type ? 'media' : scan_type;

        //Show spinner
        spinner.addClass('is-active');

        //Remove the Skip resmush attribute from button
        $('button.wp-smush-all').removeAttr('data-smush');

        //remove notices
        var el = $('.wp-smush-notice.wp-smush-resmush-message, .wp-smush-notice.wp-smush-settings-updated');
        el.slideUp(100, function () {
            el.remove();
        });

        //Disable Bulk smush button and itself
        button.attr('disabled', 'disabled');
        $('.wp-smush-button').attr('disabled', 'disabled');

        //Hide Settings changed Notice
        $('.wp-smush-settings-changed').hide();

        //Show Loading Animation
        jQuery('.bulk-resmush-wrapper .wp-smush-progress-bar-wrap').removeClass('hidden');

        //Ajax Params
        var params = {
            action: 'scan_for_resmush',
            type: scan_type,
            get_ui: true,
            wp_smush_options_nonce: jQuery('#wp_smush_options_nonce').val()
        };

        //Send ajax request and get ids if any
        $.get(ajaxurl, params, function (r) {
            //Check if we have the ids,  initialize the local variable
            if ('undefined' != typeof r.data) {
                //Update Resmush id list
                if ('undefined' != typeof r.data.resmush_ids) {
                    wp_smushit_data.resmush = r.data.resmush_ids;

                    //Get the Smushed image count
                    var smushed_count = wp_smushit_data.count_smushed - r.data.resmush_ids.length;

                    //Update it in stats bar
                    $('.smush-attachments .wp-smush-stats .smushed-count, .wp-smush-images-smushed').html(smushed_count);

                    //Hide the Existing wrapper
                    var notices = $('.bulk-smush-wrapper .wp-smush-notice');
                    if (notices.length > 0) {
                        notices.hide();
                    }
                    //remove existing Re-Smush notices
                    $('.wp-smush-resmush-notice').remove();

                    //Show Bulk wrapper
                    $('.wp-smush-bulk-wrapper').show();

                    if( 'undefined' !== typeof r.data.count ) {
                        //Update progress bar
                        update_progress_bar_resmush( r.data.count );
                    }
                }
                //If content is received, Prepend it
                if ('undefined' != typeof r.data.content) {
                    $('.bulk-smush-wrapper .box-container').prepend(r.data.content);
                }
                //If we have any notice to show
                if ('undefined' != typeof r.data.notice) {
                    $('.wp-smush-page-header').after(r.data.notice);
                }
                //Hide errors
                $('.smush-final-log').hide();

                //Hide Super Smush notice if it's enabled in media settings
                if ('undefined' != typeof r.data.super_smush && r.data.super_smush ) {
                    var enable_lossy = jQuery('.wp-smush-enable-lossy');
                    if( enable_lossy.length > 0 ) {
                        enable_lossy.remove();
                    }
                    if( 'undefined' !== r.data.super_smush_stats ) {
                        $('.super-smush-attachments .wp-smush-stats').html( r.data.super_smush_stats );
                    }
                }
            }

        }).always(function () {

            //Hide the progress bar
            jQuery('.bulk-smush-wrapper .wp-smush-bulk-progress-bar-wrapper').hide();

            //Enable the Bulk Smush Button and itself
            button.removeAttr('disabled');

            //Hide Spinner
            spinner.removeClass('is-active');
            $('.wp-smush-button').removeAttr('disabled');
        });

    });

    //Dismiss Welcome notice
    $('#wp-smush-welcome-box .smush-dismiss-welcome').on('click', function (e) {
        e.preventDefault();
        var $el = $(this).parents().eq(1);
        $el.fadeTo(100, 0, function () {
            $el.slideUp(100, function () {
                $el.remove();
            });
        });
        //Send a ajax request to save the dismissed notice option
        var param = {
            action: 'dismiss_welcome_notice'
        };
        $.post(ajaxurl, param);
    });

    //Remove Notice
    $('body').on('click', '.wp-smush-notice .dev-icon-cross', function (e) {
        e.preventDefault();
        var $el = $(this).parent();
        $el.fadeTo(100, 0, function () {
            $el.slideUp(100, function () {
                $el.remove();
            });
        });
    });

    //On Click Update Settings. Check for change in settings
    $('#wp-smush-save-settings').on('click', function (e) {
        e.preventDefault();

        var self = $(this);
        var wrapper_div = self.parents().eq(1);

        //Get all the main settings
        var keep_exif = document.getElementById("wp-smush-keep_exif");
        var super_smush = document.getElementById("wp-smush-lossy");
        var smush_original = document.getElementById("wp-smush-original");

        var update_button_txt = true;

        //If Preserve Exif is Checked, and all other settings are off, just save the settings
        if (keep_exif.checked && !super_smush.checked && !smush_original.checked) {
            update_button_txt = false;
        }

        //Update text
        self.attr('disabled', 'disabled').addClass('button-grey');

        if (update_button_txt) {
            self.val(wp_smush_msgs.checking)
        }

        self.parent().find('.spinner').addClass('is-active');

        //Check if type is set in data attributes
        var scan_type = self.data('type');
        scan_type = 'undefined' == typeof scan_type ? 'media' : scan_type;

        //Ajax param
        var param = {
            action: 'scan_for_resmush',
            scan_type: scan_type
        };

        param = jQuery.param(param) + '&' + jQuery('form#wp-smush-settings-form').serialize();

        //Send ajax, Update Settings, And Check For resmush
        jQuery.post(ajaxurl, param).done(function () {
            jQuery('form#wp-smush-settings-form').submit();
            return true;
        });
    });

    //On Resmush click
    $('body').on('click', '.wp-smush-skip-resmush', function (e) {
        e.preventDefault();
        var self = jQuery(this);
        var container = self.parents().eq(1);

        //Remove Parent div
        var $el = self.parent();
        $el.fadeTo(100, 0, function () {
            $el.slideUp(100, function () {
                $el.remove();
            });
        });

        //Remove Settings Notice
        $('.wp-smush-notice.wp-smush-settings-updated').remove();

        //Set button attribute to skip re-smush ids
        container.find('.wp-smush-all').attr('data-smush', 'skip_resmush');

        //Update Stats
        if (wp_smushit_data.count_smushed == wp_smushit_data.count_total) {

            //Show all done notice
            $('.wp-smush-notice.wp-smush-all-done').show();

            //Hide Smush button
            $('.wp-smush-bulk-wrapper ').hide()

        }
        //Remove Re-Smush Notice
        $('.wp-smush-resmush-notice').remove();

        var type = $('.wp-smush-scan').data('type');
        type = 'undefined' == typeof type ? 'media' : type;

        var smushed_count = 'undefined' != typeof wp_smushit_data.count_smushed ? wp_smushit_data.count_smushed : 0
        $('.smush-attachments .wp-smush-stats .smushed-count, .wp-smush-images-smushed').html(smushed_count);

        //Update the Progress Bar Width
        // get the progress bar
        var $progress_bar = jQuery('.bulk-smush-wrapper .wp-smush-progress-inner');
        if ($progress_bar.length < 1) {
            return;
        }

        var width = ( smushed_count / wp_smushit_data.count_total ) * 100;

        // increase progress
        $progress_bar.css('width', width + '%');

        //Show the default bulk smush notice
        $('.wp-smush-bulk-wrapper .wp-smush-notice').show();

        var params = {
            action: 'delete_resmush_list',
            type: type
        }
        //Delete resmush list
        $.post(ajaxurl, params);

    });

    //Enable Super Smush
    $('.wp-smush-lossy-enable').on('click', function (e) {
        e.preventDefault();

        //Enable Super Smush
        $('#wp-smush-lossy').prop('checked', true);
        //Induce Setting button save click
        $('#wp-smush-save-settings').click();
    });

    //Trigger Bulk
    $('body').on('click', '.wp-smush-trigger-bulk', function (e) {
        e.preventDefault();
        //Induce Setting button save click
        $('button.wp-smush-all').click();

    });

    //Allow the checkboxes to be Keyboard Accessible
    $('.wp-smush-setting-row .toggle-checkbox').focus(function () {
        //If Space is pressed
        $(this).keypress(function (e) {
            if (e.keyCode == 32) {
                e.preventDefault();
                $(this).find('.toggle-checkbox').click();
            }
        });
    });

    //Re-Validate Resize Width And Height
    $('.wp-smush-resize-input').blur(function () {

        var self = $(this);

        var wrapper_div = self.parents().eq(2);

        //Initiate the check
        validate_resize_settings(wrapper_div, false, false); // run the validation

    });

    //Handle Resize Checkbox toggle, to show/hide width, height settings
    $('#wp-smush-resize').click(function() {
        var self = $(this);
        var settings_wrap = $('.wp-smush-resize-settings-wrap');

        if (self.is(':checked')) {
            settings_wrap.show();
        } else {
            settings_wrap.hide();
        }
    });


});
(function ($) {
    var Smush = function (element, options) {
        var elem = $(element);

        var defaults = {
            isSingle: false,
            ajaxurl: '',
            msgs: {},
            msgClass: 'wp-smush-msg',
            ids: []
        };
    };
    $.fn.wpsmush = function (options) {
        return this.each(function () {
            var element = $(this);

            // Return early if this element already has a plugin instance
            if (element.data('wpsmush'))
                return;

            // pass options to plugin constructor and create a new instance
            var wpsmush = new Smush(this, options);

            // Store plugin object in this element's data
            element.data('wpsmush', wpsmush);
        });
    };

})(jQuery);

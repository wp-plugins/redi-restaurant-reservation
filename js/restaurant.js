jQuery(function () {
    jQuery('.disabled').on('click', function (e) {
        e.preventDefault();
    });

    function hideSteps() {
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        if (hidesteps) {
            jQuery('#step1busy').hide();
        }
    }

    var updateTime = function () {
        if (timepicker == 'dropdown') {
            jQuery('#redi-restaurant-startTime-alt').val(jQuery('#redi-restaurant-startHour').val() + ':' + jQuery('#redi-restaurant-startMinute').val());// update time in hidden field
        }
        hideSteps();
    };

    if (timepicker == 'dropdown') {
        jQuery('#redi-restaurant-startTime-alt').val(jQuery('#redi-restaurant-startHour').val() + ':' + jQuery('#redi-restaurant-startMinute').val());// update time in hidden field
    }

    jQuery('#redi-restaurant-startHour').change(updateTime);
    jQuery('#redi-restaurant-startMinute').change(updateTime);
    jQuery('#persons').change(function () {
        if (jQuery(this).val() === 'group') {
            jQuery('#step1button').attr('disabled', true);
            jQuery('#large_groups_message').show('slow');
            jQuery('#step1buttons').hide('slow');
            if (!hidesteps) {
                jQuery('#step2').hide();
            }
        }
        else {
            jQuery('#step1button').attr('disabled', false);
            jQuery('#large_groups_message').hide('slow');
            var day1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getDate();
            var month1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
            var year1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
            var fullDate = year1 + '-' + month1 + '-' + day1
            if (timeshiftmode === 'byshifts') {
                step1call(fullDate)
            }
            else {
                hideSteps();
                jQuery('#redi-restaurant-startDateISO').val(fullDate);
            }
        }

    });

    if (jQuery.timepicker.regional[datepicker_locale] !== undefined) {
        jQuery.timepicker.setDefaults(jQuery.timepicker.regional[datepicker_locale]);
    }
    else {
        jQuery.timepicker.setDefaults(jQuery.timepicker.regional['']);
    }

    if (jQuery.datepicker.regional[datepicker_locale] !== undefined) {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[datepicker_locale.substring(0, 2)]);
    }
    else {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional['']);
    }

    jQuery('#redi-restaurant-startTime').timepicker({
        stepMinute: 15,
        timeFormat: timepicker_time_format,
        onClose: function () {
            hideSteps();
        },
        altField: '#redi-restaurant-startTime-alt',
        altFieldTimeOnly: false,
        altTimeFormat: 'HH:mm'
    });

    jQuery('#redi-restaurant-startDate').change(function () {
        var day1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getDate();
        var month1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
        var year1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
        var fullDate = year1 + '-' + month1 + '-' + day1;

        jQuery('#redi-restaurant-startDateISO').val(fullDate);
    });

    jQuery('#redi-restaurant-startDate').datepicker({
        beforeShowDay: function (date) {
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            if (jQuery.inArray(string, disabled_dates) == -1) {
                return [true, '', ''];
            } else {
                return [false, '', ''];
            }
        },
        dateFormat: date_format,
        minDate: new Date(),
        onSelect: function () {
            var startDate = jQuery('#redi-restaurant-startDate');
            var day1 = startDate.datepicker('getDate').getDate();
            var month1 = startDate.datepicker('getDate').getMonth() + 1;
            var year1 = startDate.datepicker('getDate').getFullYear();
            var fullDate = year1 + '-' + month1 + '-' + day1;
            if (timeshiftmode === 'byshifts') {
                step1call(fullDate)
            }
            else {
                hideSteps();
                jQuery('#redi-restaurant-startDateISO').val(fullDate);
            }
        }
    });
    jQuery(document).on('click', '.redi-restaurant-time-button', function () {
        jQuery('.redi-restaurant-time-button').each(function () {
            jQuery(this).removeAttr('select');
        });

        jQuery(this).attr('select', 'select');

        jQuery('#redi-restaurant-startTimeHidden').val(jQuery(this).val());

        if (hidesteps) {
            jQuery('#step2').hide();
            jQuery('#step3').show();
        } else {
            jQuery('#step3').show('slow');
        }
        jQuery('#UserName').focus();
        return false;
    });

    jQuery(document).on('click', '#redi-restaurant-step3', function () {
        var error = '';
        if (jQuery('#UserName').val() === '') {
            error += redi_restaurant_reservation.name_missing + '<br/>';
        }
        if (jQuery('#UserEmail').val() === '') {
            error += redi_restaurant_reservation.email_missing + '<br/>';
        }
        if (jQuery('#UserPhone').val() === '') {
            error += redi_restaurant_reservation.phone_missing + '<br/>';
        }
        jQuery('.field_required').each(function () {
            if (jQuery(this).attr('type') === 'checkbox' && !jQuery(this).is(':checked') || jQuery(this).attr('type') === 'textbox' && jQuery(this).val() === '') {
                error += jQuery('#' + this.id + '_message').attr('value') + '<br/>';
            }
        });
        if (error) {
            jQuery('#step3errors').html(error).show('slow');
            return false;
        }
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step3',
            startDate: jQuery('#redi-restaurant-startDate').val(),
            startTime: jQuery('#redi-restaurant-startTimeHidden').val(),
            persons: jQuery('#persons').val(),
            UserName: jQuery('#UserName').val(),
            UserEmail: jQuery('#UserEmail').val(),
            UserComments: jQuery('#UserComments').val(),
            UserPhone: jQuery('#UserPhone').val(),
            placeID: jQuery('#placeID').val(),
            lang: locale,
            apikeyid: apikeyid
        };
        var field_1 = jQuery('#field_1');
        if (field_1.attr('type') === 'checkbox' && field_1.attr('checked') === 'checked') {
            data['field_1'] = 'on';
        } else {
            data['field_1'] = field_1.val();
        }
        var field_2 = jQuery('#field_2');
        if (field_2.attr('type') === 'checkbox' && field_2.attr('checked') === 'checked') {
            data['field_2'] = 'on';
        } else {
            data['field_2'] = field_2.val();
        }
        var field_3 = jQuery('#field_3');
        if (field_3.attr('type') === 'checkbox' && field_3.attr('checked') === 'checked') {
            data['field_3'] = 'on';
        } else {
            data['field_3'] = field_3.val();
        }
        var field_4 = jQuery('#field_4');
        if (field_4.attr('type') === 'checkbox' && field_4.attr('checked') === 'checked') {
            data['field_4'] = 'on';
        } else {
            data['field_4'] = field_4.val();
        }
        var field_5 = jQuery('#field_5');
        if (field_5.attr('type') === 'checkbox' && field_5.attr('checked') === 'checked') {
            data['field_5'] = 'on';
        } else {
            data['field_5'] = field_5.val();
        }

        jQuery('#step3load').show();
        jQuery('#step3errors').hide('slow');
        jQuery('#redi-restaurant-step3').attr('disabled', true);
        jQuery.post(redi_restaurant_reservation.ajaxurl, data, function (response) {
            jQuery('#redi-restaurant-step3').attr('disabled', false);
            jQuery('#step3load').hide();
            if (response['Error']) {
                jQuery('#step3errors').html(response['Error']).show('slow');
            } else {
                ga_event('Reservation confirmed', '');
                jQuery('#step1').hide('slow');
                jQuery('#step2').hide('slow');
                jQuery('#step3').hide('slow');
                jQuery('#step4').show('slow'); //success message
                jQuery('html, body').animate({scrollTop: 0}, 'slow');
            }
        }, 'json');
        return false;
    });
    jQuery(document).on('click', '#step1button', function () {
        if (timeshiftmode === 'byshifts') {
            step1call();
        }
        else {
            jQuery('#step1button').attr('disabled', true);
            var start_date = jQuery('#redi-restaurant-startDate').datepicker('getDate');
            var day1 = start_date.getDate();
            var month1 = start_date.getMonth() + 1;
            var year1 = start_date.getFullYear();
            var fullDate = year1 + '-' + month1 + '-' + day1;
            step1call(fullDate);
        }
        return false;
    });

    jQuery('#placeID').change(function () {
        if (hidesteps) {
            jQuery('#step1buttons').hide('slow');
        }
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        jQuery('#step1errors').hide('slow');
    });

    function step1call(fullDate) {
        hideSteps();

        jQuery('#redi-restaurant-startDateISO').val(fullDate);
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        jQuery('#step1load').show();
        jQuery('#step1errors').hide('slow');
        if (hidesteps) {
            jQuery('#step1times').hide();
        }
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step1',
            placeID: jQuery('#placeID').val(),
            startTime: jQuery('#redi-restaurant-startTime-alt').val(),
            startDateISO: jQuery('#redi-restaurant-startDateISO').val(),
            persons: jQuery('#persons').val(),
            lang: locale,
            timeshiftmode: timeshiftmode,
            apikeyid: apikeyid
        };

        jQuery.post(redi_restaurant_reservation.ajaxurl, data, function (response) {
            jQuery('#step1load').hide();
            jQuery('#step1button').attr('disabled', false);
            jQuery('#buttons').html('');
            if (response['Error']) {
                jQuery('#step1errors').html(response['Error']).show('slow');
            } else {
                if (hidesteps) {
                    jQuery('#step1times').show();
                }
                if (response['alternativeTime'] !== undefined) {
                    switch (response['alternativeTime']) {
                        case 1: //AlternativeTimeBlocks see class AlternativeTime::
                        //pass thought
                        case 2: //AlternativeTimeByShiftStartTime
                            var all_busy = true;
                            for (var res in response) {
                                if (response[res] !== undefined) {
                                    jQuery('#buttons').append(
                                        '<button ' + (response[res]['Available'] ? '' : 'title="This time is fully booked"') + ' class="redi-restaurant-time-button button ' + (response[res]['Available'] ? '' : 'disabled') + '" value="' + response[res]['StartTimeISO'] + '" ' + //(response[res]['Available'] ? '' : 'disabled="disabled"') +
                                        ' ' + (response[res]['Select'] ? 'select="select"' : '') +
                                        '>' + response[res]['StartTime'] + '</button>'
                                    );
                                }
                                if (response[res]['Available']) all_busy = false;
                            }
                            display_all_busy(all_busy);
                            break;
                        case 3: //AlternativeTimeByDay
                            var all_busy = true;
                            var current = 0;
                            var step1buttons_html = '';
                            jQuery('#step1buttons_html').html(step1buttons_html).hide();
                            for (var availability in response) {
                                if (response[availability]['Name'] !== undefined) {
                                    var html = '';

                                    if (!hidesteps) {
                                        if (response[availability]['Name']) {
                                            html += response[availability]['Name'] + ':</br>';
                                        }
                                    }

                                    if (hidesteps) {
                                        if (response[availability]['Name'] === null) {
                                            response[availability]['Name'] = redi_restaurant_reservation.next;
                                        }
                                        step1buttons_html += '<input id="time_' + (current) + '" value="' + response[availability]['Name'] + '" class="redi-restaurant-button button ';
                                        html += '<span class="opentime" id="opentime_' + (current) + '" style="display: none">';
                                        html += jQuery('#time2label').html();
                                    }
                                    var current_button_busy = true;
                                    for (var current_button_index in response[availability]['Availability']) {

                                        var b = response[availability]['Availability'][current_button_index];

                                        html +=
                                            '<button ' + (b['Available'] ? '' : ' title="'+redi_restaurant_reservation.tooltip+'"') + ' class="redi-restaurant-time-button button ' + (b['Available'] ? '' : 'disabled') + '" value="' + b['StartTimeISO'] + '" ' +
                                            ' ' + (b['Select'] ? 'select="select"' : '') + '>'
                                            + b['StartTime'] + '</button>';
                                        if (b['Available']) {
                                            all_busy = false;
                                            current_button_busy = false;
                                        }
                                    }

                                    html += '<br clear="all">';
                                    if (hidesteps) {
                                        html += '</span>';
                                    }

                                    jQuery('#buttons').append(html);
                                    if (current_button_busy) {
                                        step1buttons_html += 'disabled"'; //add class
                                        step1buttons_html += ' title="'+redi_restaurant_reservation.tooltip+'"';
                                    }
                                    else {
                                        step1buttons_html += 'available"'; //close class bracket
                                    }
                                    step1buttons_html += '>';
                                }
                                current++;
                            }
                            jQuery('#buttons').append('</br>');
                            if (jQuery('#persons').val() === 'group') {
                                jQuery('#step1button').attr('disabled', true);
                                jQuery('#large_groups_message').show('slow');
                                jQuery('#step1buttons').hide('slow');
                                if (!hidesteps) {
                                    jQuery('#step2').hide();
                                }
                            } else {
                                jQuery('#step1buttons').html(step1buttons_html).show();
                                display_all_busy(all_busy);
                            }
                            break;
                    }
                } else {
                    for (res in response) {
                        jQuery('#buttons').append(
                            '<button class="redi-restaurant-button redi-restaurant-time-button" value="' +
                            response[res]['StartTimeISO'] + '" ' +
                            (response[res]['Available'] ? '' : 'disabled="disabled"') +
                            ' ' + (response[res]['Select'] ? 'select="select"' : '') +
                            '>' + response[res]['StartTime'] + '</button>'
                        );
                    }
                }

                jQuery('#redi-restaurant-startTimeHidden').val(response['StartTimeISO']);
                if (!hidesteps) {
                    jQuery('#step2').show('slow');
                    // if selected time is available make it bold and show fields
                    jQuery('.redi-restaurant-time-button').each(function () {
                        if (jQuery(this).attr('select')) {
                            jQuery(this).click();
                        }
                    });
                }

                jQuery('#UserName').focus();

            }
        }, 'json');
    }

    function display_all_busy(hide) {
        jQuery('.redi-restaurant-button').tooltip();
        jQuery('.redi-restaurant-time-button').tooltip();
        if (hide) {
            jQuery('#step1buttons').hide();
            if (hidesteps) {
                jQuery('#step1busy').show();
            } else {
                jQuery('#buttons').hide();
                jQuery('#step2busy').show();
            }
        } else {
            jQuery('#step2busy').hide();
            jQuery('#step1buttons').show();
            if (hidesteps) {
                jQuery('#step1busy').hide();
            } else {
                jQuery('#buttons').show();
                jQuery('#step2busy').hide();
            }
        }
    }

    function ga_event(event, comment) {
        if (typeof _gaq !== 'undefined') {
            _gaq.push(['_trackEvent', 'ReDi Restaurant Reservation', event, comment]);
        }
    }

    //Cancel reservation
    jQuery(document).on('click', '#cancel-reservation', function () {
        jQuery('#redi-reservation').slideUp();
        jQuery('#cancel-reservation-div').slideDown();
    });

    jQuery(document).on('click', '#back-to-reservation', function () {
        jQuery('#redi-reservation').slideDown();
        jQuery('#cancel-reservation-div').slideUp();
        jQuery('#cancel-reservation-form').slideDown();
        jQuery('#cancel-success').slideUp();
    });

    jQuery(document).on('click', '#redi-restaurant-cancel', function () {
        var error = '';
        if (jQuery('#redi-restaurant-cancelID').val() === '') {
            error += redi_restaurant_reservation.id_missing + '<br/>';
        }
        if (jQuery('#redi-restaurant-cancelEmail').val() === '') {
            error += redi_restaurant_reservation.email_missing + '<br/>';
        }
        if (jQuery('#redi-restaurant-cancelReason').val() === '') {
            error += redi_restaurant_reservation.reason_missing + '<br/>';
        }
        if (error) {
            jQuery('#cancel-errors').html(error).show('slow');
            return false;
        }
        //Ajax
        var data = {
            action: 'redi_restaurant-submit',
            get: 'cancel',
            ID: jQuery('#redi-restaurant-cancelID').val(),
            Email: jQuery('#redi-restaurant-cancelEmail').val(),
            Reason: jQuery('#redi-restaurant-cancelReason').val(),
            lang: locale,
            apikeyid: apikeyid
        };
        jQuery('#cancel-errors').slideUp();
        jQuery('#cancel-success').slideUp();
        jQuery('#cancel-load').show();
        jQuery('#redi-restaurant-cancel').attr('disabled', true);
        jQuery.post(redi_restaurant_reservation.ajaxurl, data, function (response) {
            jQuery('#redi-restaurant-cancel').attr('disabled', false);
            jQuery('#cancel-load').hide();
            if (response['Error']) {
                jQuery('#cancel-errors').html(response['Error']).show('slow');
            } else {
                jQuery('#cancel-success').slideDown();
                jQuery('#cancel-errors').slideUp();
                jQuery('#cancel-reservation-form').slideUp();
                jQuery('html, body').animate({scrollTop: 0}, 'slow');
                //clear form
                jQuery('#redi-restaurant-cancelID').val('');
                jQuery('#redi-restaurant-cancelEmail').val('');
                jQuery('#redi-restaurant-cancelReason').val('');
            }
        }, 'json');
        return false;
    });
    jQuery(document).on('click', '.available', function (event) {
        event.preventDefault();
        jQuery('#step1').hide();
        jQuery('#step2').show();
        jQuery('#open' + this.id).show();
    });

    jQuery(document).on('click', '#step2prev', function (event) {
        event.preventDefault();
        jQuery('#step1').show();
        jQuery('#step2').hide();
        jQuery('.opentime').each(function () {
            jQuery(this).hide();
        });
    });

    jQuery(document).on('click', '#step3prev', function (event) {
        event.preventDefault();
        jQuery('#step3').hide();
        jQuery('#step2').show();
    });
});

Date.createFromString = function (string) {
    'use strict';
    var pattern = /^(\d\d\d\d)-(\d\d)-(\d\d)[ T](\d\d):(\d\d)$/;
    var matches = pattern.exec(string);
    if (!matches) {
        throw new Error("Invalid string: " + string);
    }
    var year = matches[1];
    var month = matches[2] - 1;   // month counts from zero
    var day = matches[3];
    var hour = matches[4];
    var minute = matches[5];

    // Date.UTC() returns milliseconds since the unix epoch.
    var absoluteMs = Date.UTC(year, month, day, hour, minute, 0);

    return new Date(absoluteMs);
};
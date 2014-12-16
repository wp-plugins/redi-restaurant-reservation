(function ($) {

    /*new*/
    $('.f_person_data tr td').click(function () {
        $('.f_person_data tr td').each(function () {
            $(this).removeClass('select');
        });
        $(this).addClass('select');
    });

    /*new*/
    $('.f_calender_data tr td').click(function () {
        $('.f_calender_data tr td').each(function () {
            $(this).removeClass('select');
        });
        $(this).addClass('select');
        step1call($(this).children('input').val());
    });

    function hideSteps() {
        $('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        $('#step3').hide('slow');
        if (hidesteps) {
            $('#step1busy').hide();
        }
    }

    var updateTime = function () {
        $('#redi-restaurant-startTime').val($('#redi-restaurant-startHour').val() + ':' + $('#redi-restaurant-startMinute').val());
        hideSteps();
    };

    $('#redi-restaurant-startHour').change(updateTime);
    $('#redi-restaurant-startMinute').change(updateTime);
    $('#persons').change(function () {
        if ($(this).val() === 'group') {
            $('#step1button').attr('disabled', true);
            $('#large_groups_message').show('slow');
        }
        else {
            $('#step1button').attr('disabled', false);
            $('#large_groups_message').hide('slow');
        }
        var day1 = $('#redi-restaurant-startDate').datepicker('getDate').getDate();
        var month1 = $('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
        var year1 = $('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
        var fullDate = year1 + '-' + month1 + '-' + day1;
        if (timeshiftmode === 'byshifts') {
            step1call(fullDate);
        }
    });

    if ($.timepicker.regional[datepicker_locale] !== undefined) {
        $.timepicker.setDefaults($.timepicker.regional[datepicker_locale]);
    }
    else {
        $.timepicker.setDefaults($.timepicker.regional['']);
    }

    if ($.datepicker.regional[datepicker_locale] !== undefined) {
        $.datepicker.setDefaults($.datepicker.regional[datepicker_locale.substring(0, 2)]);
    }
    else {
        $.datepicker.setDefaults($.datepicker.regional['']);
    }

    $('#redi-restaurant-startTime').timepicker({
        stepMinute: 15,
        timeFormat: time_format,
        onClose: function (dateText, inst) {
            hideSteps();
        }
    });

    $('#redi-restaurant-startDate').change(function () {
        var day1 = $('#redi-restaurant-startDate').datepicker('getDate').getDate();
        var month1 = $('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
        var year1 = $('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
        var fullDate = year1 + '-' + month1 + '-' + day1;

        $('#redi-restaurant-startDateISO').val(fullDate);
    });

    $('#redi-restaurant-startDate').datepicker({
        dateFormat: date_format,
        minDate: new Date(),
        onSelect: function (dateText, inst) {
            var day1 = $('#redi-restaurant-startDate').datepicker('getDate').getDate();
            var month1 = $('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
            var year1 = $('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
            var fullDate = year1 + '-' + month1 + '-' + day1;
            if (timeshiftmode === 'byshifts') {
                step1call(fullDate)
            }
            else {
                hideSteps();
                $('#redi-restaurant-startDateISO').val(fullDate);
            }
        }
    });

    $('.redi-restaurant-time-button').live('click', function () {
        $('.redi-restaurant-time-button').each(function () {
            //      $(this).removeAttr('select');
            $(this).removeClass('select');
        });
        $(this).addClass('select');
        //$(this).attr('select', 'select');

        $('#redi-restaurant-startTimeHidden').val($(this).val());

        if (hidesteps) {
            $('#step2').hide();
            $('#step3').show();
        } else {
            $('#step3').show('slow');
        }
        $('#UserName').focus();
        return false;
    });

    $('#redi-restaurant-step3').click(function () {
        var error = '';
        if ($('#UserName').val() === '') {
            error += redi_restaurant_reservation.name_missing + '<br/>';
        }
        if ($('#UserEmail').val() === '') {
            error += redi_restaurant_reservation.email_missing + '<br/>';
        }
        if ($('#UserPhone').val() === '') {
            error += redi_restaurant_reservation.phone_missing + '<br/>';
        }
        $('.field_required').each(function () {
            if ($(this).attr('type') === 'checkbox' && $(this).attr('checked') !== 'checked' || $(this).attr('type') === 'textbox' && $(this).val() === '') {
                error += $('#' + this.id + '_message').attr('value') + '<br/>';
            }
        });
        if (error) {
            $('#step3errors').html(error).show('slow');
            return false;
        }
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step3',
            startDate: $('#redi-restaurant-startDate').val(),
            startTime: $('#redi-restaurant-startTimeHidden').val(),
            persons: $('.f_person_data').find('.select').html(),
            UserName: $('#UserName').val(),
            UserEmail: $('#UserEmail').val(),
            UserComments: $('#UserComments').val(),
            UserPhone: $('#UserPhone').val(),
            placeID: $('#placeID').val(),
            lang: locale,
            apikeyid: apikeyid
        };
        if ($('#field_1').attr('type') === 'checkbox' && $('#field_1').attr('checked') === 'checked') {
            data['field_1'] = 'on';
        } else {
            data['field_1'] = $('#field_1').val();
        }
        if ($('#field_2').attr('type') === 'checkbox' && $('#field_2').attr('checked') === 'checked') {
            data['field_2'] = 'on';
        } else {
            data['field_2'] = $('#field_2').val();
        }
        if ($('#field_3').attr('type') === 'checkbox' && $('#field_3').attr('checked') === 'checked') {
            data['field_3'] = 'on';
        } else {
            data['field_3'] = $('#field_3').val();
        }
        if ($('#field_4').attr('type') === 'checkbox' && $('#field_4').attr('checked') === 'checked') {
            data['field_4'] = 'on';
        } else {
            data['field_4'] = $('#field_4').val();
        }
        if ($('#field_5').attr('type') === 'checkbox' && $('#field_5').attr('checked') === 'checked') {
            data['field_5'] = 'on';
        } else {
            data['field_5'] = $('#field_5').val();
        }

        $('#step3load').show();
        $('#step3errors').hide('slow');
        $('#redi-restaurant-step3').attr('disabled', true);
        $.post(redi_restaurant_reservation.ajaxurl, data, function (response) {
            $('#redi-restaurant-step3').attr('disabled', false);
            $('#step3load').hide();
            if (response['Error']) {
                $('#step3errors').html(response['Error']).show('slow');
            } else {
                ga_event('Reservation confirmed', '');
                $('#step1').hide('slow');
                $('#step2').hide('slow');
                $('#step3').hide('slow');
                $('#step4').show('slow'); //success message
                $('html, body').animate({scrollTop: 0}, 'slow');
            }
        }, 'json');
        return false;
    });
    $('#step1button').click(function () {
        if (timeshiftmode === 'byshifts') {
            step1call();
        }
        else {
            $('#step1button').attr('disabled', true);
            var day1 = $('#redi-restaurant-startDate').datepicker('getDate').getDate();
            var month1 = $('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
            var year1 = $('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
            var fullDate = year1 + '-' + month1 + '-' + day1;
            step1call(fullDate);
        }
        return false;
    });

    $('#placeID').change(function () {
        if (hidesteps) {
            $('#step1buttons').hide('slow');
        }
        $('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        $('#step3').hide('slow');
        $('#step1errors').hide('slow');
    });

    function step1call(fullDate) {
        hideSteps();

        $('#redi-restaurant-startDateISO').val(fullDate);
        $('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        $('#step3').hide('slow');
        $('#step1load').show();
        $('#buttons').hide();
        $('#step1errors').hide('slow');
        if (hidesteps) {
            $('#step1times').hide();
        }
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step1',
            placeID: $('#placeID').val(),
            startTime: $('#redi-restaurant-startTime').val(),
            startDateISO: fullDate,
            persons: $('.f_person_data').find('.select').html(),
            lang: locale,
            timeshiftmode: timeshiftmode,
            apikeyid: apikeyid
        };

        $.post(redi_restaurant_reservation.ajaxurl, data, function (response) {
            $('#step1load').hide();
            $('#step1button').attr('disabled', false);
            $('#buttons').html('');
            if (response['Error']) {
                $('#step1errors').html(response['Error']).show('slow');
            } else {
                if (hidesteps) {
                    $('#step1times').show();
                }
                if (response['alternativeTime'] !== undefined) {
                    $('#buttons').show();
                    switch (response['alternativeTime']) {
                        case 1: //AlternativeTimeBlocks see class AlternativeTime::
                        //pass thought
                        case 2: //AlternativeTimeByShiftStartTime
                            var all_busy = true;
                            for (var res in response) {
                                $('#buttons').append(
                                    '<button class="redi-restaurant-time-button button" value="' + response[res]['StartTimeISO'] + '" ' + (response[res]['Available'] ? '' : 'disabled="disabled"') +
                                    ' ' + (response[res]['Select'] ? 'select="select"' : '') +
                                    '>' + response[res]['StartTime'] + '</button>'
                                );
                                if (response[res]['Available']) all_busy = false;
                            }
                            display_all_busy(all_busy);
                            break;
                        case 3: //AlternativeTimeByDay
                            var all_busy = true;
                            var current = 0;
                            var step1buttons_html = '';
                            $('#step1buttons_html').html(step1buttons_html).hide();
                            for (var availability in response) {
                                if (response[availability]['Name'] !== undefined) {
                                    var html = '';
                                    var current = 0;
                                    for (var current_button_index in response[availability]['Availability']) {

                                        var b = response[availability]['Availability'][current_button_index];
                                        current++;
                                        if (current == 1) {
                                            html += '<tr>';
                                        }
                                        //html += '<button class="redi-restaurant-time-button button" value="' + b['StartTimeISO'] + '" ' + ' ' + (b['Available'] ? '' : 'disabled="disabled"') + (b['Select'] ? 'select="select"' : '') + '>' + b['StartTime'] + '</button>';
                                        html += '<td class="redi-restaurant-time-button ' + (b['Select'] ? 'select' : '') + '" ' + (b['Available'] ? '' : 'disabled="disabled"') + '>';
                                        html += '<input type="hidden" value="' + b['StartTimeISO'] + '"/>';
                                        html += b['StartTime'];
                                        html += '</td>';
                                        if (current == 4) {
                                            current = 0;
                                            html += '</tr>';
                                        }
                                        if (b['Available']) all_busy = false;
                                    }
                                    $('#buttons').append(html);
                                }
                            }
                            $('#step1buttons').html(step1buttons_html).show();
                            display_all_busy(all_busy);
                            break;
                    }
                }

                //if (!hidesteps) {
                //    $('#step2').show('slow');
                //}

                $('#UserName').focus();
                $('#redi-restaurant-startTimeHidden').val(response['StartTimeISO']);
            }
        }, 'json');
    }

    function display_all_busy(display) {
        if (display) {
            if (hidesteps) {
                $('#step1busy').show();
                $('.available').each(function () {
                    $(this).attr('disabled', true);
                });
            } else {
                $('#step2busy').show();
            }
        } else {
            $('#step2busy').hide();
            if (hidesteps) {
                $('#step1busy').hide();

                $('.available').each(function () {
                    $(this).attr('disabled', false);
                });
            } else {
                $('#step2busy').hide();
            }
        }
    }

    function ga_event(event, comment) {
        if (typeof _gaq !== 'undefined') {
            _gaq.push(['_trackEvent', 'ReDi Restaurant Reservation', event, comment]);
        }
    }

    //Cancel reservation
    $('#cancel-reservation').click(function () {
        $('#redi-reservation').slideUp();
        $('#cancel-reservation-div').slideDown();
    });

    $('#back-to-reservation').click(function () {
        $('#redi-reservation').slideDown();
        $('#cancel-reservation-div').slideUp();
        $('#cancel-reservation-form').slideDown();
        $('#cancel-success').slideUp();
    });

    $('#redi-restaurant-cancel').click(function () {
        var error = '';
        if ($('#redi-restaurant-cancelID').val() === '') {
            error += redi_restaurant_reservation.id_missing + '<br/>';
        }
        if ($('#redi-restaurant-cancelEmail').val() === '') {
            error += redi_restaurant_reservation.email_missing + '<br/>';
        }
        if ($('#redi-restaurant-cancelReason').val() === '') {
            error += redi_restaurant_reservation.reason_missing + '<br/>';
        }
        if (error) {
            $('#cancel-errors').html(error).show('slow');
            return false;
        }
        //Ajax
        var data = {
            action: 'redi_restaurant-submit',
            get: 'cancel',
            ID: $('#redi-restaurant-cancelID').val(),
            Email: $('#redi-restaurant-cancelEmail').val(),
            Reason: $('#redi-restaurant-cancelReason').val(),
            lang: locale,
            apikeyid: apikeyid
        };
        $('#cancel-errors').slideUp();
        $('#cancel-success').slideUp();
        $('#cancel-load').show();
        $('#redi-restaurant-cancel').attr('disabled', true);
        $.post(redi_restaurant_reservation.ajaxurl, data, function (response) {
            $('#redi-restaurant-cancel').attr('disabled', false);
            $('#cancel-load').hide();
            if (response['Error']) {
                $('#cancel-errors').html(response['Error']).show('slow');
            } else {
                $('#cancel-success').slideDown();
                $('#cancel-errors').slideUp();
                $('#cancel-reservation-form').slideUp();
                $('html, body').animate({scrollTop: 0}, 'slow');
                //clear form
                $('#redi-restaurant-cancelID').val('');
                $('#redi-restaurant-cancelEmail').val('');
                $('#redi-restaurant-cancelReason').val('');
            }
        }, 'json');
        return false;
    });

    $('.available').live('click', function (event) {
        event.preventDefault();
        $('#step1').hide();
        $('#step2').show();
        $('#open' + this.id).show();
    });

    $('#step2prev').click(function (event) {
        event.preventDefault();
        $('#step1').show();
        $('#step2').hide();
    });

    $('#step3prev').on('click', function (event) {
        event.preventDefault();
        $('#step3').hide();
        $('#step2').show();
    });

    $('#next').on('click', function (event) {
        //check if everything is selected
        $('#tab1').removeClass('f_active_step1').addClass('f_non_active_step1');
        $('#tab2').removeClass('f_non_active_step2').addClass('f_active_step2');
        $('#step1').hide();
        $('#f_check_step1').show();
        $('#step2').show();
        event.preventDefault();

    });

})(jQuery);
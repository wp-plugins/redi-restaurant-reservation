jQuery(function () {
    function hideSteps() {
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
    }

    var updateTime = function () {
        jQuery('#redi-restaurant-startTime').val(jQuery('#redi-restaurant-startHour').val() + ':' + jQuery('#redi-restaurant-startMinute').val());
        hideSteps();
    };

    jQuery('#redi-restaurant-startHour').change(updateTime);
    jQuery('#redi-restaurant-startMinute').change(updateTime);
    jQuery('#persons').change(function () {
        if(jQuery(this).val()==='group')
        {
            jQuery('#step1button').attr('disabled', true);
            jQuery('#large_groups_message').show('slow');
        }
        else
        {
            jQuery('#step1button').attr('disabled', false);
            jQuery('#large_groups_message').hide('slow');
        }
        hideSteps();
        jQuery('#step1errors').hide('slow');
    });

    // http://code.google.com/p/logicss/source/browse/trunk/media/js/jquery.ui/?r=45
    // https://github.com/trentrichardson/jQuery-Timepicker-Addon/tree/master/dist/i18n
    if (jQuery.timepicker.regional[locale] != undefined) {
        jQuery.timepicker.setDefaults(jQuery.timepicker.regional[locale]);
    }
    else {
        jQuery.timepicker.setDefaults(jQuery.timepicker.regional[""]);
    }

    if (jQuery.datepicker.regional[locale] != undefined) {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[locale]);
    }
    else {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[""]);
    }

    jQuery('#redi-restaurant-startTime').timepicker({
        stepMinute: 15,
        timeFormat: time_format,
        onClose: function (dateText, inst) {
            hideSteps();
        }
    });

    jQuery("#redi-restaurant-startDate").change(function () {
        var day1 = jQuery("#redi-restaurant-startDate").datepicker('getDate').getDate();
        var month1 = jQuery("#redi-restaurant-startDate").datepicker('getDate').getMonth() + 1;
        var year1 = jQuery("#redi-restaurant-startDate").datepicker('getDate').getFullYear();
        var fullDate = year1 + "-" + month1 + "-" + day1;

        jQuery("#redi-restaurant-startDateISO").val(fullDate);
    });

    jQuery("#redi-restaurant-startDate").datepicker({
        dateFormat: date_format,
        minDate: new Date(),
        onSelect: function (dateText, inst) {
            hideSteps();

            var day1 = jQuery("#redi-restaurant-startDate").datepicker('getDate').getDate();
            var month1 = jQuery("#redi-restaurant-startDate").datepicker('getDate').getMonth() + 1;
            var year1 = jQuery("#redi-restaurant-startDate").datepicker('getDate').getFullYear();
            var fullDate = year1 + "-" + month1 + "-" + day1;

            jQuery("#redi-restaurant-startDateISO").val(fullDate);
        }
    });

    jQuery('#redi-restaurant-step3').click(function () {
        var error = '';
        if (jQuery('#UserName').val() === '') {
            error += redi_restaraurant_reservation.name_missing + '<br/>';
        }
        if (jQuery('#UserEmail').val() === '') {
            error += redi_restaraurant_reservation.email_missing + '<br/>';
        }
        if (jQuery('#UserPhone').val() === '') {

            error += redi_restaraurant_reservation.phone_missing + '<br/>';
        }
        jQuery('.field_required').each(function () {
            if (jQuery(this).attr('type') === 'checkbox' && jQuery(this).attr('checked') !== "checked" || jQuery(this).attr('type') === 'textbox' && jQuery(this).val() === '') {
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
            lang : locale
        };
        if (jQuery('#field_1').attr('type') === 'checkbox' && jQuery('#field_1').attr('checked') === "checked") {
            data['field_1'] = 'on';
        } else {
            data['field_1'] = jQuery('#field_1').val();
        }
        if (jQuery('#field_2').attr('type') === 'checkbox' && jQuery('#field_2').attr('checked') === "checked") {
            data['field_2'] = 'on';
        } else {
            data['field_2'] = jQuery('#field_2').val();
        }
        if (jQuery('#field_3').attr('type') === 'checkbox' && jQuery('#field_3').attr('checked') === "checked") {
            data['field_3'] = 'on';
        } else {
            data['field_3'] = jQuery('#field_3').val();
        }
        if (jQuery('#field_4').attr('type') === 'checkbox' && jQuery('#field_4').attr('checked') === "checked") {
            data['field_4'] = 'on';
        } else {
            data['field_4'] = jQuery('#field_4').val();
        }
        if (jQuery('#field_5').attr('type') === 'checkbox' && jQuery('#field_5').attr('checked') === "checked") {
            data['field_5'] = 'on';
        } else {
            data['field_5'] = jQuery('#field_5').val();
        }

        jQuery('#step3load').show();
        jQuery('#step3errors').hide('slow');
        jQuery('#redi-restaurant-step3').attr('disabled', true);
        jQuery.post(redi_restaraurant_reservation.ajaxurl, data, function (response) {
            jQuery('#redi-restaurant-step3').attr('disabled', false);
            jQuery('#step3load').hide();
            if (response['Error']) {
                jQuery('#step3errors').html(response['Error']).show('slow');
            } else {
                ga_event('Reservation confirmed','');
                jQuery('#step1').hide('slow');
                jQuery('#step2').hide('slow');
                jQuery('#step3').hide('slow');
                jQuery('#step4').show('slow'); //success message
                jQuery("html, body").animate({ scrollTop: 0 }, "slow");
            }
        }, 'json');
        return false;
    });
    jQuery('#step1button').click(function () {
        jQuery('#step1button').attr('disabled', true);
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        jQuery('#step1load').show();
        jQuery('#step1errors').hide('slow');
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step1',
            placeID: jQuery('#placeID').val(),
            startTime: jQuery('#redi-restaurant-startTime').val(),
            startDateISO: jQuery('#redi-restaurant-startDateISO').val(),
            persons: jQuery('#persons').val(),
            lang: locale
        };

        jQuery.post(redi_restaraurant_reservation.ajaxurl, data, function (response) {
            jQuery('#step1load').hide();
            jQuery('#step1button').attr('disabled', false);
            jQuery('#buttons').html('');
            if (response['Error']) {
                jQuery('#step1errors').html(response['Error']).show('slow');
            } else {
                for (res in response) {

                    jQuery('#buttons').append(
                            '<button class="redi-restaurant-button" value="' + response[res]['StartTimeISO'] + '" ' + (response[res]['Available'] ? '' : 'disabled="disabled"') +
                                ' ' + (response[res]['Select'] ? 'select="select"' : '') +
                                '>' + response[res]['StartTime'] + '</button>'
                        );
                }

                jQuery('#step2').show('slow');

                jQuery('.redi-restaurant-button').click(function () {

                    jQuery('.redi-restaurant-button').each(function () {
                        jQuery(this).removeAttr("select");
                    });

                    jQuery(this).attr("select", "select");

                    jQuery('#redi-restaurant-startTimeHidden').val(jQuery(this).val());
                    jQuery('#step3').show('slow');
                    jQuery('#UserName').focus();

                    return false;
                });

                // if selected time is avilable make it bold and show fields
                jQuery('.redi-restaurant-button').each(function () {
                    if (jQuery(this).attr('select')) {
                        jQuery(this).click();
                    }
                });
            }
        }
            , 'json')
        ;
        return false;

    });

    jQuery('#placeID').change(function () {
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        jQuery('#step1errors').hide('slow');
    });

    function ga_event(event, comment)
    {
        if(typeof _gaq !== 'undefined'){
            _gaq.push(['_trackEvent', 'ReDi Restaurant Reservation', event, comment]);
        }
    }
});
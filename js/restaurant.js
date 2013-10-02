jQuery(function () {

    function hideSteps() {
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
    }

    jQuery('#persons').change(function () {
        hideSteps();
    });

    // http://code.google.com/p/logicss/source/browse/trunk/media/js/jquery.ui/?r=45
    // https://github.com/trentrichardson/jQuery-Timepicker-Addon/tree/master/dist/i18n
    if (jQuery.timepicker.regional[locale] != undefined) {
        jQuery.timepicker.setDefaults(jQuery.timepicker.regional[locale]);
    }
    else
    {
        jQuery.timepicker.setDefaults(jQuery.timepicker.regional[""]);
    }
    
    if (jQuery.datepicker.regional[locale] != undefined)
    {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[locale]);
    }
    else{
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional[""]);
    }

    jQuery('#redi-restaurant-startTime').timepicker({
        stepMinute: 15,
        timeFormat: time_format,
        onClose: function (dateText, inst) {
            hideSteps();
        }
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
        if (jQuery('#UserName').val() == '') {
            jQuery('#step3errors').html('Name can\'t be empty').show('slow');
            return false;
        }
        if (jQuery('#UserEmail').val() == '') {
            jQuery('#step3errors').html('Email can\'t be empty').show('slow');
            return false;
        }
        if (jQuery('#UserPhone').val() == '') {
            jQuery('#step3errors').html('Phone can\'t be empty').show('slow');
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
            UserPhone: jQuery('#UserPhone').val()

        };
        jQuery('#step3load').show();
        jQuery('#step3errors').hide('slow');
        jQuery.post(AjaxUrl.ajaxurl, data, function (response) {
            jQuery('#step3load').hide();
            if (response['Error']) {
                jQuery('#step3errors').html(response['Error']).show('slow');
            } else {
                jQuery('#step1').hide('slow');
                jQuery('#step2').hide('slow');
                jQuery('#step3').hide('slow');
                jQuery('#step4').show('slow');
            }
        }, 'json');
        return false;
    });
    jQuery('#step1button').click(function () {
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        jQuery('#step1load').show();
        jQuery('#step1errors').hide('slow');
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step1',
            startDate: jQuery('#redi-restaurant-startDate').val(),
            startTime: jQuery('#redi-restaurant-startTime').val(),
            startDateISO: jQuery('#redi-restaurant-startDateISO').val(),
            persons: jQuery('#persons').val()
        };

        jQuery.post(AjaxUrl.ajaxurl, data, function (response) {
            jQuery('#step1load').hide();

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
                        jQuery(this).css("font-weight", "normal");
                    });

                    jQuery(this).css("font-weight", "bold");

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
});
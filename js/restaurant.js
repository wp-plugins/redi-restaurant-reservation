jQuery(function () {
    jQuery('#startTime').timepicker({stepMinute:15});
    jQuery("#startDate").datepicker({dateFormat:'yy-mm-dd', minDate: new Date()});
    jQuery('#redi-restaurant-step3').click(function () {
        var data = {
            action:'redi_restaurant-submit',
            get:'step3',
            startDate:jQuery('#startDate').val(),
            startTime:jQuery('#startTime1').val(),
            persons:jQuery('#persons').val(),
            UserName:jQuery('#UserName').val(),
            UserEmail:jQuery('#UserEmail').val(),
            UserComments:jQuery('#UserComments').val(),
            UserPhone:jQuery('#UserPhone').val()

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
            action:'redi_restaurant-submit',
            get:'step1',
            startDate:jQuery('#startDate').val(),
            startTime:jQuery('#startTime').val(),
            persons:jQuery('#persons').val()
        };

        jQuery.post(AjaxUrl.ajaxurl, data, function (response) {
                jQuery('#step1load').hide();

                jQuery('#buttons').html('');
                if (response['Error']) {
                    jQuery('#step1errors').html(response['Error']).show('slow');
                } else {
                    for (res in response) {
                        //    console.log(response[res]['Available']);
                        jQuery('#buttons').append(
                            '<button class="redi-restaurant-button" value="' + response[res]['StartTime'] + '" ' + (response[res]['Available'] ? '' : 'disabled="disabled"') +
                                '>' + response[res]['StartTime'] + '</button>'
                        );
                    }
                    jQuery('#step2').show('slow');
                    jQuery('.redi-restaurant-button').click(function () {
                        jQuery('.redi-restaurant-button').each(function(){
                                jQuery(this).html(jQuery(this).val());
                            }
                        );
                        jQuery(this).html('<b>'+jQuery(this).val()+'</b>');
                        jQuery('#startTime1').val(jQuery(this).val());
                        jQuery('#step3').show('slow');

                        return false;

                    });
                }
            }
            , 'json')
        ;
        return false;

    });
});
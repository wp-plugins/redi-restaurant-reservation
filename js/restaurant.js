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
        if (jQuery(this).val() === 'group') {
            jQuery('#step1button').attr('disabled', true);
            jQuery('#large_groups_message').show('slow');
        }
        else {
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
jQuery('#datepicker').change(function () {
        var day1 = jQuery('#datepicker').datepicker('getDate').getDate();
        var month1 = jQuery('#datepicker').datepicker('getDate').getMonth() + 1;
        var year1 = jQuery('#datepicker').datepicker('getDate').getFullYear();
        var fullDate = year1 + '-' + month1 + '-' + day1;
		var hiddendate=day1 + '/' + month1 + '/' + year1;
        jQuery('#redi-restaurant-startDateISO').val(fullDate);
		jQuery('#redi-restaurant-startDate').val(hiddendate);
    });
  /*  jQuery('#redi-restaurant-startDate').change(function () {
        var day1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getDate();
        var month1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
        var year1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
        var fullDate = year1 + '-' + month1 + '-' + day1;

        jQuery('#redi-restaurant-startDateISO').val(fullDate);
    });*/
jQuery( "#datepicker" ).datepicker(); 

 jQuery('#datepicker').datepicker({
        dateFormat: date_format,
        minDate: new Date(),
        onSelect: function (dateText, inst) {
            hideSteps();

            var day1 = jQuery('#datepicker').datepicker('getDate').getDate();
		//	alert(day1);
            var month1 = jQuery('#datepicker').datepicker('getDate').getMonth() + 1;
		//	alert(month1);
            var year1 = jQuery('#datepicker').datepicker('getDate').getFullYear();
		//	alert(year1);
            var fullDate = year1 + '-' + month1 + '-' + day1;
		//	alert(fullDate);
            jQuery('#redi-restaurant-startDateISO').val(fullDate);
        }
    });

 jQuery('#popupBoxClose').click( function() {            
            unloadPopupBox();
        });
        
        jQuery('#container').click( function() {
            unloadPopupBox();
        });

/* jQuery('#step1button1').click( function() {
	 alert("hiii");
            loadPopupBox();
        });*/
        function unloadPopupBox() {    // TO Unload the Popupbox
           jQuery('#popup_box').fadeOut("slow");
            jQuery("#container").css({ // this is just for style        
                "opacity": "1"  
            }); 
			
			
			window.setTimeout(function(){location.reload()},1000);
        }    
        
        function loadPopupBox() {    // To Load the Popupbox
            jQuery('#popup_box').fadeIn("slow");
			jQuery("#fade").fadeIn("slow");
			jQuery("#step2").css({ // this is just for style        
                "display": "block !important"  
            });
			
			
            jQuery("#container").css({ // this is just for style
                "opacity": "0.3"  
            });      
        }   
/* jQuery('#redi-restaurant-startDate').datepicker({
        dateFormat: date_format,
        minDate: new Date(),
        onSelect: function (dateText, inst) {
            hideSteps();

            var day1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getDate();
            var month1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getMonth() + 1;
            var year1 = jQuery('#redi-restaurant-startDate').datepicker('getDate').getFullYear();
            var fullDate = year1 + '-' + month1 + '-' + day1;

            jQuery('#redi-restaurant-startDateISO').val(fullDate);
        }
    });*/

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
            if (jQuery(this).attr('type') === 'checkbox' && jQuery(this).attr('checked') !== 'checked' || jQuery(this).attr('type') === 'textbox' && jQuery(this).val() === '') {
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
        if (jQuery('#field_1').attr('type') === 'checkbox' && jQuery('#field_1').attr('checked') === 'checked') {
            data['field_1'] = 'on';
        } else {
            data['field_1'] = jQuery('#field_1').val();
        }
        if (jQuery('#field_2').attr('type') === 'checkbox' && jQuery('#field_2').attr('checked') === 'checked') {
            data['field_2'] = 'on';
        } else {
            data['field_2'] = jQuery('#field_2').val();
        }
        if (jQuery('#field_3').attr('type') === 'checkbox' && jQuery('#field_3').attr('checked') === 'checked') {
            data['field_3'] = 'on';
        } else {
            data['field_3'] = jQuery('#field_3').val();
        }
        if (jQuery('#field_4').attr('type') === 'checkbox' && jQuery('#field_4').attr('checked') === 'checked') {
            data['field_4'] = 'on';
        } else {
            data['field_4'] = jQuery('#field_4').val();
        }
        if (jQuery('#field_5').attr('type') === 'checkbox' && jQuery('#field_5').attr('checked') === 'checked') {
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
				var bookingdate=jQuery('#redi-restaurant-startTimeHidden').val();
				var bookingdatesplit=bookingdate.split(' ');
				var bookingdatee=bookingdatesplit[0];
				var datearray=bookingdatee.split('-');
				var reservationdate=datearray[2]+"-"+datearray[1]+"-"+datearray[0];
				//alert(reservationdate);
				var bookingtime=bookingdatesplit[1];
				//alert(bookingtime);
                ga_event('Reservation confirmed','');
                jQuery('#step1').hide('slow');
                jQuery('#step2').hide('slow');
                jQuery('#step3').hide('slow');
				jQuery('#success_msg').html('You should receive your confirmation by email shortly.Details of your Booking are as follows: </br> No of Person : '+jQuery('#persons').val()+' </br>Date of Reservation : '+reservationdate+" "+bookingtime );
                jQuery('#step4').show('slow'); //success message
				//jQuery('#popup_box').css('display','none !important'); 
                jQuery('html, body').animate({ scrollTop: 0 }, 'slow');
				window.setTimeout(function(){location.reload()},10000);
            }
        }, 'json');
        return false;
    });
	function getDayOfDate(givenDate) {  
weekDays=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]  
var dArray = givenDate.split("-");  

// You have to write code that will check whether the date is valid or not because February cannot be 30 or 31, etc  
  
// months in Javascript are 0-11  
myDate=new Date(dArray[0],dArray[1]-1,dArray[2]);
  
var dayCode = myDate.getDay(); // dayCode 0-6  
//alert(dayCode);
var dayIs=weekDays[dayCode]; //It will contain the required day, in this example it will be friday  
//alert(dayIs);
return dayIs; 
}  
    jQuery('#step1button1').click(function () {
	//	alert("hi");
		loadPopupBox();
		
	//	alert("hello");
	//	alert(jQuery('#session').val());
	    var bookingdate=jQuery('#redi-restaurant-startDateISO').val();
		//alert(bookingdate);
        var dd=getDayOfDate(bookingdate); //Give call to the function  
	//	alert(dd);
		var session=jQuery('#session').val();
	    if(session=="lunch")
		{
			jQuery('#redi-restaurant-startTime').val('12:00 pm');
		}else  if(session=="dinner"){
		   if((dd=="Monday")||(dd=="Tuesday")||(dd=="Wednesday")||(dd=="Thursday")||(dd=="Friday")||(dd=="Saturday"))
		   {  
		   //alert("hello");
			jQuery('#redi-restaurant-startTime').val('06:00 pm');
		   }
		}
		//alert(jQuery('#redi-restaurant-startTime').val());
        jQuery('#step1button').attr('disabled', true);
		
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
     //   jQuery('#step3').hide('slow');
	
        jQuery('#step1load').show();
        jQuery('#step1errors').hide('slow');
        var data = {
            action: 'redi_restaurant-submit',
            get: 'step1',
			session: session,
            placeID: jQuery('#placeID').val(),
            startTime: jQuery('#redi-restaurant-startTime').val(),
            startDateISO: jQuery('#redi-restaurant-startDateISO').val(),
            persons: jQuery('#persons').val(),
            lang: locale
        };
       // alert(data);
        jQuery.post(redi_restaraurant_reservation.ajaxurl, data, function (response) {
			//alert(response);
		
            jQuery('#step1load').hide();
            jQuery('#step1button').attr('disabled', false);
            jQuery('#buttons').html('');
            if (response['Error']) {
				//alert(response['Error']);
			jQuery("#step2").css({ // this is just for style        
                "display": "block !important"  
            });
				 
			jQuery("#fade").css({ // this is just for style        
                "display": "block !important"  
            });
                jQuery('#step1errors').html(response['Error']).show('slow');
				
            } else {
				var session = $( "#session option:selected" ).text();
			
                for (res in response) {	
                     				
				//alert(response[res]['StartTimeISO']);
				//alert(response[res]['StartTime']);
					  if(response[res]['StartTimeISO']!=null){
						
                    jQuery('#buttons').append(
                            '<button id="'+ response[res]['StartTime'] +'" class="redi-restaurant-button redi-restaurant-time-button" value="' + response[res]['StartTimeISO'] + '" '  +
                                ' ' + (response[res]['Select'] ? '' : '') +
                                '>' + response[res]['StartTime'] + '</button>'
                        );
						
						
						
						if(session=="Dinner")
						{
						   if(dd!="Sunday")
						   {
						      if((response[res]['StartTime']<"5:45pm"))
						      {
							     jQuery(".redi-restaurant-time-button").css("display","none");
							  }
							  if(response[res]['StartTime']=="9:00 pm")
							  {
								  $("[id='"+response[res]['StartTime']+"']").css("display","none");
							  }
							  
						   }else{
						   jQuery('#step1errors').html("Dinner facility is not provided on Sunday.");
						   jQuery('#step1errors').show('slow');
						   jQuery('#buttons').hide('slow');
						   jQuery('#msg').hide('slow');
						   }
						}else if(session=="Lunch")
						{
						
						 if(dd!="Sunday")
						   {
						      if(response[res]['StartTime']<"11:45pm")
						      {
							     jQuery(".redi-restaurant-time-button").css("display","none");
							  }
						   }else{
						      if(response[res]['StartTime']<"11:45pm")
						      {
							     jQuery(".redi-restaurant-time-button").css("display","none");
							  }
						   }
						
						}
						
						
						jQuery( "#msg" ).css("display","block");
					}
                }
			
                //jQuery('#step2').show('slow');
                   jQuery('#step2').show();
				   jQuery('#step3').hide();
				  //  alert(jQuery('#step2').css('display'));
		          //   alert(jQuery('#step3').css('display')); 
                    jQuery('.redi-restaurant-time-button').click(function () {
                    jQuery('#step3').show();
					//jquery('#redi-restaurant-step3').show();
                    jQuery('.redi-restaurant-time-button').each(function () {
                        jQuery(this).removeAttr('select');
                    });

                    jQuery(this).attr('select', 'select');

                    jQuery('#redi-restaurant-startTimeHidden').val(jQuery(this).val());
                   // jQuery('#step3').show('slow');
                    jQuery('#UserName').focus();

                    return false;
                });

                // if selected time is avilable make it bold and show fields
                jQuery('.redi-restaurant-time-button').each(function () {
                    if (jQuery(this).attr('select')) {
                        jQuery(this).click();
                    }
                });
            }
        },'json')
        ;
        return false;

    });

    jQuery('#placeID').change(function () {
        jQuery('#step2').hide('slow'); // if user clicks again first button we hide the other steps
        jQuery('#step3').hide('slow');
        jQuery('#step1errors').hide('slow');
    });

    function ga_event(event, comment) {
        if (typeof _gaq !== 'undefined') {
            _gaq.push(['_trackEvent', 'ReDi Restaurant Reservation', event, comment]);
        }
    }

    //Cancel reservation
    jQuery('#cancel-reservation').click(function () {
        jQuery('#redi-reservation').slideUp();
        jQuery('#cancel-reservation-div').slideDown();
    });

    jQuery('#back-to-reservation').click(function () {
        jQuery('#redi-reservation').slideDown();
        jQuery('#cancel-reservation-div').slideUp();
        jQuery('#cancel-reservation-form').slideDown();
        jQuery('#cancel-success').slideUp();
    });

    jQuery('#redi-restaurant-cancel').click(function () {
        var error = '';
        if (jQuery('#redi-restaurant-cancelID').val() === '') {
            error += redi_restaraurant_reservation.id_missing + '<br/>';
        }
        if (jQuery('#redi-restaurant-cancelEmail').val() === '') {
            error += redi_restaraurant_reservation.email_missing + '<br/>';
        }
        if (jQuery('#redi-restaurant-cancelReason').val() === '') {
            error += redi_restaraurant_reservation.reason_missing + '<br/>';
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
            lang: locale
        };
        jQuery('#cancel-errors').slideUp();
        jQuery('#cancel-success').slideUp();
        jQuery('#cancel-load').show();
        jQuery('#redi-restaurant-cancel').attr('disabled', true);
        jQuery.post(redi_restaraurant_reservation.ajaxurl, data, function (response) {
            jQuery('#redi-restaurant-cancel').attr('disabled', false);
            jQuery('#cancel-load').hide();
            if (response['Error']) {
                jQuery('#cancel-errors').html(response['Error']).show('slow');
            } else {
                jQuery('#cancel-success').slideDown();
                jQuery('#cancel-errors').slideUp();
                jQuery('#cancel-reservation-form').slideUp();
                jQuery('html, body').animate({ scrollTop: 0 }, 'slow');
                //clear form
                jQuery('#redi-restaurant-cancelID').val('');
                jQuery('#redi-restaurant-cancelEmail').val('');
                jQuery('#redi-restaurant-cancelReason').val('');
            }
        }, 'json');
        return false;
    });
});
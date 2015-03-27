<!-- ReDi restaurant reservation plugin version <?php echo $this->version?> -->
<?php require_once(REDI_RESTAURANT_TEMPLATE.'cancel.php');?>
<script type="text/javascript">var date_format = '<?php echo $calendar_date_format ?>';<?php $time_format_s =explode(':', $time_format);if(isset($time_format_s[0]) && in_array($time_format_s[0], array('g','h'))):?>var time_format ="h:mm tt";<?php else: ?>var time_format ="HH:mm";<?php endif ?>var locale = "<?php echo get_locale()?>";if ((/^en/).test(locale))locale = "";</script>
<style type="text/css">
.ui-datepicker{
width:16em ;
}
/* popup_box DIV-Styles*/
#popup_box { 
    display:none; /* Hide the DIV */
    position:fixed;  
    _position:absolute; /* hack for internet explorer 6 */  
    /*width:600px; */
	width:74%;
    background:#FFFFFF;  
   /* left: 300px;*/ 
   left: 5%;
   top: 56px;
    z-index:1000;  /* Layering ( on-top of others), if you have lots of layers: I just maximized, you can change it yourself */
    margin-left: 15px;  
    
    /* additional features, can be omitted */
    border:2px solid #ff0000;      
    padding:15px;  
    font-size:15px;  
    -moz-box-shadow: 0 0 5px #ff0000;
    -webkit-box-shadow: 0 0 5px #ff0000;
    box-shadow: 0 0 5px #ff0000;
    
}

#container {
    background: #d2d2d2; /*Sample*/
    width:100%;
    height:100%;
}

a{  
cursor: pointer;  
text-decoration:none;  
} 
.black_overlay{
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            min-height: 2274px;
            background-color: black;
            z-index:999;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=80);
        }
/* This is for the positioning of the Close Link */
#popupBoxClose {
    font-size:15px;  
    line-height:15px;  
    right:5px;  
    top:5px;  
    position:absolute;  
    color:#d0301f;  
    font-weight:500;      
	}
	
#step3{
line-height:15px;
}

table.fullcalendar td{
text-align:center;
}

table.fullcalendar{
width:100%;
}

</style> 
<form id="redi-reservation" name="redi-reservation" method="post">

	<div id="step1" style="margin-top: -15px;">
		
        <?php if(count((array)$places) > 1 ): ?>
            <h2 style="float:left;"> <?php _e('Step', 'redi-restaurant-reservation')?> 1: <?php _e('Select place, date and time', 'redi-restaurant-reservation')?></h2><a href="#cancel" id="cancel-reservation" class="cancel-reservation" style="border-radius: 31px;float: left;font-size: 13px;font-weight: bold;padding: 13px;width: 38%;margin-top:0;"><?php _e('Cancel Booking', 'redi-restaurant-reservation')?></a>
            <br clear="both"/><label for="placeID"><?php _e('Place', 'redi-restaurant-reservation')?>:</label><br clear="both"/>
		 <select name="placeID" id="placeID" class="redi-reservation-select">
			<?php foreach((array)$places as $place_current):?>
				<option value="<?php echo $place_current->ID ?>">
					<?php echo $place_current->Name ?>
				</option>
			<?php endforeach; ?>
		 </select>
         <!--<br clear="both"/> -->
         <?php else: ?>
        <!-- <h2 style="float:left;"> <?php _e('Step', 'redi-restaurant-reservation')?> 1: <?php _e('Select date and time', 'redi-restaurant-reservation')?></h2> <a href="#cancel" id="cancel-reservation" class="cancel-reservation" style="display:block;"><?php _e('Cancel reservation', 'redi-restaurant-reservation')?></a>-->
            <input type="hidden" id="placeID" name="placeID" value="<?php echo $places[0]->ID ?>"/>
         <?php endif ?>
		<!--<br clear="both"/><label for="redi-restaurant-startDate"><?php _e('Date', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>--><br clear="both"/>
		<input type="hidden" value="<?php echo $startDate ?>" name="startDate" id="redi-restaurant-startDate"/>
        <div id="datepicker"></div>
      <!--  <br clear="both"/>
        <br clear="both"/><label for="redi-restaurant-startHour"><?php _e('Time', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br clear="both"/> -->
		<?php if (isset($timepicker) && $timepicker === 'dropdown'):?>
			<select id="redi-restaurant-startHour" class="redi-reservation-select">
				<?php foreach(range(0, 23) as $hour):?>
					<option value="<?php echo $hour;?>" <?php if(date('H',$startTime)==$hour):?>selected="selected"<?php endif;?>><?php echo date($time_format_hours, strtotime( $hour.':00'));?></option>
				<?php endforeach;?>
			</select>&nbsp;:&nbsp;<select id="redi-restaurant-startMinute" class="redi-reservation-select">
				<?php foreach(range(0, 45, 15) as $minute):?>
					<option value="<?php echo $minute;?>"><?php printf('%02d', $minute);?></option>
				<?php endforeach;?>
			</select>
			<input id="redi-restaurant-startTime" type="hidden" value="<?php echo date_i18n($time_format, $startTime);?>" name="startTime"/><!--<br clear="both"/>-->
		<?php else:?>
			<input id="redi-restaurant-startTime" type="hidden" value="<?php echo date_i18n($time_format, $startTime);?>" name="startTime"/><!--<br clear="both"/>-->
		<?php endif ?>
		<input id="redi-restaurant-startDateISO" type="hidden" value="<?php echo $startDateISO ?>" name="startDateISO"/><!--<br clear="both"/>--><div style="width: 100%; height: 14px;"></div>
        <label for="session" style="font-size: 13px; font-weight: bold;"><?php _e('Session', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>

		<select name="session" id="session" class="redi-reservation-select" style="padding:3px;">
              <!--  <option value="breakfast" >Breakfast</option> -->
                <option value="lunch" >Lunch</option>
                <option value="dinner" >Dinner</option>
		</select>
        
		<label for="persons" style="font-size: 13px; font-weight: bold;margin-left:9%;"><?php _e('Persons', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>

		<select name="persons" id="persons" class="redi-reservation-select" style="width:17%;padding:3px;">
			<?php for ($i = $minPersons; $i != $maxPersons+1; $i++): ?>
                <option value="<?php echo $i?>" ><?php /*<?php if ($persons == $i) echo 'selected="selected"';?> */ ?>
                    <?php echo $i ?>
                </option>
			<?php endfor?>
            <?php if (!empty($largeGroupsMessage)):?>
                <option value="group" >
                    <?php echo sprintf( __( 'More than %s people', 'redi-restaurant-reservation' ), $maxPersons );?>
                </option>
            <?php endif ?>
		</select>

        <div id="large_groups_message" style="display: none;margin-top: 30px;" class="redi-reservation-alert-info redi-reservation-alert"><?php echo $largeGroupsMessage?></div>
		<div style="margin-top: 14px;">
			<!--<input class="redi-restaurant-button" id="step1button" type="submit" value="<?php _e('Check available time', 'redi-restaurant-reservation');?>" name="submit">-->
         <a href="#cancel" id="cancel-reservation" class="cancel-reservation" style="border-radius: 31px;float: left;font-size: 13px;font-weight: bold;padding: 13px;width: 38%;margin-top:0;"><?php _e('Cancel Booking', 'redi-restaurant-reservation')?></a>   <input class="redi-restaurant-button" id="step1button1" type="button" value="<?php _e('Next', 'redi-restaurant-reservation');?>" name="submit" style="border-radius: 31px;float: right;font-size: 13px;font-weight: bold;margin-right: 10px;padding: 16px;width: 38%;">
			<img id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
		</div>
       
	</div>
	<br clear="both"/>
<div id="popup_box" style="display:none;">
	<div>
    <div id="step2" style="display: block;">
		<h2><?php _e('Step', 'redi-restaurant-reservation')?> 2: <?php _e('Select available time', 'redi-restaurant-reservation')?></h2>
         <br clear="both"/>
        <div id="step1errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
		<div id="buttons">
          
		</div>
		<input type="hidden" id="redi-restaurant-startTimeHidden" value=""/>
      <div id="msg" style="display:none;">Please select the prefered time to procced.</div>
	</div>
    <br clear="both"/>
  
    <div id="step3" style="display: none !important;">
		<h2><?php _e('Step', 'redi-restaurant-reservation')?> 3: <?php _e('Provide reservation details', 'redi-restaurant-reservation')?></h2>
		<div>
			<br clear="both"/>
			<label for="UserName"><?php _e('Name', 'redi-restaurant-reservation');?>:
				<span class="redi_required">*</span>
			</label>
			<br clear="both"/>
			<input type="text" value="" name="UserName" id="UserName">
		</div>
		<div>
			<br clear="both"/>
			<label for="UserPhone"><?php _e('Phone', 'redi-restaurant-reservation');?>:
				<span class="redi_required">*</span>
			</label>
			<br clear="both"/>
			<input type="text" value="" name="UserPhone" id="UserPhone">
		</div>
		<div>
			<br clear="both"/>
			<label for="UserEmail"><?php _e('Email', 'redi-restaurant-reservation');?>:
				<span class="redi_required">*</span>
			</label>
			<br clear="both"/>
			<input type="text" value="" name="UserEmail" id="UserEmail">
		</div>
		<?php // echo"<!-- custom fields -->"; ?>
		<?php for($i=1; $i!=6; $i++):?>
			<?php $field_name = 'field_'.$i.'_name'; ?>
			<?php $field_required = 'field_'.$i.'_required'; ?>
			<?php $field_type = 'field_'.$i.'_type'; ?>
			<?php $field_message = 'field_'.$i.'_message'; ?>

			<?php if(isset($$field_name) && !empty($$field_name)):?>
				<div>
					<br clear="both"/>
					<label for="field_<?php echo $i; ?>"><?php echo $$field_name; ?>:
						<?php if(isset($$field_required) && $$field_required):?>
							<span class="redi_required">*</span>
							<input type="hidden" id="<?php echo 'field_'.$i.'_message'; ?>"
								value="<?php
								if(!empty($$field_message))
								{
									echo $$field_message;
								}
								else
								{
									/// TODO: Custom field ${name} is required, $$field_name
									echo ( _e('Custom field is required', 'redi-restaurant-reservation'));
								}
								?>">
						<?php endif;?>
					</label>
					<br clear="both"/>

					<input type="<?php echo($$field_type);?>" value="" name="field_<?php echo $i; ?>" id="field_<?php echo $i; ?>" <?php if(isset($$field_required) && $$field_required):?>class="field_required"<?php endif; ?>>
				</div>
			<?php endif;?>
		<?php endfor;?>
        <?php //echo"<!-- /custom fields -->"; ?>
		<div>
			<br clear="both"/>
			<label for="UserComments">
				<?php _e('Comment', 'redi-restaurant-reservation');?>:
			</label>
			<br clear="both"/>
			<textarea maxlength="250" rows="2" name="UserComments" id="UserComments" cols="20"></textarea>
		</div>
		<div>
			<br clear="both"/><br clear="both"/>
			<input class="redi-restaurant-button" type="submit" id="redi-restaurant-step3" name="Action" value="<?php _e('Make a reservation', 'redi-restaurant-reservation')?>">
			<img id="step3load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
			<br clear="both"/>
		</div>
		<br clear="both"/>
        <br clear="both"/>
		<div id="step3errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
	</div>
    <div id="step4" style="display: none;" class="redi-reservation-alert-success redi-reservation-alert">
		<strong>
			<?php _e('Thank you for your reservation.', 'redi-restaurant-reservation')?><br clear="both"/>
		</strong>
        
		<div id="success_msg"><?php _e('We will create a confirmation and email it to you at the email address you entered on the reservations form. You should receive your confirmation by email shortly.', 'redi-restaurant-reservation');?> </div>
	</div>
    <a id="popupBoxClose">Close</a>    
    </div>

</div>
<div id="fade" class="black_overlay"></div>
	<!--<div id="step2" style="display: none;">
		<h2><?php _e('Step', 'redi-restaurant-reservation')?> 2: <?php _e('Select available time', 'redi-restaurant-reservation')?></h2>
		 <br clear="both"/>
        <div id="step1errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
        <div id="buttons">
        
		</div>
		<input type="hidden" id="redi-restaurant-startTimeHidden" value=""/>
	</div>
	<br clear="both"/>-->

	<!--<div id="step3" style="display: none;">
		<h2><?php _e('Step', 'redi-restaurant-reservation')?> 3: <?php _e('Provide reservation details', 'redi-restaurant-reservation')?></h2>
		<div>
			<br clear="both"/>
			<label for="UserName"><?php _e('Name', 'redi-restaurant-reservation');?>:
				<span class="redi_required">*</span>
			</label>
			<br clear="both"/>
			<input type="text" value="" name="UserName" id="UserName">
		</div>
		<div>
			<br clear="both"/>
			<label for="UserPhone"><?php _e('Phone', 'redi-restaurant-reservation');?>:
				<span class="redi_required">*</span>
			</label>
			<br clear="both"/>
			<input type="text" value="" name="UserPhone" id="UserPhone">
		</div>
		<div>
			<br clear="both"/>
			<label for="UserEmail"><?php _e('Email', 'redi-restaurant-reservation');?>:
				<span class="redi_required">*</span>
			</label>
			<br clear="both"/>
			<input type="text" value="" name="UserEmail" id="UserEmail">
		</div>
		<?php // echo"<!-- custom fields -->"; ?>
		<?php for($i=1; $i!=6; $i++):?>
			<?php $field_name = 'field_'.$i.'_name'; ?>
			<?php $field_required = 'field_'.$i.'_required'; ?>
			<?php $field_type = 'field_'.$i.'_type'; ?>
			<?php $field_message = 'field_'.$i.'_message'; ?>

			<?php if(isset($$field_name) && !empty($$field_name)):?>
				<div>
					<br clear="both"/>
					<label for="field_<?php echo $i; ?>"><?php echo $$field_name; ?>:
						<?php if(isset($$field_required) && $$field_required):?>
							<span class="redi_required">*</span>
							<input type="hidden" id="<?php echo 'field_'.$i.'_message'; ?>"
								value="<?php
								if(!empty($$field_message))
								{
									echo $$field_message;
								}
								else
								{
									/// TODO: Custom field ${name} is required, $$field_name
									echo ( _e('Custom field is required', 'redi-restaurant-reservation'));
								}
								?>">
						<?php endif;?>
					</label>
					<br clear="both"/>

					<input type="<?php echo($$field_type);?>" value="" name="field_<?php echo $i; ?>" id="field_<?php echo $i; ?>" <?php if(isset($$field_required) && $$field_required):?>class="field_required"<?php endif; ?>>
				</div>
			<?php endif;?>
		<?php endfor;?>
        <?php //echo"<!-- /custom fields -->"; ?>
		<div>
			<br clear="both"/>
			<label for="UserComments">
				<?php _e('Comment', 'redi-restaurant-reservation');?>:
			</label>
			<br clear="both"/>
			<textarea maxlength="250" rows="2" name="UserComments" id="UserComments" cols="20"></textarea>
		</div>
		<div>
			<br clear="both"/><br clear="both"/>
			<input class="redi-restaurant-button" type="submit" id="redi-restaurant-step3" name="Action" value="<?php _e('Make a reservation', 'redi-restaurant-reservation')?>">
			<img id="step3load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
			<br clear="both"/>
		</div>
		<br clear="both"/>
        <br clear="both"/>
		<div id="step3errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
	</div>-->
    
   
   
    
    
	<!--<div id="step4" style="display: none;" class="redi-reservation-alert-success redi-reservation-alert">
		<strong>
			<?php _e('Thank you for your reservation.', 'redi-restaurant-reservation')?><br clear="both"/>
		</strong>
		<?php _e('We will create a confirmation and email it to you at the email address you entered on the reservations form. You should receive your confirmation by email shortly.', 'redi-restaurant-reservation');?>
	</div>-->
</form>
<?php if($thanks):?>
	<div id="Thanks" style="">
		<a style="float: right;" href="http://www.reservationdiary.eu/" target="_blank">
			<label style="font-size: 10px;">
			<?php _e('Powered by', 'redi-restaurant-reservation')?>
			</label>
			<img style="border:none; margin-left: 3px;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL?>img/logo.png" alt="Powered by reservationdiary.eu" title="Powered by reservationdiary.eu"/></a>
	</div>
<?php endif ?>
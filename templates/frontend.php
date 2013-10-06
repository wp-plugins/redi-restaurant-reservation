<!--{version:"<?php echo $this->version?>"}-->
<script type="text/javascript">
	var date_format = '<?php echo $calendar_date_format ?>';
    <?php
    $time_format_s =explode(':', $time_format);

    if(isset($time_format_s[0]) && in_array($time_format_s[0], array('g','h'))):?>
var time_format ="h:mm tt";
    <?php else: ?>
var time_format ="HH:mm";
    <?php endif ?>

    var locale = "<?php echo get_locale()?>";

    if ((/^en/).test(locale))
    {
        locale = "";
    }
	
</script>
<form id="redi-reservation" name="redi-reservation" method="post">
    <div id="step1">
        <h2> <?php _e('Step', 'redi-restaurant-reservation')?> 1: <?php _e('Select date and time', 'redi-restaurant-reservation')?></h2>
        <br/><label for="redi-restaurant-startDate"><?php _e('Date and time', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>
        <input type="text" value="<?php echo $startDate ?>" name="startDate" id="redi-restaurant-startDate"/>
        <input id="redi-restaurant-startTime" type="text" value="<?php echo date_i18n($time_format, $startTime);?>" name="startTime"/><br/>
        <input id="redi-restaurant-startDateISO" type="hidden" value="<?php echo $startDateISO ?>" name="startDateISO"/>
        <br/><label for="persons"><?php _e('Persons', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>

        <select name="persons" id="persons">
			<?php for ($i = 1; $i != $maxPersons+1; $i++): ?>
            <option value="<?php echo $i?>" <?php if ($persons == $i) echo 'selected="selected"';?> >
	            <?php echo $i ?>
            </option>
			<?php endfor?>
        </select>

        <div style="margin-top: 30px; margin-bottom: 30px;">
            <input id="step1button" type="submit" value="<?php _e('Check available time', 'redi-restaurant-reservation');?>" name="submit">
			<img id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
        </div>

        <div id="step1errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
    </div>
    <br/>

    <div id="step2" style="display: none;">
        <h2><?php _e('Step', 'redi-restaurant-reservation')?> 2: <?php _e('Select available time', 'redi-restaurant-reservation')?></h2>
        <div id="buttons">
        </div>
        <input type="hidden" id="redi-restaurant-startTimeHidden" value=""/>
    </div>
    <br/>

    <div id="step3" style="display: none;">
        <h2><?php _e('Step', 'redi-restaurant-reservation')?> 3: <?php _e('Provide reservation details', 'redi-restaurant-reservation')?></h2>
        <div>
            <br/>
            <label for="UserName"><?php _e('Name', 'redi-restaurant-reservation');?>:
	            <span class="redi_required">*</span>
            </label>
	        <br/>
            <input type="text" value="" name="UserName" id="UserName">
        </div>
        <div>
            <br/>
	        <label for="UserPhone"><?php _e('Phone', 'redi-restaurant-reservation');?>:
		        <span class="redi_required">*</span>
	        </label>
	        <br/>
            <input type="text" value="" name="UserPhone" id="UserPhone">
        </div>
        <div>
            <br/>
            <label for="UserEmail"><?php _e('Email', 'redi-restaurant-reservation');?>:
	            <span class="redi_required">*</span>
            </label>
	        <br/>
            <input type="text" value="" name="UserEmail" id="UserEmail">
        </div>
        <div>
            <br/>
            <label for="UserComments">
	            <?php _e('Comment', 'redi-restaurant-reservation');?>:
            </label>
	        <br/>
            <textarea rows="2" name="UserComments" id="UserComments" cols="20"></textarea>
        </div>
        <div>
            <br/><br/>
            <input type="submit" id="redi-restaurant-step3" name="Action" value="<?php _e('Make a reservation', 'redi-restaurant-reservation')?>">
	        <img id="step3load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
	        <br/>
        </div>
        <br/>
        <div id="step3errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
    </div>
    <div id="step4" style="display: none;">
        <strong>
			<?php _e('Thank you for your reservation.', 'redi-restaurant-reservation')?><br/>
        </strong>
		<?php _e('We will create a confirmation and email it to you at the email address you entered on the reservations form. You should receive your confirmation by email shortly.', 'redi-restaurant-reservation');?>
    </div>
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
<!--{version:"<? echo $this->version?>"}-->
<form id="redi-reservation" name="redi-reservation" method="post">
    <div id="step1">
        <h2> <?php _e('Step', 'redi-restaurant-reservation')?> 1: <?php _e('Select date and time', 'redi-restaurant-reservation')?></h2>
        <br/><label for="startDate"><?php _e('Date and time', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>
        <input type="text" value="<?php echo $startDate ?>" name="startDate" id="startDate"/>
        <input id="startTime" type="text" value="<?php echo $startTime?>" name="startTime"/><br/>
        <br/><label for="persons"><?php _e('Persons', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>

        <select name="persons" id="persons">
			<?php for ($i = 1; $i != 11; $i++): ?>
            <option value="<?php echo $i?>" <?php if ($persons == $i) echo 'selected="selected"';?> >
	            <?php echo $i ?>
            </option>
			<?php endfor?>
        </select>

        <div style="margin-top: 30px; margin-bottom: 30px;">
            <input id="step1button" type="submit" value="<?php _e('Check available time', 'redi-restaurant-reservation');?>" name="submit"><img
                id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif"
                alt=""/>
        </div>

        <div id="step1errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
    </div>
    <br/>

    <div id="step2" style="display: none;">
        <h2><?php _e('Step', 'redi-restaurant-reservation')?> 2: <?php _e('Select available time', 'redi-restaurant-reservation')?></h2>

        <div id="buttons">
        </div>
        <input type="hidden" id="startTime1" value=""/>
    </div>
    <br/>

    <div id="step3" style="display: none;">
        <h2><?php _e('Step', 'redi-restaurant-reservation')?> 3: <?php _e('Provide reservation details', 'redi-restaurant-reservation')?></h2>

        <div>
            <br/>
            <label for="UserName"><?php _e('Name', 'redi-restaurant-reservation');?>:<span class="redi_required">*</span></label><br/>
            <input type="text" value="" name="UserName" id="UserName">
        </div>
        <div>
            <br/><label for="UserPhone"><?php _e('Phone', 'redi-restaurant-reservation');?>:<span class="redi_required">*</span></label><br/>
            <input type="text" value="" name="UserPhone" id="UserPhone">
        </div>
        <div>
            <br/>
            <label for="UserEmail"><?php _e('Email', 'redi-restaurant-reservation');?>:<span class="redi_required">*</span></label><br/>
            <input type="text" value="" name="UserEmail" id="UserEmail">
        </div>
        <div>
            <br/>
            <label for="UserComments"><?php _e('Comment', 'redi-restaurant-reservation');?>:</label><br/>
            <textarea rows="2" name="UserComments" id="UserComments" cols="20"></textarea>
        </div>
        <div>
            <br/><br/>
            <input type="submit" id="redi-restaurant-step3" name="Action"
                   value="<?php _e('Make a reservation', 'redi-restaurant-reservation')?>"><img id="step3load" style="display: none;"
                                                                    src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif"
                                                                    alt=""/><br/>
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
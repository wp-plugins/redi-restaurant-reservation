<!--{version:"<? echo $this->version?>"}-->
<form id="redi-reservation" name="redi-reservation" method="post">
    <div id="step1">
        <h2> <?php echo __('Step')?> 1: <?php echo __('Select date and time')?></h2>
        <label for="startDate"><?php echo __('Date and time:')?></label><br/>
        <input type="text" value="<?php echo $startDate ?>" name="startDate" id="startDate"/>
        <input id="startTime" type="text" value="<?php echo $startTime?>" name="startTime"/><br/>
        <label for="persons"><?php echo __('Persons:')?></label><br/>
        <input type="text" value="<?php echo $persons?>" name="persons" id="persons"/>

        <div style="margin-top: 30px; margin-bottom: 30px;">
            <input id="step1button" type="submit" value="<?php echo __('Check available time');?>" name="submit"><img id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
        </div>

        <div id="step1errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
    </div>
    <br/>
    <div id="step2" style="display: none;">
        <h2><?php echo __('Step')?> 2: <?php echo __('Select available time')?></h2>
        <div id="buttons">
        </div>
	    <input type="hidden" id="startTime1" value=""/>
    </div>
    <br/>

    <div id="step3" style="display: none;">
        <h2><?php echo __('Step')?> 3: <?php echo __('Provide reservation details')?></h2>
        <div>
            <label for="UserName"><?php echo __('Name');?></label> <span class="redi_required">*</span><br>
            <input type="text" value="" name="UserName" id="UserName">
        </div>
        <div>
            <label for="UserPhone"><?php echo __('Phone');?></label> <span class="redi_required">*</span><br>
            <input type="text" value="" name="UserPhone" id="UserPhone">
        </div>
        <div>
            <label for="UserEmail"><?php echo __('Email');?></label> <span class="redi_required">*</span><br>
            <input type="text" value="" name="UserEmail" id="UserEmail">
        </div>
        <div>
            <label for="UserComments"><?php echo __('Comment');?></label><br>
            <textarea rows="2" name="UserComments" id="UserComments" cols="20"></textarea>
        </div>
        <div>
            <input type="submit" id="redi-restaurant-step3" name="Action" value="<?php echo __('Make reservation')?>"><img id="step3load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/><br/>
        </div>

        <div id="step3errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
    </div>

    <div id="step4" style="display: none;">
		<strong>
			<?php echo __('Thank you for your reservation. We will create a confirmation and email it to you at the email address you entered on the reservations form. You should receive your confirmation by email shortly.');?>
		</strong>
    </div>
</form>
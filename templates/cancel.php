<div id="cancel-reservation-div" style="display: none">

	<h2 style="float:left;"> <?php _e('Cancel reservation', 'redi-restaurant-reservation')?></h2><a href="#cancel" id="back-to-reservation" class="cancel-reservation"><?php _e('Back to reservation page', 'redi-restaurant-reservation')?></a>

	<br clear="both"/>
	<div id="cancel-reservation-form">
		<label for="redi-restaurant-cancelID"><?php _e('Reservation number', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>
		<br clear="both"/>
		<input type="text" name="cancelID" id="redi-restaurant-cancelID"/>
		<br clear="both"/>
		<br clear="both"/>
		<label for="redi-restaurant-cancelEmail"><?php _e('Email', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>
		<br clear="both"/>
		<input type="text" name="cancelEmail" id="redi-restaurant-cancelEmail"/>
		<br clear="both"/>
		<br clear="both"/>
		<label for="redi-restaurant-cancelReason"><?php _e('Reason', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>
		<br clear="both"/>
		<textarea maxlength="250" rows="2" name="cancelEmail" id="redi-restaurant-cancelReason" cols="20"></textarea>
		<br clear="both"/><br clear="both"/>
		<input class="redi-restaurant-button" type="submit" id="redi-restaurant-cancel" name="Action" value="<?php _e('Cancel a reservation', 'redi-restaurant-reservation')?>"  style="border-radius: 31px;float: left;font-size: 20px;font-weight: normal;padding: 13px;width: 97%;margin-top:0;"> 
		<img id="cancel-load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
	</div>
	<br clear="both"/>
	<br clear="both"/>
	<div id="cancel-errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
	<div id="cancel-success" style="display: none;" class="redi-reservation-alert-success redi-reservation-alert">
		<strong>
			<?php _e( 'Reservation has been successfully canceled.', 'redi-restaurant-reservation' ); ?><br clear="both"/>
		</strong>
	</div>
</div>

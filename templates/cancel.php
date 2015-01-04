<div id="cancel-reservation-div" style="display: none">

	<h2 style="float:left;"> <?php _e('Cancel reservation', 'redi-restaurant-reservation')?></h2><a href="#cancel" id="back-to-reservation" class="cancel-reservation redi-restaurant-button"><?php _e('Back to reservation page', 'redi-restaurant-reservation')?></a>

	<br clear="both"/>
	<div id="cancel-reservation-form">
		<form method="post" action="?jquery_fail=true">
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
			<input class="redi-restaurant-button" type="submit" id="redi-restaurant-cancel" name="action" value="<?php _e('Cancel reservation', 'redi-restaurant-reservation')?>">
			<img id="cancel-load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
		</form>
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

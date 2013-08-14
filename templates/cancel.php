<div class="wrap">
	<div id="icon-admin" class="icon32">
		<br>
	</div>
	<h2><?php _e('Cancel reservation', 'redi-restaurant-reservation'); ?></h2>
	<form id="redi-reservation-cancel" name="redi-reservation-cancel" method="post">
		<input type="hidden" name="action" value="cancel"/>
		<br/>
		<label for="redi-restaurant-cancel-id"><?php _e('Reservation number', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>
		<input type="text" value="" name="id" id="redi-restaurant-cancel-id"/>
		<br/>
		<label for="redi-restaurant-cancel-reason"><?php _e('Reason', 'redi-restaurant-reservation')?>:</label><br/>
		<textarea name="reason" id="redi-restaurant-cancel-reason" rows="5" cols="60"></textarea>
		<br/>
		<input type="submit" name="cancelReservation" value="<?php _e('Cancel reservation', 'redi-restaurant-reservation')?>">
	</form>
</div>
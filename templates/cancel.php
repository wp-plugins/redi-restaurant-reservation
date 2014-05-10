<div id="cancel-reservation-div" style="display: none">
	<a href="#cancel" id="back-to-reservation" class="cancel-reservation"><?php _e('Back to reservation page', 'redi-restaurant-reservation')?></a>
	<h2> <?php _e('Cancel reservation', 'redi-restaurant-reservation')?></h2>
	<br clear="both"/>
	<label for="redi-restaurant-cancelReservationID"><?php _e('Reservation number', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label>
	<br clear="both"/>
	<input type="text" name="cancelReservationID" id="redi-restaurant-cancelReservationID"/>
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
	<input type="submit" id="redi-restaurant-cancel" name="Action" value="<?php _e('Cancel a reservation', 'redi-restaurant-reservation')?>">
	<br clear="both"/>
	<br clear="both"/>
	<div id="cancel-errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
</div>
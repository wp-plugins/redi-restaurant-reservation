<!-- ReDi restaurant reservation plugin version <?php echo $this->version?> -->
<?php require_once(REDI_RESTAURANT_TEMPLATE.'cancel.php');?>
<script type="text/javascript">var date_format = '<?php echo $calendar_date_format ?>';<?php $time_format_s = explode(':', $time_format);if(isset($time_format_s[0]) && in_array($time_format_s[0], array('g','h'))):?>var time_format = 'h:mm tt';<?php else: ?>var time_format = 'HH:mm';<?php endif ?>var locale = '<?php echo get_locale()?>';if ((/^en/).test(locale))locale = ''; var timeshiftmode = '<?php echo $timeshiftmode; ?>'; var hidesteps = <?php echo $hidesteps ? 1 : 0; ?>; var apikeyid = '<?php echo $apiKeyId; ?>';</script>
<form id="redi-reservation" name="redi-reservation" method="post">
	<div id="step1">

		<?php if ( count( (array) $places ) > 1 ): /* multiple places */ ?>
			<h2 style="float:left;">
				<?php _e( 'Step', 'redi-restaurant-reservation' ) ?> 1: <?php _e( 'Select place, date and time', 'redi-restaurant-reservation' ) ?>
			</h2>
			<a href="#cancel" id="cancel-reservation" class="cancel-reservation"><?php _e('Cancel reservation', 'redi-restaurant-reservation')?></a>
			<br clear="both"/>
			<label for="placeID">
				<?php _e( 'Place', 'redi-restaurant-reservation' ) ?>:</label>
			<br clear="both"/>
			<select name="placeID" id="placeID" class="redi-reservation-select">
				<?php foreach ( (array) $places as $place_current ): ?>
					<option value="<?php echo $place_current->ID ?>">
						<?php echo $place_current->Name ?>
					</option>
				<?php endforeach; ?>
			</select>
			<br clear="both"/>
		<?php else: /* only one place */ ?>
			<h2 style="float:left;">
				<?php _e( 'Step', 'redi-restaurant-reservation' ) ?> 1: <?php _e( 'Select date and time', 'redi-restaurant-reservation' ) ?>

			</h2>
			<a href="#cancel" id="cancel-reservation" class="cancel-reservation"><?php _e('Cancel reservation', 'redi-restaurant-reservation')?></a>
			<input type="hidden" id="placeID" name="placeID" value="<?php echo $places[0]->ID ?>"/>
		<?php endif ?>
		<br clear="both"/>
		<label for="redi-restaurant-startDate"><?php _e('Date', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br clear="both"/>
		<?php if($calendar === 'show'): ?>
			<div id="redi-restaurant-startDate"></div>
		<?php else: ?>
			<input type="text" value="<?php echo $startDate ?>" name="startDate" id="redi-restaurant-startDate"/>
		<?php endif ?>

		<input id="redi-restaurant-startDateISO" type="hidden" value="<?php echo $startDateISO ?>" name="startDateISO"/>


		<?php if(!$hide_clock):?>
			<br clear="both"/>
			<br clear="both"/><label for="redi-restaurant-startHour"><?php _e('Time', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br clear="both"/>

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
				<input id="redi-restaurant-startTime" type="hidden" value="<?php echo date_i18n($time_format, $startTime);?>" name="startTime"/><br clear="both"/>
			<?php else:?>
				<input id="redi-restaurant-startTime" type="text" value="<?php echo date_i18n($time_format, $startTime);?>" name="startTime"/><br clear="both"/>
			<?php endif ?>
		<?php endif;?>

		<?php if(isset($start_time_array)):?>
			<input id="redi-restaurant-startTimeArray" type="hidden" name="StartTimeArray" value="<?php echo $start_time_array; ?>" />
		<?php endif;?>
		<br clear="both"/>

        <label for="persons">
		<?php _e('Persons', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br clear="both"/>

		<select name="persons" id="persons" class="redi-reservation-select">
			<?php for ($i = $minPersons; $i != $maxPersons+1; $i++): ?>
				<option value="<?php echo $i?>" <?php if ($persons == $i) echo 'selected="selected"';?> >
					<?php echo $i ?>
				</option>
			<?php endfor?>
            <?php if (!empty($largeGroupsMessage)):?>
                <option value="group" >
                    <?php echo sprintf( __( 'More than %s people', 'redi-restaurant-reservation' ), $maxPersons );?>
                </option>
            <?php endif ?>
		</select>
		<?php if ( $timeshiftmode === 'byshifts' ): ?>
			<br clear="both"/>

			<span id="step1times">
				<?php if ( $hidesteps ): ?>
			        <br clear="all">
			        <label><?php _e( 'Time', 'redi-restaurant-reservation' ) ?>:</label>
					<br clear="all">
				<?php endif ?>
		        <span id="step1buttons">
			        <?php if ( $hidesteps ):
				        $current = 0;
				        if ( isset( $step1 ) && is_array( $step1 ) && ! isset( $step1['Error'] ) ):
					        $all_busy = true;
					        foreach ( $step1 as $available ): ?>
						        <?php $current_busy = true;
						        if ( isset( $available['Availability'] ) && is_array( $available['Availability'] ) ) {
							        foreach ( $available['Availability'] as $button ) {
								        if ( $button['Available'] ) {
									        $all_busy = $current_busy = false;
								        }
							        }
						        }
						        if ( isset( $available['Name'] ) ): ?>
							        <input class="redi-restaurant-button button available" type="submit"
							               id="time_<?php echo $current ++; ?>" value="<?php echo( $available['Name'] ); ?>"
							               <?php if ($current_busy): ?>disabled="disabled"<?php endif ?>/>
						        <?php endif ?>
					        <?php endforeach ?>
				        <?php endif ?>

			        <?php endif ?>
		        </span>
	        </span>
		<?php else: /* byshifts end */?>
			<?php $all_busy = FALSE; ?>
	        <div style="margin-top: 30px;">
		        <?php if($timeshiftmode != 'byshifts'):?>
			        <input class="redi-restaurant-button" id="step1button" type="submit" value="<?php _e('Check available time', 'redi-restaurant-reservation');?>" name="submit">
		        <?php endif?>
		    </div>
		<?php endif /* normal */ ?>
		<br clear="both"/><br clear="both"/>

		<div id="step1busy" <?php if(!$all_busy):?>style="display: none;"<?php endif; ?> class="redi-reservation-alert-error redi-reservation-alert">
			<?php _e('Reservation is not available on selected day. Please select another day.', 'redi-restaurant-reservation');?>
		</div>

		<div id="large_groups_message" style="display: none;margin-top: 30px;" class="redi-reservation-alert-info redi-reservation-alert"><?php echo $largeGroupsMessage?></div>
		<div>
			<img id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
		</div>

		<div id="step1errors" <?php if (!isset($step1['Error'])):?>style="display: none;"<?php endif;?> class="redi-reservation-alert-error redi-reservation-alert">
			<?php if (isset($step1['Error'])):?>
				<?php echo $step1['Error'];?><br clear="both"/>
			<?php endif;?>
		</div>
	</div>


	<div id="step2" <?php if ($timeshiftmode !=='byshifts' || $hidesteps): ?>style="display: none" <?php endif ?>>

		<?php if ( $timeshiftmode !=='byshifts' ||$hidesteps ): ?>
			<h2 style="float:left;">
				<?php _e('Step', 'redi-restaurant-reservation')?> 2: <?php _e('Select available time', 'redi-restaurant-reservation')?>
			</h2>
			<br clear="both"/>
		<?php endif ?>
		<span id="time2label" style="display: none"><?php _e('Time', 'redi-restaurant-reservation')?>:</label><br clear="both"/></span>
		<div id="buttons">

			<?php if ( isset( $step1 ) && is_array($step1)&& !isset($step1['Error'] )): ?>
				<?php $current = 0;?>
				<?php foreach ( $step1 as $available ): ?>
					<?php if ( isset( $available['Name'] ) ): ?>

						<?php if ( !$hidesteps ): ?>

							<?php echo( $available['Name'] ); ?>:</br>
						<?php endif ?>
					<?php endif ?>
					<?php if ( $hidesteps ): ?>
						<span id="opentime_<?php echo( $current++ ); ?>" style="display: none">
						<label><?php _e('Time', 'redi-restaurant-reservation')?>:</label><br clear="both"/>
					<?php endif ?>
					<?php if ( isset( $available['Availability'] ) && is_array($available['Availability']) ): ?>
						<?php $all_busy = true; ?>

                        <?php foreach ( $available['Availability'] as $button ): ?><button <?php if(!$button['Available']):?>disabled="disabled"<?php endif?> class="redi-restaurant-time-button button" value="<?php echo $button['StartTimeISO'] ?>"><?php echo $button['StartTime'] ?></button>

							<?php if($button['Available']) $all_busy = false; ?>

						<?php endforeach; ?>

						</br>
					<?php endif; ?>
					</br>
					<?php if ( $hidesteps ): ?>
						</span>
					<?php endif ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<input type="hidden" id="redi-restaurant-startTimeHidden" value=""/>
        <img id="step2load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
        <div id="step2errors" style="display: none;" class="redi-reservation-alert-error redi-reservation-alert"></div>
		<div id="step2busy" <?php if(!$all_busy):?>style="display: none;"<?php endif; ?> class="redi-reservation-alert-error redi-reservation-alert">
			<?php _e('Reservation is not available on selected day. Please select another day.', 'redi-restaurant-reservation');?>
		</div>
		<?php if ($hidesteps):?>
			<input class="redi-restaurant-button button" type="submit" id="step2prev" value="<?php _e('Previous', 'redi-restaurant-reservation')?>">
		<?php endif ?>
	</div>
	<br clear="both"/>

	<div id="step3" style="display: none;">

		<h2 style="float:left;">
			<?php _e( 'Step', 'redi-restaurant-reservation' ) ?> <?php echo ( $hidesteps ) ? 3 : 2 ?>: <?php _e( 'Provide reservation details', 'redi-restaurant-reservation' ) ?>
		</h2>

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
		<!-- custom fields -->
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
							<input type="hidden" id="<?php echo 'field_'.$i.'_message'; ?>" value="<?php echo !empty($$field_message) ? $$field_message : _e('Custom field is required', 'redi-restaurant-reservation');?>">
						<?php endif;?>
					</label>
					<br clear="both"/>

					<input type="<?php echo($$field_type);?>" value="" name="field_<?php echo $i; ?>" id="field_<?php echo $i; ?>" <?php if(isset($$field_required) && $$field_required):?>class="field_required"<?php endif; ?>>
				</div>
			<?php endif;?>
		<?php endfor;?>
		<!-- /custom fields -->
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
			<?php if ($hidesteps):?>
				<input class="redi-restaurant-button button" type="submit" id="step3prev" value="<?php _e('Previous', 'redi-restaurant-reservation')?>">
			<?php endif ?>
			<input class="redi-restaurant-button button" type="submit" id="redi-restaurant-step3" name="Action" value="<?php _e('Make a reservation', 'redi-restaurant-reservation')?>">
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
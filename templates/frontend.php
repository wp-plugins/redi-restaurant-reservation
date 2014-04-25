<!-- ReDi restaurant reservation plugin version <?php echo $this->version?> -->
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
		
        <?php if(count((array)$places) > 1 ): ?>
            <h2> <?php _e('Step', 'redi-restaurant-reservation')?> 1: <?php _e('Select place, date and time', 'redi-restaurant-reservation')?></h2>
        <br/><label for="placeID"><?php _e('Place', 'redi-restaurant-reservation')?>:</label><br/>
		 <select name="placeID" id="placeID" class="redi-reservation-select">
			<?php foreach((array)$places as $place_current):?>
				<option value="<?php echo $place_current->ID ?>">
					<?php echo $place_current->Name ?>
				</option>
			<?php endforeach; ?>
		 </select>
         <br/>
         <?php else: ?>
         <h2> <?php _e('Step', 'redi-restaurant-reservation')?> 1: <?php _e('Select date', 'redi-restaurant-reservation')?></h2>
            <input type="hidden" id="placeID" name="placeID" value="<?php echo $places[0]->ID ?>"/>
         <?php endif ?>
		<br/><label for="redi-restaurant-startDate"><?php _e('Date', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>
		<input type="text" value="<?php echo $startDate ?>" name="startDate" id="redi-restaurant-startDate"/>
		<input id="redi-restaurant-startDateISO" type="hidden" value="<?php echo $startDateISO ?>" name="startDateISO"/>
		<?php if(!$hide_clock):?>
			<input id="redi-restaurant-startTime" type="text" value="<?php echo date_i18n($time_format, $startTime);?>" name="startTime"/><br/>
		<?php endif;?>

		<?php if(isset($start_time_array)):?>
			<input id="redi-restaurant-startTimeArray" type="hidden" name="StartTimeArray" value="<?php echo $start_time_array; ?>" />
		<?php endif;?>
		<br/>
        <br/>
        <label for="persons"><?php _e('Persons', 'redi-restaurant-reservation')?>:<span class="redi_required">*</span></label><br/>

		<select name="persons" id="persons" class="redi-reservation-select">
			<?php for ($i = 1; $i != $maxPersons+1; $i++): ?>
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

        <div id="large_groups_message" style="display: none;margin-top: 30px;" class="redi-reservation-alert-info redi-reservation-alert"><?php echo $largeGroupsMessage?></div>
		<div style="margin-top: 30px; margin-bottom: 30px;">
<!--			<input id="step1button" type="submit" value="--><?php //_e('Check available time', 'redi-restaurant-reservation');?><!--" name="submit">-->
			<img id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
		</div>

		<div id="step1errors" <?php if (!isset($step1['Error'])):?>style="display: none;"<?php endif;?> class="redi-reservation-alert-error redi-reservation-alert">
			<?php if (isset($step1['Error'])):?>
				<?php echo $step1['Error'];?><br/>
			<?php endif;?>
		</div>
	</div>
<!--	<br/>-->

	<div id="step2">
<!--		<h2>--><?php //_e('Step', 'redi-restaurant-reservation')?><!-- 2: --><?php //_e('Select available time', 'redi-restaurant-reservation')?><!--</h2>-->
		<div id="buttons">
			<?php if ( isset( $step1 ) ): ?>
				<?php foreach ( $step1 as $available ): ?>
					<?php if ( isset( $available['Name'] ) ): ?>
						<?php echo( $available['Name'] ); ?>:</br>
					<?php endif ?>
					<?php if ( isset( $available['Availability'] ) ): ?>
						<?php foreach ( $available['Availability'] as $button ): ?><button class="redi-restaurant-button" value="<?php echo $button['StartTimeISO'] ?>"><?php echo $button['StartTime'] ?></button><?php endforeach; ?>
						</br>
					<?php endif; ?>
					</br>
				<?php endforeach; ?>

			<?php endif; ?>
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
		<!-- custom fields -->
		<?php for($i=1; $i!=6; $i++):?>
			<?php $field_name = 'field_'.$i.'_name'; ?>
			<?php $field_required = 'field_'.$i.'_required'; ?>
			<?php $field_type = 'field_'.$i.'_type'; ?>
			<?php $field_message = 'field_'.$i.'_message'; ?>

			<?php if(isset($$field_name) && !empty($$field_name)):?>
				<div>
					<br/>
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
					<br/>

					<input type="<?php echo($$field_type);?>" value="" name="field_<?php echo $i; ?>" id="field_<?php echo $i; ?>" <?php if(isset($$field_required) && $$field_required):?>class="field_required"<?php endif; ?>>
				</div>
			<?php endif;?>
		<?php endfor;?>
		<!-- /custom fields -->
		<div>
			<br/>
			<label for="UserComments">
				<?php _e('Comment', 'redi-restaurant-reservation');?>:
			</label>
			<br/>
			<textarea maxlength="250" rows="2" name="UserComments" id="UserComments" cols="20"></textarea>
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
	<div id="step4" style="display: none;" class="redi-reservation-alert-success redi-reservation-alert">
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
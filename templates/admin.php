<style type="text/css">
	.tab_wrap {
		background-color: #FFFFFF;
		border: 1px solid #CCCCCC;
		padding: 10px;
		min-width: 763px;
	}
	.redi_required{
		color: #DD0000;
	}
	.redi-admin-left {
		margin-right: 300px;
		float: left;
	}
	.redi-admin-right {
		position: absolute;
		right: 0;
		width: 290px;
	}
	.postbox h3 {
		font-size: 14px;
		line-height: 1.4;
		margin: 0;
		padding: 8px 12px;
	}
	.nav-tab-basic{
		background-color:#78DD88;
	}
	.nav-tab-basic:hover{
		background-color:#7FFF8E;
	}
</style>
<script type="text/javascript">
	// Include the UserVoice JavaScript SDK (only needed once on a page)
	UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/gDfKlRGSIwZxjtqDE5rg.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();
	UserVoice.push(['set', {locale: '<?php echo get_locale()?>'}]);
	UserVoice.push(['identify', {email:'<?php echo get_option( 'admin_email' );?>',name:'<?php echo get_option( 'blogname' );?>'}]);
	UserVoice.push(['addTrigger', { mode: 'smartvote', trigger_position: 'bottom-right' }]);
</script>

<div class="wrap">
<a class="nav-tab <?php if(!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm']=='free')): ?> nav-tab-active<?php endif;?>"
   href="options-general.php?page=redi-restaurant-reservation&sm=free"><?php _e('Free package settings', 'redi-restaurant-reservation') ?></a>
<a class="nav-tab nav-tab-basic <?php if((isset($_GET['sm']) && $_GET['sm']=='basic')): ?> nav-tab-active<?php endif;?>"
   href="options-general.php?page=redi-restaurant-reservation&sm=basic"><?php _e('Basic package settings', 'redi-restaurant-reservation') ?></a>
<a class="nav-tab <?php if((isset($_GET['sm']) && $_GET['sm']=='cancel')): ?> nav-tab-active<?php endif;?>"
   href="options-general.php?page=redi-restaurant-reservation&sm=cancel"><?php _e('Cancel reservation', 'redi-restaurant-reservation') ?></a>
	<?php if(!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm']=='free')): ?>
		<div class="redi-admin-right" >

			<div class="postbox">
				<h3><?php _e('Plugin Info', 'redi-restaurant-reservation') ?></h3>
				<div class="inside">
					<p><?php _e('Name', 'redi-restaurant-reservation') ?>: Redi Restaurant Reservation</p>
					<p><?php _e('Version', 'redi-restaurant-reservation') ?>: <?php echo $this->version ?></p>
					<p><?php _e('Authors', 'redi-restaurant-reservation') ?>: <a href="https://profiles.wordpress.org/thecatkin/" target="_blank">Catkin</a> & <a href="https://profiles.wordpress.org/robbyroboter/" target="_blank">Robby Roboter</a></p>
					<p><?php _e('Email', 'redi-restaurant-reservation') ?>: <a target="_blank" href="mailto:info@reservationdiary.eu">info@reservationdiary.eu</a></p>
				</div>
			</div>
		</div>
	<?php endif ?>
<div class="tab_wrap <?php if(!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm']=='free')): ?>redi-admin-left<?php endif ?>">

	<?php if (isset($settings_saved) && $settings_saved): ?>
		<div class="updated" id="message">
			<p>
				<?php _e('Your settings have been saved!', 'redi-restaurant-reservation') ?>
			</p>
		</div>
	<?php endif ?>
	<?php if ( isset( $cancel_success ) ): ?>
		<div class="updated">
			<p>
				<?php echo $cancel_success; ?>
			</p>
		</div>
	<?php endif; ?>
	<?php if (isset($errors)): ?>
		<?php foreach((array)$errors as $error):?>
		<div class="error">
			<p>
				<?php echo $error; ?>
			</p>
		</div>
		<?php endforeach;?>
	<?php endif ?>
	<?php if(!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm']=='free')): ?>

	<div class="icon32" id="icon-admin"><br></div>
	<h2><?php _e('Common settings', 'redi-restaurant-reservation'); ?></h2>
	<form name="redi-restaurant" method="post">
		<table class="form-table" >
			<tr valign="top">
				<th scope="row" style="width:25%;">
					<label for="MinPersons"><?php _e('Min persons per reservation', 'redi-restaurant-reservation'); ?> </label>
				</th>
				<td style="vertical-align: top;">
					<select name="MinPersons" id="MinPersons">
						<?php foreach(range(1, 10) as $current):?>
							<option value="<?php echo $current?>" <?php if($current == $minPersons): ?>selected="selected"<?php endif;?>>
								<?php echo $current ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
                <td style="width:75%">
                    <p class="description">
                        <?php _e('Minimum number of persons allowed for each reservation. Drop down list of persons starts from this number.', 'redi-restaurant-reservation') ?>
                    </p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:15%;">
					<label for="MaxPersons"><?php _e('Max persons per reservation', 'redi-restaurant-reservation'); ?> </label>
				</th>
				<td style="vertical-align: top;">
				<select name="MaxPersons" id="MaxPersons">
					<?php foreach(range(1, 100) as $current):?>
						<option value="<?php echo $current?>" <?php if($current == $maxPersons): ?>selected="selected"<?php endif;?>>
							<?php echo $current ?>
						</option>
					<?php endforeach; ?>
				</select>
				</td>
                <td style="width:75%">
                    <p class="description">
                        <?php _e('Maximum number of persons allowed for each reservation. Drop down list of persons ends with this number.', 'redi-restaurant-reservation') ?>
                    </p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="ReservationTime">
						<?php _e('Reservation time', 'redi-restaurant-reservation'); ?>&nbsp;<span class="redi_required">*</span>
					</label>
				</th>
				<td style="vertical-align: top;">
					<input id="ReservationTime" type="text" value="<?php echo $reservationTime ?>" name="ReservationTime"/>
				</td>
				<td style="width:75%">
					<p class="description">
						<?php _e('Duration of reservation in minutes. This is the time allocated for each reservation.', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
            <tr>
                <th scope="row">
                    <label for="AlternativeTimeStep">
                        <?php _e('Alternative time step', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td style="vertical-align: top;">
                    <select name="AlternativeTimeStep">
                        <option value="15" <?php if ($alternativeTimeStep == 15):?>selected="selected" <?php endif;?>><?php printf(__('%d min', 'redi-restaurant-reservation'), 15);?></option>
                        <option value="30" <?php if ($alternativeTimeStep == 30):?>selected="selected" <?php endif;?>><?php printf(__('%d min', 'redi-restaurant-reservation'), 30);?></option>
                        <option value="60" <?php if ($alternativeTimeStep == 60):?>selected="selected" <?php endif;?>><?php printf(__('%d min', 'redi-restaurant-reservation'), 60);?></option>
                    </select>
                </td>
                <td style="width:75%">
                    <p class="description">
                        <?php _e('Displays the available time with time step to the clients. For instance, if one selects 15 min time step, then alternative time will be 10:00, 10:15, 10:30, etc.', 'redi-restaurant-reservation') ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="EmailFrom">
                        <?php _e('Send confirmation email to client', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td style="vertical-align: top;">
                    <select name="EmailFrom">
                        <option value="ReDi" <?php if ($emailFrom == EmailFrom::ReDi):?>selected="selected" <?php endif;?>><?php _e('From ReservationDiary.eu', 'redi-restaurant-reservation'); ?></option>
                        <option value="WordPress" <?php if ($emailFrom == EmailFrom::WordPress):?>selected="selected" <?php endif;?>><?php _e('From wordpress email account', 'redi-restaurant-reservation'); ?></option>
                        <option value="Disabled" <?php if ($emailFrom == EmailFrom::Disabled):?>selected="selected" <?php endif;?>><?php _e('Disable confirmation email', 'redi-restaurant-reservation'); ?></option>
                    </select>
                </td>
                <td style="width:75%">
                    <p class="description">
                        <?php _e('The way you want confirmation email to be delivered to the client. It can be "From WordPress email account", "From reservationdiary.eu" or "Disable confirmation email".', 'redi-restaurant-reservation') ?><br/>
                        <b><?php _e('From WordPress email account', 'redi-restaurant-reservation') ?></b> - <?php _e('the confirmation email will be sent out from your email set in WordPress. Sending from WordPress account setting will work only for Basic package clients.', 'redi-restaurant-reservation') ?><br/>
	                    <b><?php _e('From reservationdiary.eu', 'redi-restaurant-reservation') ?></b> - <?php _e('the confirmation email will be sent out from info@reservationdiary.eu. When the client replies to confirmation email, you will receive it.', 'redi-restaurant-reservation') ?><br/>
		                <b><?php _e('Disable confirmation email', 'redi-restaurant-reservation') ?></b> - <?php _e('With this option, confirmation email is not sent to the client.', 'redi-restaurant-reservation') ?>
                    </p>
                </td>
            </tr>
			<tr>
				<th scope="row">
					<label for="MaxTime">
						<?php _e('Max time before reservations', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<select name="MaxTime">
						<?php foreach(range(1, 12) as $current):?>
							<option value="<?php echo $current?>" <?php if($current == $maxTime): ?>selected="selected"<?php endif;?>>
								<?php echo $current ?> <?php echo _n( 'Month', 'Months', $current, 'redi-restaurant-reservation' )?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
                <td style="width:75%">
                    <p class="description">
                        <?php _e('Maximum time before the reservation is accepted. It can be anything from 1 month to 1 year.', 'redi-restaurant-reservation') ?>
                    </p>
                </td>
			</tr>
		</table>
		<br/>
		<div class="icon32" id="icon-admin"><br></div>
		<h2><?php _e('Frontend settings', 'redi-restaurant-reservation'); ?></h2>
		<table class="form-table">
			<tr>
				<th scope="row" style="width:25%;">
					<label for="LargeGroupsMessage">
						<?php _e('Message for large groups', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<textarea maxlength="250" name="LargeGroupsMessage" id="LargeGroupsMessage" rows="5" cols="40"><?php echo $largeGroupsMessage ?></textarea>
				</td>
				<td style="width:75%; vertical-align: top;">
					<p class="description">
						<?php _e(' If this field is filled, the drop down menu of persons would show "Large Groups" and upon selection, the specified message would appear.', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
			<tr style="width: 250px">
				<th scope="row">
					<label for="TimeShiftMode">
						<?php _e('TimeShift Mode', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<select name="TimeShiftMode">
						<option value="normal" <?php if ($timeshiftmode === 'normal'):?>selected="selected"<?php endif;?>>normal</option>
						<option value="byshifts" <?php if ($timeshiftmode === 'byshifts'):?>selected="selected"<?php endif;?>>byshifts</option>
					</select>
				</td>
				<td style="width:75%">
					<p class="description">
						<?php _e('Mode how available working hours presented to user so that they can choose time slots most convenient to them.', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('Normal', 'redi-restaurant-reservation'); ?></b> – <?php _e('In this mode, the user selects the desired time and the system verifies its availability to present five different alternative times to the customer.', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('By shifts', 'redi-restaurant-reservation'); ?></b> – <?php _e('In this mode, the system automatically displays all the available times for the date selected without any manual time input.', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('Hide steps', 'redi-restaurant-reservation'); ?></b> - <?php _e('This option is meant only for the by shifts mode. It is meant for hiding the previous steps. The system would display all the available times for the specified date but upon selecting the available time, the previous steps are hidden and the next step is presented. It is a good mode for widgets specially.', 'redi-restaurant-reservation'); ?><br/>

					</p>
				</td>
			</tr>
			<tr style="width: 250px">
				<th scope="row">
					<label for="Hidesteps">
						<?php _e('Hidesteps', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<select name="Hidesteps">
						<option value="true" <?php if ($hidesteps === 'true'):?>selected="selected"<?php endif;?>>true</option>
						<option value="false" <?php if ($hidesteps === 'false'):?>selected="selected"<?php endif;?>>false</option>
					</select>
				</td>
				<td style="width:75%">
					<p class="description">
						<?php _e('Hide previous steps (only for timeshiftmode byshifts)', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
			<tr style="width: 250px">
				<th scope="row">
					<label for="Calendar">
						<?php _e('Calendar type', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<select name="Calendar">
						<option value="show" <?php if ($calendar === 'show'):?>selected="selected"<?php endif;?>>Always show</option>
						<option value="hide" <?php if ($calendar === 'hide'):?>selected="selected"<?php endif;?>>Shown on click</option>
					</select>
				</td>
				<td style="width:75%">
					<p class="description">
						<?php _e('This field lets you select the style in which the calendar control is displayed. It can be either "Show on click" or "Always show”.', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('Shown on click', 'redi-restaurant-reservation'); ?></b> – <?php _e('Selecting this option, the calendar is set to popup when the user clicks the calendar control.', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('Always show', 'redi-restaurant-reservation'); ?></b> – <?php _e('This option sets the calendar control to display all the time.', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="TimePicker">
						<?php _e('TimePicker type', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<select name="TimePicker">
						<option value="plugin" <?php if ($timepicker === 'plugin'):?>selected="selected" <?php endif;?>>jQuery plugin</option>
						<option value="dropdown" <?php if ($timepicker === 'dropdown'):?>selected="selected" <?php endif;?>>dropdown</option>
					</select>
				</td>
				<td style="width:75%">
					<p class="description">
						<?php _e('This field lets you select the way time picker is displayed. You can choose from "jQuery plugin" and "HTML dropdown".', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('jQuery plugin', 'redi-restaurant-reservation'); ?></b> – <?php _e('Time picker type if selected to be jQuery plugin, it is set to pop up with the hour and time.', 'redi-restaurant-reservation'); ?><br/>
						<b><?php _e('HTML dropdown', 'redi-restaurant-reservation'); ?></b> – <?php _e('With this option selected, the Time Picker is shown to be simple dropdown for selecting the hour and time.', 'redi-restaurant-reservation'); ?>

					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="Thanks">
						<?php _e('Support us', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td style="vertical-align: top;">
					<input type="checkbox" name="Thanks" id="Thanks" value="1" <?php if (isset($thanks) && $thanks)  echo 'checked="checked"' ?>>
				</td>
				<td style="width:75%">
					<p class="description">
						<?php _e('If checkbox is checked, a logo of <b>"Powered by ReDi"</b> is displayed. Thank you for supporting us.', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
		</table>

		<!-- custom fields-->
		<div class="icon32" id="icon-edit-comments"><br></div>
		<h2><?php _e('Custom fields', 'redi-restaurant-reservation'); ?></h2>
        <p class="description">
			<?php _e('Custom fields are meant to allow users define additional fields for collecting more information from customers. You can choose the name of the field, type of field, the constraint whether it is a required field or not and the error message for the required field.', 'redi-restaurant-reservation') ?><br/>
			<b style="color: red"><?php _e('NOTE: Name, Email and Phone are required fields of reservation form and do not need to be defined here as custom fields.', 'redi-restaurant-reservation') ?></b>
		</p>

		<table class="form-table" style="width: 90%; text-align: center;">
			<thead>
				<tr>
					<th>
						<label>
							<?php _e('Field name', 'redi-restaurant-reservation'); ?>
						</label>
					</th>
					<th>
						<label>
							<?php _e('Field type', 'redi-restaurant-reservation'); ?>
						</label>
					</th>
					<th>
						<label>
							<?php _e('Is required?', 'redi-restaurant-reservation'); ?>
						</label>
					</th>
					<th>
						<label>
							<?php _e('Required error message', 'redi-restaurant-reservation'); ?>
						</label>
					</th>
				</tr>
			</thead>
			<?php for($i = 1; $i != CUSTOM_FIELDS; $i++):?>
			<tr>
				<td>
					<?php $field_name=('field_'.$i.'_name'); ?>
					<input type="text" name="<?php echo $field_name; ?>" value="<?php echo $$field_name; ?>"/>
				</td>
				<td style="vertical-align: top;">
					<?php $field_type=('field_'.$i.'_type'); ?>
					<select name="<?php echo $field_type; ?>">
						<option value="textbox" <?php if ($$field_type === 'textbox'):?>selected="selected"<?php endif?>>TextBox</option>
						<option value="checkbox" <?php if ($$field_type === 'checkbox'):?>selected="selected"<?php endif?>>Checkbox</option>
					</select>
				</td>
				<td>
					<?php $field_required=('field_'.$i.'_required'); ?>
					<input type="checkbox" name="<?php echo $field_required; ?>" <?php if ($$field_required): ?>checked="checked"<?php endif?>>
				</td>
				<td>
					<?php $field_message=('field_'.$i.'_message'); ?>
					<input type="text" name="<?php echo $field_message;?>" value="<?php echo $$field_message; ?>" style="width:250px;">
				</td>
			</tr>
			<?php endfor; ?>
		</table>
		<br/>
		<!-- /custom fields-->
                
		<div id="ajaxed">
            <?php self::ajaxed_admin_page($placeID, $categoryID, $settings_saved); ?>
		</div>
		
		

		<input class="button-primary" id="submit" type="submit" value="<?php _e( 'Save Changes', 'redi-restaurant-reservation') ?>" name="submit">
	</form>
        <?php elseif((isset($_GET['sm']) && $_GET['sm']=='basic')):?>
            <iframe src="http://wp.reservationdiary.eu/<?php echo str_replace( '_', '-', get_locale() )?>/<?php echo $this->ApiKey; ?>/Home" width="100%;" style="min-height: 1700px;"></iframe>
        <?php elseif((isset($_GET['sm']) && $_GET['sm']=='cancel')):?>
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
		<textarea maxlength="250" name="reason" id="redi-restaurant-cancel-reason" rows="5" cols="60"></textarea>
		<br/>
        <br/>
		<input class="button-secondary" type="submit" name="cancelReservation" value="<?php _e('Cancel reservation', 'redi-restaurant-reservation')?>">
	</form>
	<?php endif ?>
</div>
</div>

<br/>
<br/>
<br/>
<style type="text/css">
	.tab_wrap {
		background-color: #FFFFFF;
		border: 1px solid #CCCCCC;
		padding: 10px;
	}
</style>


<div class="wrap">
<a class="nav-tab <?php if(!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm']=='free')): ?> nav-tab-active<?php endif;?>"
   href="options-general.php?page=options_page_slug&sm=free"><?php _e('Free package settings', 'redi-restaurant-reservation') ?></a>
<a class="nav-tab <?php if((isset($_GET['sm']) && $_GET['sm']=='basic')): ?> nav-tab-active<?php endif;?>"
   href="options-general.php?page=options_page_slug&sm=basic"><?php _e('Basic package settings', 'redi-restaurant-reservation') ?></a>
<a class="nav-tab <?php if((isset($_GET['sm']) && $_GET['sm']=='cancel')): ?> nav-tab-active<?php endif;?>"
   href="options-general.php?page=options_page_slug&sm=cancel"><?php _e('Cancel reservation', 'redi-restaurant-reservation') ?></a>
<div class="tab_wrap">
	<?php if (isset($settings_saved) && $settings_saved): ?>
		<div class="updated" id="message">
			<p>
				<?php _e('Your settings have been saved!', 'redi-restaurant-reservation') ?>
			</p>
		</div>
	<?php endif ?>

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
				<th scope="row" style="width:15%;">
					<label for="MaxPersons"><?php _e('Max persons per reservation', 'redi-restaurant-reservation'); ?> </label>
				</th>
				<td>
					<input id="MaxPersons" type="text" value="<?php echo (int)$maxPersons ?>" name="MaxPersons"/>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="ReservationTime">
						<?php _e('Reservation time', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="ReservationTime" type="text" value="<?php echo $ReservationTime ?>" name="ReservationTime"/>
				</td>
				<td style="width:80%">
					<p class="description">
						<?php _e('Reservation duration in minutes', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="Thanks">
						<?php _e('Support us', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input type="checkbox" name="Thanks" id="Thanks" value="1" <?php if ($thanks) echo 'checked="checked"' ?>>
				</td>
				<td style="width:80%">
					<p class="description">
						<?php _e('Please support our plugin by publishing Reservation Diary logo', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="TimePicker">
						<?php _e('TimePicker type', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<select name="TimePicker">
						<option value="plugin" <?php if ($timepicker === 'plugin'):?>selected="selected" <?php endif;?>>jQuery plugin</option>
						<option value="dropdown" <?php if ($timepicker === 'dropdown'):?>selected="selected" <?php endif;?>>dropdown</option>
					</select>
				</td>
				<td style="width:80%">
					<p class="description">
						<?php _e('jQuery plugin or HTML dropdown', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
		</table>
		<br/>
		
		<!-- custom fields-->
		<div class="icon32" id="icon-edit-comments"><br></div>
		<h2><?php _e('Custom fields', 'redi-restaurant-reservation'); ?></h2>

		<table class="form-table" style="width: 80%;">
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
				<td>
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
					<input type="text" name="<?php echo $field_message;?>" value="<?php echo $$field_message; ?>" style="width:300px;">
				</td>
			</tr>
			<?php endfor; ?>
		</table>
		<br/>
		<!-- /custom fields-->
                
		<div id="ajaxed">
                    <?php self::ajaxed_admin_page($placeID, $categoryID); ?>
		</div>
		
		

		<input class="button-primary" id="submit" type="submit" value="<?php _e( 'Save Changes' );?>" name="submit">
	</form>
        <?php elseif((isset($_GET['sm']) && $_GET['sm']=='basic')):?>
            <iframe src="http://wp.reservationdiary.eu/en-uk/<?php echo $this->ApiKey; ?>/Home" width="100%;" style="min-height: 500px;"></iframe>
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
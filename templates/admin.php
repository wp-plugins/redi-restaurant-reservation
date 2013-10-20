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
<div class="tab_wrap">
	<?php if (isset($settings_saved)): ?>
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
		<table class="form-table" style="width: 25%;">

			<tr valign="top">
				<th scope="row">
					<label for="services"><?php _e('Available seats', 'redi-restaurant-reservation'); ?> </label>
				</th>
				<td>
					<input id="services" type="text" value="<?php echo (int)count($getServices) ?>" name="services"/>
				</td>
				<td>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="MaxPersons"><?php _e('Max persons per reservation', 'redi-restaurant-reservation'); ?> </label>
				</th>
				<td>
					<input id="MaxPersons" type="text" value="<?php echo (int)$maxPersons ?>" name="MaxPersons"/>
				</td>
				<td>
				</td>
			</tr>
		</table>
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Working time', 'redi-restaurant-reservation'); ?> </h2>
		<table class="form-table" style="width: 20%;">
			<tr valign="top">

				<th scope="row">

				</th>
				<td>
					<?php _e('Open', 'redi-restaurant-reservation'); ?>
				</td>
				<td>
					<?php _e('Close', 'redi-restaurant-reservation'); ?>
				</td>
			</tr>

			<?php foreach ($this->weekday as $serviceTimeName): ?>
				<?php $serviceTimeValue = $serviceTimes[$serviceTimeName]; ?>

				<tr valign="top">
					<th scope="row">
						<label for="OpenTime[<?php echo $serviceTimeName ?>]">
							<?php _e($serviceTimeName) ?>
						</label>
					</th>
					<td>
						<input id="OpenTime[<?php echo $day ?>]" type="text"
						       value="<?php echo $serviceTimeValue->OpenTime ?>"
						       name="OpenTime[<?php echo $serviceTimeName ?>]"/>
					</td>
					<td>
						<input id="" type="text" value="<?php echo $serviceTimeValue->CloseTime ?>"
						       name="CloseTime[<?php echo $serviceTimeName ?>]"/>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
		<br/>
		<p class="description">
			<?php _e('Specify time in 24h format (00:00 - 23:59).', 'redi-restaurant-reservation'); ?>
			<br/>
			<?php _e('If you close next day at night then set closing time on a same day. For example 18:00 - 3:00', 'redi-restaurant-reservation'); ?>
			<br/>
			<?php _e('Set Open and Close fields to blank if restaurant is closed.', 'redi-restaurant-reservation'); ?>
		</p>
		<br/>
		<div class="icon32" id="icon-users"><br></div>
		<h2><?php _e('Restaurant details', 'redi-restaurant-reservation'); ?></h2>

		<table class="form-table" style="width: 80%;">
			<tr valign="top">

				<th scope="row" style="width:15%;">
					<label for="Name">
						<?php _e('Restaurant name', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="Name" type="text" value="<?php echo $place['Name'] ?>" name="Name"/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="Country">
						<?php _e('Country', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="Country" type="text" value="<?php echo $place['Country'] ?>" name="Country"/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="City">
						<?php _e('City', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="City" type="text" value="<?php echo $place['City'] ?>" name="City"/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="Address">
						<?php _e('Address', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="Address" type="text" value="<?php echo $place['Address'] ?>" name="Address"/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="WebAddress">
						<?php _e('Url', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="WebAddress" type="WebAddress" value="<?php echo $place['WebAddress'] ?>"
					       name="WebAddress"/>
				</td>
			</tr>
			<tr>

				<th scope="row">
					<label for="Email">
						<?php _e('Email', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="Email" type="text" value="<?php echo $place['Email'] ?>" name="Email"/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="Phone">
						<?php _e('Phone', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="Phone" type="text" value="<?php echo $place['Phone'] ?>" name="Phone"/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="DescriptionShort">
						<?php _e('Short description', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="DescriptionShort" type="text" value="<?php echo $place['DescriptionShort'] ?>" name="DescriptionShort"/>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="DescriptionFull">
						<?php _e('Long description', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td colspan="2">
					<textarea name="DescriptionFull" id="DescriptionFull" cols="60" rows="5"><?php echo $place['DescriptionFull'] ?></textarea>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="MinTimeBeforeReservation">
						<?php _e('Hours before reservation', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input id="MinTimeBeforeReservation" type="text" value="<?php echo $place['MinTimeBeforeReservation'] ?>" name="MinTimeBeforeReservation"/>
				</td>
				<td style="width:80%">
					<p class="description">
						<?php _e('Minimum hours before reservation can be accepted from client', 'redi-restaurant-reservation'); ?>
					</p>
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
					<label for="Catalog">
						<?php _e('Catalog', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<input type="checkbox" name="Catalog" id="Catalog" value="1" <?php if ($place['Catalog']) echo 'checked="checked"' ?>>
				</td>
				<td style="width:80%">
					<p class="description">
						<?php _e('Publish restaurant details to reservationdiary.eu catalog', 'redi-restaurant-reservation'); ?>
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
		</table>

		<div class="icon32" id="icon-edit-comments"><br></div>
		<h2><?php _e('Email Configuration', 'redi-restaurant-reservation'); ?></h2>

		<table class="form-table" style="width: 80%;">
			<tr>
				<th scope="row">
					<label for="Lang">
						<?php _e('Language', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<select name="Lang" style="width:137px;"/>
					<?php foreach ((array)$redi_l10n_sys_locales as $locale): ?>
						<option <?php if ($place['Lang'] == $locale['lang-www']): ?> selected="selected" <?php endif ?> value="<?php echo $locale['lang-www'] ?>">
							<?php echo $locale['lang-native']; ?>
						</option>
					<?php endforeach ?>
					</select>
				</td>
				<td>
					<p class="description">
						<?php _e('Language for admin emails', 'redi-restaurant-reservation'); ?>
					</p>
				</td>
			</tr>
			<tr valign="top">

				<th scope="row" style="width:15%;">
					<label for="DateFormat">
						<?php _e('Date format', 'redi-restaurant-reservation'); ?>
					</label>
				</th>
				<td>
					<select id="DateFormat" name="DateFormat">
						<optgroup label="Hyphen">
							<option <?php if ($place['DateFormat'] == 'yyyy-MM-dd'): ?> selected="selected" <?php endif ?> value="yyyy-MM-dd">yyyy-mm-dd</option>
							<option <?php if ($place['DateFormat'] == 'MM-dd-yyyy'): ?> selected="selected" <?php endif ?> value="MM-dd-yyyy">mm-dd-yyyy</option>
							<option <?php if ($place['DateFormat'] == 'dd-MM-yyyy'): ?> selected="selected" <?php endif ?> value="dd-MM-yyyy">dd-mm-yyyy</option>
						</optgroup>
						<optgroup label="Dot">
							<option <?php if ($place['DateFormat'] == 'yyyy.MM.dd'): ?> selected="selected" <?php endif ?> value="yyyy.MM.dd">yyyy.mm.dd</option>
							<option <?php if ($place['DateFormat'] == 'MM.dd.yyyy'): ?> selected="selected" <?php endif ?> value="MM.dd.yyyy">mm.dd.yyyy</option>
							<option <?php if ($place['DateFormat'] == 'dd.MM.yyyy'): ?> selected="selected" <?php endif ?> value="dd.MM.yyyy">dd.mm.yyyy</option>
						</optgroup>
						<optgroup label="Slash">
							<option <?php if ($place['DateFormat'] == 'yyyy/MM/dd'): ?> selected="selected" <?php endif ?> value="yyyy/MM/dd">yyyy/mm/dd</option>
							<option <?php if ($place['DateFormat'] == 'MM/dd/yyyy'): ?> selected="selected" <?php endif ?> value="MM/dd/yyyy">mm/dd/yyyy</option>
							<option <?php if ($place['DateFormat'] == 'dd/MM/yyyy'): ?> selected="selected" <?php endif ?> value="dd/MM/yyyy">dd/mm/yyyy</option>
						</optgroup>
					</select>
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
			<?php for($i=1; $i!=6; $i++):?>
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

		<input class="button-primary" id="submit" type="submit" value="Save" name="submit">
	</form>
<?php else:?>
	<iframe src="http://wp.reservationdiary.eu/en-uk/<?php echo $this->ApiKey; ?>/AvailableSeats" width="100%;" style="min-height: 500px;"></iframe>
	<?php endif ?>
</div>
</div>
<br/>
<br/>
<br/>
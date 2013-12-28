<style type="text/css">
	.tab_wrap {
		background-color: #FFFFFF;
		border: 1px solid #CCCCCC;
		padding: 10px;
	}
</style>
<script type="text/javascript">
jQuery(function () {
	jQuery('#Place').change(function () {
		jQuery('#Place option:selected').each(function () {
				var data = {
					action: 'redi_restaurant-submit',
					get: 'get_place',
					placeID: this.value
				};
				jQuery.post('admin-ajax.php', data, function (response) {
					jQuery('#ajaxed').html(response);
				});
		});
	});
});
</script>

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
		<table class="form-table" >
		<tr valign="top">
				<th scope="row" style="width:15%;">
					<label for="Place"><?php _e('Place', 'redi-restaurant-reservation'); ?> </label>
				</th>
				<td>
					<select name="Place" id="Place">
						<?php foreach((array)$places as $place_current):?>
							<option value="<?php echo $place_current->ID ?>" <?php if($placeID == $place_current->ID): ?>selected="selected"<?php endif;?>>
								<?php echo $place_current->Name ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
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
		</table>
		<br/>
		<p class="description">
			<b style="color: red"><?php _e("NOTE: Reducing number of available seats will remove existing reservations.") ?></b>
		</p>
		<div id="ajaxed">
		<?php 
		//var_dump($placeID);
       self::ajaxed_admin_page($placeID, $categoryID);
		 ?>
		</div>
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
					<select name="Lang" style="width:137px;">
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

		<input class="button-primary" id="submit" type="submit" value="Save" name="submit">
	</form>
<?php else:?>
	<iframe src="http://wp.reservationdiary.eu/en-uk/<?php echo $this->ApiKey; ?>/Home" width="100%;" style="min-height: 500px;"></iframe>
	<?php endif ?>
</div>
</div>
<br/>
<br/>
<br/>
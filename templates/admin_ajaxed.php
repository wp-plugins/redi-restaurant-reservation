<script type="text/javascript">
jQuery(function () {
	jQuery('#Place').change(function () {
		jQuery('#Place option:selected').each(function () {
				var data = {
					action: 'redi_restaurant-submit',
					get: 'get_place',
					placeID: this.value
				};
                jQuery('#ajaxload').show('slow');
				jQuery.post('admin-ajax.php', data, function (response) {
                    jQuery('#ajaxload').hide('slow');
					jQuery('#ajaxed').html(response);
				});
		});
	});
});
</script>
<div class="icon32" id="icon-options-general"><br></div>

	<div class="icon32" id="icon-users"><br></div>
	<h2><?php _e('Restaurant settings', 'redi-restaurant-reservation'); ?></h2>

	<table class="form-table" style="width: 80%;">
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
                            <img id="ajaxload" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/ajax-loader.gif" alt=""/>
                        </td>
				        <td style="width:80%">
					        <p class="description">
                                <?php _e('Multiple places are available for Basic package users', 'redi-restaurant-reservation') ?>
                            </p>
                        </td>
                </tr>
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
				<select id="Country" name="Country">
					<option value=""> -- <?php _e('Select Country', 'redi-restaurant-reservation')?> -- </option>
					<?php foreach($countries as $country):?>
					<option value="<?php echo $country ?>" <?php if($place['Country']==$country): ?>selected="selected"<? endif ?>><?php echo $country ?></option>
					<?php endforeach ?>
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
				<input id="WebAddress" type="WebAddress" value="<?php echo $place['WebAddress'] ?>" name="WebAddress"/>
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
				<label for="EmailCC">
					<?php _e('Email CC', 'redi-restaurant-reservation'); ?>
				</label>
			</th>
			<td>
				<input id="EmailCC" type="text" value="<?php echo $place['EmailCC'] ?>" name="EmailCC"/>
			</td>
			<td>
				<p class="description">
					<?php _e('Send copy of reservation emails to specific recipients. Separate multiple recipients with ,', 'redi-restaurant-reservation'); ?>
				</p>
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
				<input maxlength="200" id="DescriptionShort" type="text" value="<?php echo $place['DescriptionShort'] ?>" name="DescriptionShort"/>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="DescriptionFull">
					<?php _e('Long description', 'redi-restaurant-reservation'); ?>
				</label>
			</th>
			<td colspan="2">
				<textarea maxlength="1000" name="DescriptionFull" id="DescriptionFull" cols="60" rows="5"><?php echo $place['DescriptionFull'] ?></textarea>
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
		<tr valign="top">
			<th scope="row">
				<label for="services"><?php _e('Available seats', 'redi-restaurant-reservation'); ?> </label>
			</th>
			<td>
				<select name="services" id="services">
					<?php foreach(range(1, 200) as $current):?>
						<option value="<?php echo $current?>" <?php if($current == (int)count($getServices)): ?>selected="selected"<?php endif;?>>
							<?php echo $current ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
			</td>
		</tr>			
	</table>
        <p class="description">
			<b style="color: red"><?php _e('NOTE: Reducing number of available seats will remove existing reservations.', 'redi-restaurant-reservation') ?></b>
		</p>
                <br/>
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
			<?php $serviceTimeValue = isset($serviceTimes[$serviceTimeName]) ? $serviceTimes[$serviceTimeName] : ''; ?>

			<tr valign="top">
				<th scope="row">
					<label for="OpenTime[<?php echo $serviceTimeName ?>]">
						<?php _e($serviceTimeName) ?>
					</label>
				</th>
				<td>
					<input id="OpenTime[<?php echo $serviceTimeName ?>]" type="text"
							value="<?php echo isset($serviceTimeValue['OpenTime'])?$serviceTimeValue['OpenTime']:'' ?>"
							name="OpenTime[<?php echo $serviceTimeName ?>]"/>
				</td>
				<td>
					<input id="" type="text" value="<?php echo isset($serviceTimeValue['CloseTime'])?$serviceTimeValue['CloseTime']:'' ?>"
							name="CloseTime[<?php echo $serviceTimeName ?>]"/>
				</td>
			</tr>
		<?php endforeach ?>
	</table>
	<br/>
	<br/>

	<p class="description">
		<?php _e('Specify time in 24h format (00:00 - 23:59).', 'redi-restaurant-reservation'); ?>
		<br/>
		<?php _e('If you close next day at night then set closing time on a same day. For example 18:00 - 3:00', 'redi-restaurant-reservation'); ?>
		<br/>
		<?php _e('Set Open and Close fields to blank if restaurant is closed.', 'redi-restaurant-reservation'); ?>
		<br/>
		<?php _e('Multiple open and close times are available in Basic package.', 'redi-restaurant-reservation'); ?>
	</p>
	<br/>
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
                                        <optgroup label="<?php _e('Hyphen', 'redi-restaurant-reservation'); ?>">
                                                <option <?php if ($place['DateFormat'] == 'yyyy-MM-dd'): ?> selected="selected" <?php endif ?> value="yyyy-MM-dd">yyyy-mm-dd</option>
                                                <option <?php if ($place['DateFormat'] == 'MM-dd-yyyy'): ?> selected="selected" <?php endif ?> value="MM-dd-yyyy">mm-dd-yyyy</option>
                                                <option <?php if ($place['DateFormat'] == 'dd-MM-yyyy'): ?> selected="selected" <?php endif ?> value="dd-MM-yyyy">dd-mm-yyyy</option>
                                        </optgroup>
                                        <optgroup label="<?php _e('Dot', 'redi-restaurant-reservation'); ?>">
                                                <option <?php if ($place['DateFormat'] == 'yyyy.MM.dd'): ?> selected="selected" <?php endif ?> value="yyyy.MM.dd">yyyy.mm.dd</option>
                                                <option <?php if ($place['DateFormat'] == 'MM.dd.yyyy'): ?> selected="selected" <?php endif ?> value="MM.dd.yyyy">mm.dd.yyyy</option>
                                                <option <?php if ($place['DateFormat'] == 'dd.MM.yyyy'): ?> selected="selected" <?php endif ?> value="dd.MM.yyyy">dd.mm.yyyy</option>
                                        </optgroup>
                                        <optgroup label="<?php _e('Slash', 'redi-restaurant-reservation'); ?>">
                                                <option <?php if ($place['DateFormat'] == 'yyyy/MM/dd'): ?> selected="selected" <?php endif ?> value="yyyy/MM/dd">yyyy/mm/dd</option>
                                                <option <?php if ($place['DateFormat'] == 'MM/dd/yyyy'): ?> selected="selected" <?php endif ?> value="MM/dd/yyyy">mm/dd/yyyy</option>
                                                <option <?php if ($place['DateFormat'] == 'dd/MM/yyyy'): ?> selected="selected" <?php endif ?> value="dd/MM/yyyy">dd/mm/yyyy</option>
                                        </optgroup>
                                </select>
                        </td>
                </tr>

        </table>
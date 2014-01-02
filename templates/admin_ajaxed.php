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
				<input id="services" type="text" value="<?php echo (int)count($getServices) ?>" name="services"/>
			</td>
			<td>
			</td>
		</tr>			
	</table>
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
	<br/>

	<p class="description">
		<?php _e('Specify time in 24h format (00:00 - 23:59).', 'redi-restaurant-reservation'); ?>
		<br/>
		<?php _e('If you close next day at night then set closing time on a same day. For example 18:00 - 3:00', 'redi-restaurant-reservation'); ?>
		<br/>
		<?php _e('Set Open and Close fields to blank if restaurant is closed.', 'redi-restaurant-reservation'); ?>
	</p>
	<br/>
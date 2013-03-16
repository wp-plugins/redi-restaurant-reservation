<div class="wrap">

	<?php if (isset($settings_saved)): ?>
    <div class="updated" id="message"><p><?php _e('Your settings have been saved!', 'redi-restaurant-reservation')?></p></div>
	<?php endif ?>
    <div class="icon32" id="icon-admin"><br></div>
    <h2><?php _e('Common settings', 'redi-restaurant-reservation');?></h2>

    <form name="redi-restaurant" method="post">
        <!--input type="hidden" name="categoryID" value="<?php echo $categoryID;?>"/-->
        <table class="form-table" style="width: 50%;">

            <tr valign="top">
                <th scope="row">
                    <label for="services"><?php _e('Available seats', 'redi-restaurant-reservation');?> </label>
                </th>
                <td>
                    <input id="services" type="text"
                           value="<?php echo (int)count($getServices) ?>" name="services"/>
                </td>
                <td>
                </td>
            </tr>
        </table>
        <div class="icon32" id="icon-options-general"><br></div>
        <h2><?php _e('Working time', 'redi-restaurant-reservation');?> </h2>
        <table class="form-table" style="width: 50%;">
            <tr valign="top">

                <th scope="row">

                </th>
                <td>
					<?php _e('Open', 'redi-restaurant-reservation');?>
                </td>
                <td>
					<?php _e('Close', 'redi-restaurant-reservation');?>
                </td>
            </tr>
			<?php //$day =0; ?>
			<?php foreach ($this->weekday as $serviceTimeName): ?>
			<?php //foreach ($serviceTimes as $serviceTimeName => $serviceTimeValue): ?>
			<?php $serviceTimeValue = $serviceTimes[$serviceTimeName]; ?>

            <tr valign="top">
                <th scope="row">
                    <label for="OpenTime[<?php echo $serviceTimeName?>]">
						<?php _e($serviceTimeName)?>
                    </label>
                </th>
                <td>
                    <input id="OpenTime[<?php echo $day?>]" type="text" value="<?php echo $serviceTimeValue->OpenTime?>"
                           name="OpenTime[<?php echo $serviceTimeName?>]"/>
                </td>
                <td>
                    <input id="" type="text" value="<?php echo $serviceTimeValue->CloseTime?>"
                           name="CloseTime[<?php echo $serviceTimeName?>]"/>
                </td>
            </tr>
			<?php endforeach?>
        </table>
        <div class="icon32" id="icon-users"><br></div>
        <h2><?php _e('Restaurant details', 'redi-restaurant-reservation');?></h2>

        <table class="form-table" style="width: 50%;">
            <tr valign="top">

                <th scope="row">
                    <label for="Name">
						<?php _e('Restaurant name', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td>
                    <input id="Name" type="text" value="<?php echo $place['Name']?>" name="Name"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="Country">
						<?php _e('Country', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td>
                    <input id="Country" type="text" value="<?php echo $place['Country']?>" name="Country"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="City">
						<?php _e('City', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td>
                    <input id="City" type="text" value="<?php echo $place['City']?>" name="City"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="Address">
						<?php _e('Address', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td>
                    <input id="Address" type="text" value="<?php echo $place['Address']?>" name="Address"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="WebAddress">
						<?php _e('Url', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td>
                    <input id="WebAddress" type="WebAddress" value="<?php echo $place['WebAddress']?>"
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
                    <input id="Email" type="text" value="<?php echo $place['Email']?>" name="Email"/>
                </td>
            </tr>
            <tr>

                <th scope="row">
                    <label for="Phone">
						<?php _e('Phone', 'redi-restaurant-reservation'); ?>
                    </label>
                </th>
                <td>
                    <input id="Phone" type="text" value="<?php echo $place['Phone']?>" name="Phone"/>
                </td>
            </tr>
        </table>
        <br/>
        <input type="checkbox" name="Catalog" id="Catalog" value="1" <?php if ($place['Catalog'])
			echo 'checked="checked'?>>
        <label for="Catalog">
			<?php _e('Publish restaurant details to reservationdiary.eu catalog', 'redi-restaurant-reservation');?>
        </label>
        <br/>
        <br/>

        <input id="submit" type="submit" value="Save" name="submit">
    </form>
</div>
<div class="wrap">

	<?php if (isset($settings_saved)): ?>
    <div class="updated" id="message"><p><?php echo __('Your setting is saved!')?></p></div>
	<?php endif ?>
    <div class="icon32" id="icon-admin"><br></div>
    <h2><?php echo __('Common settings');?></h2>

    <form name="redi-restaurant" method="post">
        <!--input type="hidden" name="categoryID" value="<?php echo $categoryID;?>"/-->
        <table class="form-table" style="width: 50%;">

            <tr valign="top">
                <th scope="row">
                    <label for="services"><?php echo __('Seats:');?> </label>
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
        <h2><?php echo __('Working time:');?> </h2>
        <table class="form-table" style="width: 50%;">
            <tr valign="top">

                <th scope="row">

                </th>
                <td>
					<?php echo __('Open');?>
                </td>
                <td>
					<?php echo __('Close');?>
                </td>
            </tr>
			<?php //$day =0; ?>
			<?php foreach ($this->weekday as $serviceTimeName): ?>
			<?php //foreach ($serviceTimes as $serviceTimeName => $serviceTimeValue): ?>
			<?php $serviceTimeValue = $serviceTimes[$serviceTimeName]; ?>

            <tr valign="top">
                <th scope="row">
                    <label for="OpenTime[<?php echo $serviceTimeName?>]">
						<?php echo __($serviceTimeName)?>
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
        <h2><?php echo __('Address');?></h2>

        <table class="form-table" style="width: 50%;">
            <tr valign="top">

                <th scope="row">
                    <label for="Name">
						<?php echo __('Name'); ?>
                    </label>
                </th>
                <td>
                    <input id="Name" type="text" value="<?php echo $place['Name']?>" name="Name"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="Country">
						<?php echo __('Country'); ?>
                    </label>
                </th>
                <td>
                    <input id="Country" type="text" value="<?php echo $place['Country']?>" name="Country"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="City">
						<?php echo __('City'); ?>
                    </label>
                </th>
                <td>
                    <input id="City" type="text" value="<?php echo $place['City']?>" name="City"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="Address">
						<?php echo __('Address'); ?>
                    </label>
                </th>
                <td>
                    <input id="Address" type="text" value="<?php echo $place['Address']?>" name="Address"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="WebAddress">
						<?php echo __('Url'); ?>
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
						<?php echo __('Email'); ?>
                    </label>
                </th>
                <td>
                    <input id="Email" type="text" value="<?php echo $place['Email']?>" name="Email"/>
                </td>
            </tr>
            <tr>

                <th scope="row">
                    <label for="Phone">
						<?php echo __('Phone'); ?>
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
			<?php echo __('Publish restaurant details to reservationdiary.eu catalog');?>
        </label>
        <br/>
        <br/>

        <input id="submit" type="submit" value="Save" name="submit">
    </form>
</div>
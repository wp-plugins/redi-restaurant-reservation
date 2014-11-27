<br clear="all"/>
<div class="wrap">

	<h2><?php _e('ApiKey:'); ?></h2> <br/>
	<b><i><?php echo ($this->ApiKey); ?></i></b>

	<br/><br/>
    <h2><?php _e('Basic package (paid version)','redi-restaurant-reservation'); ?></h2>
    <br/>
        <?php _e('* View upcoming reservations on Mobile/Tablet PC', 'redi-restaurant-reservation'); ?><br/>
    <p class="description">
        <?php _e('View upcoming reservations from your Mobile/Tablet PC and never miss your customer. This page should be open on a Tablet PC so waitress can see all upcoming reservations for today. Page refreshes every 15 min and shows reservations that in past for 3 hours as well as all upcoming reservations for next 24 hours. By clicking on reservation item you will see reservation details.', 'redi-restaurant-reservation');?><br/>
        <br/>
        <?php _e('Fully functional demo can be accessed by following link for 30 days:', 'redi-restaurant-reservation');?> <a href="http://upcoming.reservationdiary.eu/Entry/<?php _e($this->ApiKey) ?>" target="_blank"><?php _e('Open upcoming reservations', 'redi-restaurant-reservation');?></a>
    </p>
    <br/>
    <?php _e('* Setup maximum available seats for online reservation by week day', 'redi-restaurant-reservation');?><br/>
    <br/>
    <?php _e('* Time shifts. Define multiple open/close time by week day. Define time before reservation by shift and week day.', 'redi-restaurant-reservation');?><br/>
    <br/>
    <?php _e('* Support for multiple places.', 'redi-restaurant-reservation');?><br/>
    <br/>

    <?php _e('Basic package price is 5 EUR per month per place. To subscribe please use following PayPal link:') ?>
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R2KJQFCXB7EMN" target="_blank"><?php _e('Subscribe to basic package') ?></a><br/>
	<?php _e('Please allow 1 business day for us to confirm your payment and upgrade your account.') ?>
	
</div>
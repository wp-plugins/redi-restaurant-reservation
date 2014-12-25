<br clear="all"/>
<div class="wrap">

	<h2><?php _e('ApiKey:'); ?></h2> <br/>
	<b><i><?php echo ($this->ApiKey); ?></i></b></br>
    <?php _e('This is your registration key. Please use it when you send request for support.','redi-restaurant-reservation'); ?>

	<br/><br/>
    <h2><?php _e('Basic package functionality (paid version)','redi-restaurant-reservation'); ?></h2>
    <br/>
    <p class="description">
        <?php _e('◾ View upcoming reservations on Mobile/Tablet PC', 'redi-restaurant-reservation'); ?><br/>
        <?php _e('◾ View your upcoming reservations from your Mobile/Tablet PC and never miss your customer. This page should be open on a Tablet PC and so hostess can see all upcoming reservations for today. Page refreshes every 15 min and shows reservations that in past for 3 hours as well as upcoming reservations for next 24 hours. By clicking on reservation you will see reservation details. Demo version can be accessed for 30 days using this link: ', 'redi-restaurant-reservation'); ?> <a href="http://upcoming.reservationdiary.eu/Entry/<?php _e($this->ApiKey) ?>" target="_blank"><?php _e('Open upcoming reservations', 'redi-restaurant-reservation');?></a><br/>
        <?php _e('◾ Setup maximum available seats for online reservation by week day', 'redi-restaurant-reservation'); ?><br/>
        <?php _e('◾ Time shifts. The time shift option will enable you to choose between various working hours whichever is most convenient to you.', 'redi-restaurant-reservation'); ?><br/>
        <?php _e('◾ Support for multiple places. Number of places depends on number of subscriptions.', 'redi-restaurant-reservation'); ?><br/>
        <?php _e('◾ Blocked Time. Define time range when online reservation should not be accepted. Specify a reason why reservations are not accepted at this time to keep your clients happy.', 'redi-restaurant-reservation'); ?><br/>
        <?php _e('◾ Send client reservation confirmation emails from WordPress account', 'redi-restaurant-reservation'); ?><br/>
        <?php _e('◾ Email template customization for all supported languages', 'redi-restaurant-reservation'); ?><br/>
    </p>
    <?php _e('Basic package price is 5 EUR per month per place. To subscribe please use following PayPal link:') ?>
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R2KJQFCXB7EMN" target="_blank"><?php _e('Subscribe to basic package') ?></a><br/>
    <?php _e('Please provide API key into comment field. You can find API key from setting page.', 'redi-restaurant-reservation'); ?><br/>
    <?php _e('Please allow 1 business day for us to confirm your payment and upgrade your account.', 'redi-restaurant-reservation'); ?><br/>
	
</div>
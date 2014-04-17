=== ReDi Restaurant Reservation ===
Contributors: thecatkin, robby.roboter
Tags: reservation diary, reservation, table reservation, restaurant reservation, time reservation, open table, free table, easy reservation, easy booking, table booking, restaurant booking
Requires at least: 3.0.0
Tested up to: 3.8
Stable tag: trunk

ReDi Restaurant Reservation plugin allows you to easily manage reservations for your restaurant business.

== Description ==

ReDi Restaurant Reservation plugin allows you to manage reservations for your restaurant business. This plugin can help to receive reservations from clients online. Your clients will be able to see available space at specified time, and if it's available, client is able to make a table reservation.
Initially you need to setup number of persons that can book at one time, working times and restaurant details.

Supported languages:

* Danish
* Dutch
* English
* Estonian
* French, <a href="http://www.youtube.com/watch?v=MWJKx7onpBs">check out video review</a>
* German
* Italian
* Polish
* Russian
* Portuguese (Brazil) 
* Portuguese (Portugal)
* Spanish
* Swedish

If you need more languages, please contact us!

= Basic package functionality =
* View your upcoming reservations from your Mobile/Tablet PC and never miss your customer. This page should be open on a Tablet PC and so hostess can see all upcoming reservations for today. Page refreshes every 15 min and shows reservations that in past for 3 hours as well as upcoming reservations for next 24 hours. By clicking on reservation you will see reservation details. Demo version can be accessed by this link: <a href="http://goo.gl/DFSBXQ" target="_blank">Open demo version</a>
* Setup maximum available seats for online reservation by week day
* Time shifts. Define multiple open/close time by week day. Define time before reservation by shift and week day.
* Support for multiple places.
* Blocked Time. Define time range when online reservation should not be accepted. Specify a reason why reservations are not accepted to explain it to clients.

Basic package price is 5 EUR per month per place. To subscribe please use following PayPal link: <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R2KJQFCXB7EMN">Subscribe to basic package</a>

= Additional services =
* Make clients from your facebook fans. <a href="http://www.slideshare.net/sergeiprokopov/make-clients-from-your-facebook-fans">View presentation.</a>
* We can offer you white labelled restaurant reservation application for Facebook Application, iPhone/iPad Application, Windows Phone Application or Android Application. Please send request by email: <a href="mailto:info@reservationdiary.eu">info@reservationdiary.eu</a>
* Amaze your customer by knowing him in face when he visits you, especially when he visits you for first time. We can offer you Facebook integration where we will try to provide you customer profile picture if exists.
* Do you want to know what your client thinks about his last visit? We will collect it for you.
* Remind your customer about upcoming reservation via Email or by SMS
* Collect pre-payment for reservations
* Are you building catalogue with restaurants and you are looking for reservation plugin for that? We can provide it for you.

Do you want to have some new functionality or if you have any other questions please contact us by email: <a href="mailto:info@reservationdiary.eu">info@reservationdiary.eu</a>

= Other plugins =
* If you need to manage more complex reservations, please check our <a href="http://wordpress.org/plugins/redi-reservation/" target="_blank">ReDi Reservation</a>
 plugin too.
== Installation ==

1. Click on "Install now" to install plugin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings/Redi Restaurant Reservation page
4. Follow instructions on the page to setup plugin

When plugin is activated, new page is automatically created and you can make your first reservation straight away. Plugin is based on a public <a href="http://www.reservationdiary.eu/eng/reservation-wordpress-plugin">ReservationDiary API</a>. We do not share any of our user's information with third parties.
In case you have any problems with plugin installation or you need some customization, please don't hesitate to contact us by email: <a href="mailto:info@reservationdiary.eu">info@reservationdiary.eu</a>

Plugin requires curl library to operate.

== Screenshots ==
01. Example of plugin first page installed into default theme. When plugin is activated, new "Reservation" page is created. Step 1: Requests from user to select reservation date and time, and number of seats. User has to click on a button "Check available time". System will query online database for available places at specified time and shows result.
02. Example of calendar control.
03. Example of time control.
04. Step 2: On this step user is requested to select available time if any.
05. Step 3: Once user selects available reservation time, user will be requested to fill reservation form with contact information and custom fields.
06. Reservation confirmation screen. At this time user has to receive confirmation email. Same time restaurant owner receives list of all upcoming reservations.
07. Setup screen with number of seats available, maximum persons per reservation, opening times and days of the week.
08. Setup screen with info on the restaurant including name, address, country, phone, email, URL
09. List of upcoming reservations on Mobile/Tablet PC (Available only for Basic package users). Demo version can be accessed by this link: <a href="http://goo.gl/DFSBXQ" target="_blank">Open demo version</a>
10. Detailed reservation information on Mobile/Tablet PC (Available only for Basic package users). Photo is taken from Facebook public profile. Photo, Visits and Rating are available only by request.
11. Cancel reservation from Tablet PC version
12. Configuration for maximum available seats for online reservation by week day. (Available only for Basic package users)
13. Setting for custom fields that user should fill during reservation
14. List of Time shifts (Available only for Basic package users)
15. Open/close time for time shift (Available only for Basic package users)
16. Time before reservation for time shift by weekday (Available only for Basic package users)
17. Step 1: Select place, date and time (Multiple places available only for Basic package users)
18. Blocked Time list. (Available only for Basic package users)
19. Edit Blocked time. (Available only for Basic package users)

== Upgrade Notice ==

== Changelog ==

= 14.0221 =
* Added alternative time picker for better reservation time selection on mobile devices
* Added limits to text areas
* Moved all texts to language files
* (+) Added settings to select Min and Max party size
* (+) Added message to client when he select Large group
* (+) Added setting to specify alternative time step
* (+) Added Portuguese (Brazil) translation
* (+) Added Swedish translation
* (+) Fixed multi language support
* (+) Fixed time displayed in drop down
* (+) Removed line brakes from javascript to prevent WordPress inserting p elements there when content formatting is turned on
* (+) Removed direct dependency from curl library

= 14.0114 =
* Added support for multiple places (for basic package users)
* Added better error handling to Admin console
* Changed style of confirmation message
* Scroll up page to confirmation message
* Set calendar style to display on top of all elements
* Moved cancel functionality to separated Tab
* Fixed double click on reservation
* Fixed manual date input

= 13.1022 =
* Added setting for maximum persons per reservation
* Added support for custom fields that user should fill during reservation
* Added Polish language

= 13.0919 =
* Added Portuguese language
* Added German language
* Refactored calendar localization support to work on PHP 5.2 version
* Improved error handling

= 13.0817 =
* Added calendar localization support

= 13.0815 =
* Added cancel reservation from admin panel

= 13.0716 =
* Added possibility to specify date format in emails
* Added to configuration reservation duration time

= 13.0625 =
* Added timezone support

= 13.0618 =
* Fixed problem with double escaping of ' or \ symbols

= 13.0530 =
* Added Dutch translation
* Added Danish translation
* Added Spainish translation

= 13.0505 =
* Added support for different time formats

= 13.0406 =
* Added French translation
* Added possibility to provide Short and Full description about restaurant
* Added language selection for emails that are sent to administrator
* Added configuration of minimum time in hours before reservation

= 13.0316 =
* Fixed incompatibility with plugins that resides on same page
* Added multi language support to reservation form
* Added translations on Russian and Estonian

= 13.0302 =
* Fixed incompatibility with some themes

= 13.0203 =
* Changed persons from free form text to drop down
* When user changes date, time or number of persons, form is closed and user has to check reservation availability with new form data
* Added required field validation on a client side
* When fully booked added information message

= 13.0128 =
* Added settings saved message box to admin page
* Added checkbox that asks for permission to publish restaurant details into reservationdiary.eu catalog
* Fixed prepopulating of URL field in admin page

= 13.0119 =
* Updated reservation confirmation text
* Selected reservation time highlighted with bold style
* Added loading icon
* Applied time zone of the general WordPress settings
* Removed links from confirmation email

= 13.0114 =
* Initial version
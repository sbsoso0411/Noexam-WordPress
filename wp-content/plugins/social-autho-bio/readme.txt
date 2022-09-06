=== Social Author Bio ===
Contributors: nickpowers 
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZKC2FCDBY5LRC
Tags: author, author bio, social, author box, facebook, google author
Requires at least: 2.9
Tested up to: 3.4.2
Stable tag: 2.4
License: GPLv2

Social Author Bio automatically adds an author bio box along with Gravatar and social icons on posts. Now with built in Google Author!

== Description ==
[Social Author Bio](http://nickpowers.info/wordpress-plugins/social-author-bio/) adds a author bio box with the author's avatar with built-in and custom social icons on pages/posts.

= Examples =
*   [Social Author Bio Main Site](http://nickpowers.info/wordpress-plugins/social-author-bio/)
*   [Social Author Bio Example](http://www.elirose.com/)

= New to version 2.4 =
Thanks for all your great suggestions

*   Fully integrated Google+ authorship (shows Google+ Avatar in search results)
*   Added custom field (check box) on edit screen providing the ability to disable of author box on individual pages/posts
*   New shortcode [social_bio_icons] which displays only the Social Icons
*   Ability to adjust avatar size (admin)
*   Choice of location, top or bottom, for automatic placement of Social Author Bio
*   Number of custom links increased from 5 to 10
*   Added %home% (The home URL) variable to advanced HTML section

= Built in Social Icons =
AIM, Digg, eMail, Facebook, Google+, iCompositions, ICQ, LinkedIn, MSN, MySpace, Pinterest, Reverbnation, Skype, Soundcloud, Technorati, Twitter, Yahoo, YouTube

= Custom Social Icons =
Admin can create up to 5 custom social icons

**Admin** can control the following options from the settings menu:

*   Enable/disable use of Google+ authorship sitewide
*   Complete control over HTML that produces the Social Author Bio
*   Complete control over CSS that styles the Social Author Bio
*   Enable/disable built in and custom social icons site wide
*   Admin can enable/disable Social Author Bio display on Pages, Posts, and/or shortcodes.
*   Which role to start using Social Author Bio (defaults to Contributor) 
*   What prefix to display before user's name.
*   Location of bio box, top or bottom, for automatic placement of Social Author Bio
*   Avatar size


**Users** can control the following options from their profile page:

*   Enable/disable Social Author Bio for their profile
*   Choice of website icon (WordPress, Blogger, or website)
*   Enable/disable use of Google+ authorship for their profile
*   Enable/disable the built in or custom social icons on their profile
*   Configure each social icon's user, username, id, etc.
*   Use shortcode [social-bio] in pages/posts
*   Use shortcode [social-bio id=xxx] where xxx is a user id to display

If enabled by Admin & Author Social Author Bio is displayed automatically on post/pages.

If enabled by Admin, Authors can use the [social-bio] and/or the [social_bio_icons] shortcodes in posts/pages.
The shortcode can be used even if the Author disables Social Author Bio in their profile.
This allows the Author to tag selected posts with their Social Author Bio.

If you have suggestions for a new add-on, feel free to [Email](http://nickpowers.info/contact-social-author-bio/) me.

== Installation ==

1. Unpack the download-package  
1. Upload all files to the `/wp-content/plugins/` directory, with folder `social_author_bio`  
1. Activate the plugin through the 'Plugins' menu in WordPress  
1. Got to 'Options' menu and configure the plugin

== License ==

Copyright (C) 2012  Nick Powers

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
[GPL V2.0](www.gnu.org/licenses/gpl-2.0.html)

Nick Powers
[NickPowers.info](http://nickpowers.info)
[Email](http://nickpowers.info/contact-social-author-bio/)

== Frequently Asked Questions ==
Please fill out the contact form at [NickPowers.info](http://nickpowers.info/contact-social-author-bio/).  FAQs will be added here as they are presented

= I have a multi-author blog entry, both of them have user profiles. I have tried the [social-bio id=xxx], but it will only show 1. Is there a way that i can add social bios for 2 authors? =

Yes, first make sure shortcode is enabled in the general section of the Social Author Bio Admin menu.

After that just add one shortcode per author something like this:
[social-bio id=1]
[social-bio id=2]

That would show a bio box for user with ID 1 and another for user with ID 2.  You can display as many as you want.

= How can I add the Social Author Bio box directly into my theme? =

Add code like below to your theme template files:

[?php echo do_shortcode('[social-bio]'); ?]
(Replace the first and last []'s with normal html greater/less than signs).

= Can I get get just the Social Author Icons into my Plugin / Theme? =

Yes, since v2.1 you can use the following code to retrieve the Social Icons (Images with links) 

$icons = apply_filters('social_author_icons', $ID); echo $icons;

where $ID is the ID of the user whos Social Icons you want to display

== Screenshots ==
1. Social Author Bio in Action!
2. Social Author Bio General Admin Settings
3. Social Author Bio Custom Social Icons
4. Social Author Bio HTML Settings
5. Social Author Bio User Profile Settings

== Changelog ==

Social Author Bio automatically adds an author bio box along with Gravatar and social icons on posts. Now with built in Google Author!

= 2.3 =
* Fully integrated Google+ authorship (shows Google+ Avatar in search results)
* Added custom field (check box) on edit screen providing the ability to disable of author box on individual pages/posts
* New shortcode [social_bio_icons] which displays only the Social Icons
* Ability to adjust avatar size
* Choice of location, top or bottom, for automatic placement of Social Author Bio
* Number of custom links increased from 5 to 10
* Added %home% (The home URL) variable to advanced HTML section

= 2.3 =
* Fixed bug where users who had disabled Social Author Bio in their profile were still being displayed via shortcode

= 2.2 =
* Fixed bug where only WordPress website icon would display

= 2.1 =
* Added social_author_icons filter that other plugins / themes can retrieve Social Author Icons (Links w Images)
* Updated default style to include float:left; in #author-bio-box to fix a visual layer bug

= 2.0 =
* Code cleanup.
* Convert code to OOP style
* Added Pinterest to built-in Social Icons
* Added ability to create custom Social Icons
* Added ability to modify HTML
* Added ability to modify Styles

= 1.2.3 =
* Fixed prepend LinkedIn URL (per user report)

= 1.2.2 =
* Code cleanup.

= 1.2.1 =
* Enabled control over which role to start using Social Author bio.
* Added Reverbnation, Soundcloud, iCompositions, YouTube per user request.

= 1.2 =
* Added [social-bio id=xxx] shortcode

= 1.1.1 =
* Fixed issue where some users were seeing borders.
 
= 1.1 =
* Added [social-bio] shortcode

= .1 =
* Initial release

= .1 =
* Pre v1, beta, and gold testing done in house.
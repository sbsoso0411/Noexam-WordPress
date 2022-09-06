=== Plugin Name ===
Contributors: kubi23
Tested up to: 4.4.5
Stable tag: 5.2.1
Requires at least: 3.0

Tags: blog, wordpress, security, plugin, comment, comments, anti-spam, antispam, spam, spambot, spambots, protection, user, users, template, secure, hidden, yawasp, nospamnx, blacklist, blacklist, field, fields

To protect your Blog from automated spambots, this plugin adds invisible formfields to your comment form.

== Description ==

There have been many new good ideas of fighting automated Spam in WordPress. Most of these Plugins (like the antecessor of NoSpamNX: Yawasp) change the name of one (or more) of your comment field. On the one hand, this is indeed more effective, but on the other hand, this goes to the expense of compatibility. Therefore, NoSpamNX does not change any of your comment fields, but still claims to be very effective.

Many Antispam Plugins focus on user interaction, e.g. captcha or calculations to defend you against automated comment spambots. Some use JavaScript and/or Sessions. NoSpamNX intend to handle automated comment-spam-protection without these measures. It does not require JavaScript, Cookies or Sessions. It does not change any of your comment template fields, given you more compatibility with other WordPress- or Browser Plugins.

NoSpamNX automaticly adds additional formfields to your comment form, invisible to human users. If a spambot fills these fields blindly (which most of all spambots do), the comment will not be saved. You can decide if you want to block these spambots or mark them as spam. Furthermore, you can put common spam-phrases on a local Blacklist or (if you have multiple Blogs) on a global Blacklist. This comes very handy when fighting against "handmade" Spam.

NoSpamNX works best with a wordpress default theme (e.g. TwentyEleven, TwentyTwelve, etc.)

= Requirements =

Make sure your theme loads <code>comment_form</code> in the Comment-Template (comments.php) according to the WordPress Codex - see http://is.gd/1lezf - otherwise NoSpamNX will not work properly!


= Features in a Nutshell =

* Easy installation (just activate the plugin)
* Easy configuration (only two options)
* Increased spam protection when using WordPress default Theme
* Local Blacklist to block specific Phrases, URLs, Domains, IP-Ranges, etc. (use it to block "handmade" Spam)
* Global Blacklist for multiple WordPress Blogs
* Does not require JavaScript, Cookies or Sessions
* Does not require any extra field for user input (e.g. Captcha, Calculations, Pictures, etc.)
* No need to manage spambot comments (if you don't want to)
* No Database queries except for a few WordPress Options
* False-positives are nearly impossible


= Compatibility =

* Compatible with bbPress
* Compatible with WPtouch (Mobile Theme)
* Compatible with WordPress Multi-Site feature (see FAQ)


= Known Issues =

* Does not work with OpenID
* Does not work with BuddyPress
* Does not work with Jetpack Comments


= Available Languages  =

* German
* English
* Spanish (Thanks to Samuel Aguilera)
* Italian (Thanks to Gianni Diurno)
* Russian (Thanks to minimus)
* Hebrew (Thanks to Amiad)
* Belarusian (Thanks to Alexander Ovsov)
* Turkish (Thanks to Hakan Er)


== Installation ==

1. Unzip Plugin
2. Copy the nospamnx folder to your wp-content/plugins folder
3. Activate plugin
4. (Optional) Adjust settings (Settings -> NoSpamNX)


== Frequently Asked Questions ==

= After I updated to a new Version all comments are marked as Spam! =

The best way to deal with this Problem, is to uninstall the Plugin via the WordPress Backend and re-install the Plugin.

= After I updated to a new Version the Options are reseted! =

Update to at least Version 3.14. Since this Version the Options are not rested when the plugin is updated.

= I use OpenID and after Installation all comments are marked as Spam! =

NoSpamNX does not work with OpenID.

= I use BuddyPress and after Installation all comments are marked as Spam! =

NoSpamNX does not work with BuddyPress.

= Does NoSpamNX work with WordPress Multi-Site feature? =

Yes, but you have to activate the plugin for each site individually.

= Can I block IP-Adresses and IP-Ranges with the built-in blacklist? =

Yes, just add an IP-Address (e.g. 10.23.23.4) in the Blacklist or use CIDR-Notation (e.g. 10.43.8.67/28) to block IP-Ranges.

= Does the plugin block Ping-/Trackback Spam as well? =

No, the plugin focus on automated Spambots only and I have no plans of changing that. There are some really good Plugins in the WordPress Plugin Repository which can do that for you.

= What about false-positives? =

Due to the functionality of NoSpamNX false-positives are nearly impossible. There 'might' be problems when using WordPress Cache-Plugins, but none have ever been reported. If you are uncertain, mark Spambots as Spam instead of blocking them.


== Screenshots ==

1. Statistic on Dashboard
2. Settings
3. Local Blacklist
4. Global Blacklist


== Changelog ==

= 5.2.1 =
* Translation fix

= 5.2.0 =
* Added new language features from WordPress (thanks to m266)
* Tested with latest WordPress version 4.5

= 5.1.18 =
* Tested with latest WordPress version 4.3

= 5.1.17 =
* Updated russion translation, added swedish translation (thanks to tobifjellner)

= 5.1.16 =
* Refactoring

= 5.1.15 =
* Changed nospamnxPluginLinks to static
* Changed nospamnxSettingsLinks to static

= 5.1.14 =
* Changed deprecated function __ngettext to _n
* Code cleanup

= 5.1.13 =
* Updated readme
* Re-added flattr button

= 5.1.12 =
* Removed Flattr Link

= 5.1.11 =
* Fixed typo in options array initialisation
* Updated function for cidr check for PHP > 5.3 compatibility

= 5.1.10 =
* Added filter for comment delay (thanks to Johan Eenfeldt)

= 5.1.9 =
* Minor code cleanup
* Checked compatibility to WordPress 3.5

= 5.1.8 =
* Increased wait time for post from 5 to 10 seconds

= 5.1.7 =
* Added License Tag
* Updated translations

= 5.1.6 =
* Fixed Bug on Dashboard

= 5.1.5 =
* Fixed Debug-Error
* Fixed Translation-Bug in Dashboard

= 5.1.4 =
* Fixed Bug, that didn't load langauges
* Updated translations

= 5.1.3 =
* Fixed Debug-Errors
* Updated readme
* Updated translations

= 5.1.2 =
* Updated Settings
* Updated translations

= 5.1.1 =
* Added Option to Block full-string or sub-string in Blacklists
* Updated translations
* Updated readme

= 5.1.0 =
* Added compatibitly to bbPress (Thanks to Marcel Bokhorst)
* Minor Code cleanup
* Updated readme

= 5.0.3 =
* Change Uninstall Hook to static function call (Thanks to Tom Braider)

= 5.0.2 =
* Added Turkish translation

= 5.0.1 =
* Fixed Bug when Plugin is activated (Thanks to Steve Munro)
* Fixed typo when options are stored
* Added specific message when comment is posted to fast
* Changed Response Code to 403 when comment is blocked (Thanks to Marcel Bokhorst)
* Changed Response Code to 403 when user has no permissions to change settings
* Changed Response Code to 403 when nonce-check fails

= 5.0.0 =
* NoSpamNX now requires at least WordPress 3.0
* Added timestamp check for new comment
* Removed option to show blocked word from blacklist
* Blacklist can now block IP-Ranges based on CIDR (e.g. 10.43.8.67/28)
* Updated readme
* Updated Translations
* Updated Screenshots
* Updated FAQ
* Code cleanup

= 4.1.6 =
* Added Link to Plugin Settings
* Added Plugin Banner

= 4.1.5 =
* Added Belarusian translation

= 4.1.4 =
* Fixed invalid HTML

= 4.1.3 =
* Minor Code improvements (Thanks to Thomas Bier)

= 4.1.2 =
* Fixed Bug when using WordPress Default Theme (TwentyTen) and WordPress >= 3.0

= 4.1.1 =
* Added Hebrew Translation
* Fixed Bug with WordPress >= 3.0
* Minor Code Update

= 4.1.0 =
* Improved Spam-Protection (Thanks to Marcel Bokhorst)
* Update italian translation

= 4.0.8 =
* Fixed bug when using the blacklist
* Added option for showing blocked string
* Updated language files

= 4.0.7 =
* Fixed bug when Counter was reseted
* Added more detailed messages when Spam is blocked or blacklisted
* Updated language files

= 4.0.6 =
* Removed DNSBL Check (temporarly)
* Updated Italian Translation

= 4.0.5 =
* Wrong Message was displayed when a comment was blocked
* Global Blacklist is now sorted
* Code cleanup

= 4.0.4 =
* Added DNSBL Check (dnsbl.tornevall.org)
* Removed French translation (< 60% translated)
* Updated language files
* Updated readme

= 4.0.3 =
* Removed PHP Version Check
* Removed Referer-Check
* Removes static in uninstall_hook

= 4.0.2 =
* Added PHP Version Check
* Improved Referer-Check
* Updated Spanish Translation

= 4.0.1 =
* Updated Russian Translation

= 4.0.0 =
* Improved Spambot-Protection
* Added local and global Blacklist
* Referer Check is now built-in
* Removed all deprecated variables
* Removed Translations with less than 60% translation
* Code cleanup
* Updated Screenshots
* Updated readme

= 3.22 =
* Fixed Bug with Referer-Check when Blog is installed in Sub-Directory

= 3.21 =
* Minor Code cleanup
* Some cleanup in Backend
* Updated readme

= 3.20 =
* Added Russian Translation
* Added flattr Button to Backend

= 3.19 =
* Added sorting of Blacklist

= 3.18 =
* Minor Code cleanup
* Updated Language Files
* Updated Screenshots

= 3.17 =
* Removed Custom CSS Option (Fields are now hidden by display:none again)

= 3.16 =
* Fixed Bug when reseting CSS Name
* Fixed Bug when activating plugin
* Updated readme

= 3.15 =
* Fixed Bug when activating plugin
* Removed deactivation hook
* Plugin now requires at least WordPress 2.8
* Updated language files

= 3.14 =
* Added nonce fields
* Added check, that options are only reseted if option-fields have changed
* Updated language files
* Updated readme

= 3.13 =
* Fixed Bug that could mark first comment after Plugin Update as Spam
* Added Support for SSL for Referer-Check
* Updated readme

= 3.12 =
* Changed Error- and Info-Message to new Stylesheets
* Updated readme

= 3.11 =
* Fixed Bug that could cause visibility of hidden fields
* Referer-Check checks now against 'siteurl' AND 'home'
* Code cleanup
* Updated language files

= 3.10 =
* Changed Referer-Check from 'siteurl' to 'home'
* Blacklist will only be deleted when plugin is uninstalled
* Removed WordPress MU support
* Removed comments-popup support
* Updated language files

= 3.9 =
* Fixed bug when displaying statistics
* Removed function that could caus false positives
* Updated spanish translation

= 3.8 =
* Quickfix for deactivation/activation Bug

= 3.7 =
* Fixed bug when plugin was deactivated
* Minor code improvements
* Updated language files

= 3.6 =
* Improved Referer-Check
* Added per Day Stats
* Updated language files

= 3.5 =
* Fixed Bug in plugin activation
* Added Italian Translation

= 3.4 =
* Fixed Bug with referer check
* Updated language files
* Removed uneccesary code comments
* Updated readme

= 3.3 =
* Re-Added old CSS-Style due to cache problems
* Fixed Bug on Options-Page
* Updated screenshots
* Updated german language file
* Updated readme

= 3.2 =
* Fixed Bug when including CSS

= 3.1 =
* Fixed Bug when including CSS

= 3.0 =
* Hidden field names now have a variable length
* Added option to include own stylesheet
* Removed option to moderate catched spambots
* Removed option to check logged in users (now built-in)
* Removed option to check registration and login form
* Removed all fuzzy translations
* Blacklist now searches for pattern in comment field
* Updated language files
* Updated readme

= 2.9 =
* Added Tags to hidden field for XHTML 1.0 Strict compatibility (Thanks to Pete Stephenson!)
* Updated Spanish Translation

= 2.8 =
* Fixed Bug when directly accessing wp-admin

= 2.7 =
* Fixed Bug when checking URL

= 2.6 =
* Added optional check for Registration and Login Form
* Updated language files
* Updated readme

= 2.5 =
* Fixed bug when displaying statistics

= 2.4 =
* Plugin is now compatible with WordPress MU
* Added Swedish Translation
* Updated Chinese Translation
* Updated readme

= 2.3 =
* Optimized class loading
* Removed all output buffer calls in favor of comment_form hook
* Removed debug information on settings page

= 2.2 =
* Modified loading of Stylesheet
* Added new WordPress Plugin changelog

= 2.1 =
* Updated Spanish translation

= 2.0 =
* Added Blacklist
* Re-Added HTTP-Referer-Check, but now optional
* Changed pacing of hidden fields
* Changed all Radio-Buttons to Checkboxes
* Changed default operating to mark as spam
* Removed IP-Lock due to new Blacklist
* Removed option to deactivate Plugin on certain pages/posts due to new placing
* Requires now at least WordPress 2.7
* Updated Screenshot
* Updated readme
* Updated language files

= 1.10 =
* Removed referer check temporarily

= 1.9 =
* Fixed bug with referer check

= 1.8 =
* Improved function when using comments popup
* Added referer check
* Optimized function that blocks the spambots

= 1.7 =
* Fixed Bug when disabling NoSpamNX on certain pages/posts
* Optimized function that blocks the spambots

= 1.6 =
* Added feature to disable NoSpamNX on certain pages/posts
* Fixed Bug that displayed hidden fields in comments popup
* Added Polish translation
* Update language files

= 1.5 =
* Increased compatibility with different PHP configurations
* Added information tab in seetings
* Updated language files
* Updated FAQ

= 1.4 =
* Added Russian translation
* Added Chinese translation (Simplified Chinese)
* Modified function that changes the template
* Updated FAQ

= 1.3 =
* Added full compatibility with comments-popup
* Added Spanish translation
* Akismet or similar is not require any more to mark comment as spam
* Updated language files
* Updated Screenshot
* Updated readme.txt

= 1.2 =
* Added French translation
* Added Italian translation
* Minor code optimization
* Default blocktime for ip address set to 1 hour

= 1.1 =
* Optimized function that adds the hidden fields
* Removed all serialize/unserialize functions
* Changed activate/deactivate to wp-hooks
* Completly changed handling of options
* Updated language file

= 1.0 =
* Initial release

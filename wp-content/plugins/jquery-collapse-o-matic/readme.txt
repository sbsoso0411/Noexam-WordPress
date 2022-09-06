=== Collapse-O-Matic ===

Contributors: twinpictures, baden03
Donate link: http://plugins.twinpictures.de/plugins/collapse-o-matic/
Tags: collapse, expand, collapsible, expandable, expandable content, collapsable content, shortcode, hidden, hide, display, accordion, accordion, jQuery, javascript, roll-your-own, twinpictures, read me, read more, more, plugin oven
Requires at least: 4.0
Tested up to: 4.6-beta-3
Stable tag: 1.7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Remove clutter, save space: display and hide additional content in a SEO friendly way by wrapping content in an [expand] shortcode.

== Description ==

Collapse-O-Matic adds an `[expand title="trigger text"]hidden content[/expand]` shortcode that will wrap any content, including other shortcodes, into a lovely jQuery expanding and collapsing div.  A <a href='http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/'>complete listing of shortcode options and attribute demos</a> are available, as well as <a href='http://wordpress.org/support/plugin/jquery-collapse-o-matic'>free community</a> support.

== Installation ==

1. Old-school: upload the `collapse-o-matic` folder to the `/wp-content/plug-ins/` directory via FTP.  Hipster: Ironically add collapse-o-matic via the WordPress Plug-ins menu.
1. Activate the Plug-in
1. Add a the shortcode to your post like so: `[expand title="Displayed Title Goes Here"]Hidden content goes here[/expand]`
1. Test that the this plug-in meets your demanding needs.
1. Tweak the CSS to match your flavour.
1. Rate the plug-in and verify if it works at wordpress.org.
1. Leave a comment regarding bugs, feature request, cocktail recipes at http://wordpress.org/tags/jquery-collapse-o-matic/

== Frequently Asked Questions ==

= Where can I translate this plugin into my favourite language? =
<a href='https://translate.wordpress.org/projects/wp-plugins/jquery-collapse-o-matic'>Community translation for Collapse-O-Matic</a> is available via the WordPress Translation project.

= I am a Social Netwookiee, might Twinpictures have a Facebook page? =
Yes, yes... <a href='http://www.facebook.com/twinpictures'>Twinpictures is on Facebook</a>.

= Does Twinpictures do the Twitter? =
Ah yes! <a href='http://twitter.com/#!/twinpictures'>@Twinpictures</a> does the twitter tweeting around here.

= How does one use the shortcode, exactly? =
A <a href='http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/'>complete listing of shortcode options</a> has been provided to answer this exact question.

= Is Galato the same as Ice Cream? =
No. Not even close.

== Screenshots ==

1. Hmmm... wonder what happens this text by that arrow is clicked?
2. Holy Crap! That other text just showed up like magic!  Thank you Collapse-O-Matic!
3. Options Page? Yes, Options Page!

== Changelog ==

= 1.7.2 =
* adjusted data-findme to deal with ‘auto’ value
* added the must-be-one class to force one highlander element to stay open

= 1.7.1 =
* integrated new collapse-commander image trigger feature
* added new togglegroup shortcode attribute and data-togglegroup html attribute to reference groups of expand elements with out auto-collapsing other groups, such as when using the real attribute
* added the ability to load plugin css only when shortcode is used
* removed language files in favour of WordPress language packs
* fully tested to work with WordPress 4.5

= 1.7.0 =
* reworked the find-me feature to use new data-findme attribute, rather than create a hidden element.

= 1.6.15 =
* renamed language files to xx_XX.po and xx_XX.mo
* recreated the .pot file

= 1.6.14 =
* hash-bang (#!) that are used in urls will not be processed as url anchors
* now checks if a url anchor is actually an existing element before trying to process it 
* changed language domain to jquery-collapse-o-matic to work with WordPress’ new translation process
* updated tested up to tag to 4.3.1

= 1.6.13 =
* added map resize support for google maps builder

= 1.6.12 =
* removed css that changed the display to block on focus

= 1.6.11 =
* added css to remove the border on focus

= 1.6.10 =
* added styles and jQuery for accessibility: focus/keyboard navigation

= 1.6.9 =
* added catch-all callback for jetpack’s infinite-scroll
* fix: XSS Vulnerability

= 1.6.8 =
* fixed filter_content toggle bug.
* even if content is not to be filtered, it will still be passed through do_shortcode function

= 1.6.7 =
* added filter_content to the options page, defaults to off

= 1.6.6 =
* added filter attribute to pass the content through the_content filter
* changed name of wpex_clean_shortcodes function
* tested up to: WordPress 4.2-alpha-31047

= 1.6.5 =
* maptastic class only removed when present
* maptastic has a max-width assigned to prevent content from entering display area.
* updated collapse-commander integration to work with new licensing system
* tested up to: WordPress 4.2-alpha-31047

= 1.6.4 =
* added plugin to GitHub: https://github.com/baden03/collapse-o-matic
* added new tabindex attribute to shortcode and options page
* registration fields are masked using password input type

= 1.6.3 =
* added shortcode and html support for the title, swaptitle, startwrap, enwrap and excerpt attributes using placeholders
* added wpex_clean_shortcodes filter to strip unwanted p and br tags from the shortcode
* added the ability to set trigclass, targtag and targclass attribute values in the plugin options page

= 1.6.2 =
* added colomat-swap class to the swaptitle element
* added optional cid default to the shortcode

= 1.6.1 =
* fixed undeclared cid variable

= 1.6 =
* fixed issue with using id anchor links on elements with expanded=‘true’
* added support for Collapse Commander add-on plugin to manage expand elements

= 1.5.10 =
* change the find me element from an anchor to an input due to HTML5 no longer supporting the Name attribute
* fixed bug with trigpos inline
* added span_fix classes to css files
* added 4.0 icons to assets

= 1.5.9 =
* fixed issue with missing classes in dark style
* streamlined the collapse/expand function
* streamlined the collapseall/expandall functions
* added auto to scrollonclose attribute
* added new colomat_expandall and colomat_collpaseall functions that can be triggered externally

= 1.5.8 =
* load scripts only if shortcode is in use defaults to false - load all the time
* added option to choose where all scripts will load: header or footer

= 1.5.7 =
* option to only load scripts if shortcode is in use
* script now loaded in footer
* loading js and css now hooked to wp_enqueue_scripts rather than init
* added Serbian language files courtesy of Borisa Djuraskovic from webhostinghub.com

= 1.5.6 =
* fixed admin_init error

= 1.5.5 =
* updated for 3.8
* cleaned up old code

= 1.5.4 =
* updated .on method
* removed php4 constructor
* added DE and RU languages

= 1.5.3 =
* shortcode works in sidebar widgets (again)
* fixed hasClass error in Slide Only animation option

= 1.5.2 =
* find-me now is called only after collapse/expand animations
* using .on() method once again
* updated Requires at least tag to 3.3
* added swapexcerpt
* fixed typos on the options page
* added custom css to options page
* added targtag and targpos attributes
* added the ability to insert extra external triggers

= 1.5.1 =
* find-me auto feature improved to work with responsive websites

= 1.5 =
* completely re-wrote plugin
* added sweet-sweet options page
* added colomat-expand-only class that forces collapse element to only open once and stay open
* introduced Collapse-Pro-Matic for Premium Level Collapsing Action.
* added scroll-to-trigger class for Internal Collapse Triggers
* added livequery to bind collapse functions to dynamically added content
* added callbacks: collapse_init(); and findme(); for dynamically added content
* added language files for localization

= 1.4.11 =
* added colomat-parent-highlight class functionality
* moved findme auto calculations until after all images have loaded.
* Added elwraptag and elwrapclass attributes
* Added snap-shut class to slam collapse elements shut

= 1.4.10 =
* Added colomat-nolink class for anchor tags to prevent auto-scrolling to anchor
* bot- links for targets with swaptitle will now only swap out title for top trigger text

= 1.4.9 =
* Replaced on() event handler back to click due to the WordPress world not quite ready for on(). People are still using jQuery 1.6.4 - can you believe that?!

= 1.4.8 =
* Click events now use jQuery's on() event handler for content that arrived dynamically via AJAX NOTE: plugin now requires jQuery version 1.7 or newer
* added notitle attribute if no title should be displayed
* added startwrap and enwrap attributes for wrapping titles
* fixed bug when using nested close links and highlander grouping
* added expandanchor to expand items from anchor links on the same page
* added colomat-expand-only class to prevent collapse from trigger text
* added scrollonclose attribute and scroll-to-trigger class to trigger auto-scroll to specific value on close.

= 1.4.7 =
Fixed auto-expand of urls with id-anchors

= 1.4.6 =
* fixed JS error in 1.4.5 - GRRR

= 1.4.5 =
* Added SlideFade/SlideToggle option
* Visited trigers now have colomat-visited class

= 1.4.4 =
* Minified javascript for faster page loads

= 1.4.3 =
* Added the findme attribute to auto scroll to start of expanded items

= 1.4.2 =
* Added speed adjustment to the slideToggle

= 1.4.1 =
* Autoclose elements will now trigger SwapTitle

= 1.4 =
* Relaunched of plugin page on Twinpictures' Plugin Oven

= 1.3.18 =
* Added alternate style sheet for sites with dark backgrounds.

= 1.3.17 =
* Improved nesting collapse function for sub-levels and added support for nesting 30 levels deep.

= 1.3.16.1 =
* Added noarrows class for better integration of images as triggers.

= 1.3.16 =
* Bug Fix for the new trigpos attribute, stupid, stupid, stupid

= 1.3.15 =
* Added trigpos attribute to position the trigger below the target.

= 1.3.14 =
* Fixed the Enqueue error (thanks pborg & lancehudson)
* Expand All and Collapse All will now also trigger swap titles
* Added force_content_collapse class trigger to hide all targets w/o tirggers (roll-your-own)
* Added content_collapse_wrapper class trigger that keeps targets hidden if no JavaScript is present.

= 1.3.13 =
* Added the ability to place a second trigger link inside the expanding content area.

= 1.3.12 =
* Added the 'tracy patch' that will collapse all nested items if the parent is collapsed via highlander grouping.

= 1.3.11 =
* Added excerpt feature and all related attributes.

= 1.3.10 =
* Swaptitle system rebuilt to support images and cufon.
* Updated CSS the over generalized names close and hover to more plug-in specific colomat-close and colomat-hover
* removed !important from CSS arrow definitions to make it easier to redefine arrows.

= 1.3.9 =
* Swaptitle will not be triggered if either <img> or <cufon> tags are used in trigger titles
* Added rel grouping to the collapse/expand-all triggers to target only elements of a single group.

= 1.3.8 =
* Expand elements containing content with one empty space will no longer insert empty target elements.

= 1.3.7 =
* Added expandsub1 - expandsub3 shortcodes for nested expand elements.
* Added collapse/expand-all triggers.
* Better looking 24bit arrows.

= 1.3.6 =
* Plug-in now degrades gracefully if JS is disabled. Added swaptitle attribute to change the title on expand/collapse.

= 1.3.5 =
* Random ID's now start with letters, for valid xhtml. 

= 1.3.4 =
* Changed '-member' to '-highlander' because it sounds better. 

= 1.3.3 =
* Added the ability to have only one member of a group expanded by adding '-member' to the rel attribute value. 

= 1.3.2 =
* Added Expanded attribute that sets the default position expanded open.
* Added style that moves arrows to the right of the text.

= 1.3.1 =
* Added the hover style to pimp the element style when moused over.

= 1.3 =
* Added the rel attribute to deal with grouping collapse elements.
* Added the trigclass and targclass attributes.
* Added the alt attribute to define the title's hover-over text.

= 1.2 =
* Expanded the shortcode to include an optional tag attribute.  The system defaults to wrapping the trigger in a span tag.
* Style will support various element tags... span, div, h1, h2... etc.
* Style separates the arrow icons, so there is no background bleed-over on extra-long trigger titles.

= 1.1 =
* Optimized Code
* Shortcode now works in Sidebar
* Expand Titles now have title tags that match the title, not just some random number
* Shortcode can include a user-defined ID instead of a generated random number
* Added auto expand feature if an anchor tag is used in the URL

= 1.0 =
* The plug-in came to be.

== Upgrade Notice ==
adjusted data-findme attribute to work with auto value.
added new must-be-one class to force one highlander element to stay open

=== Tabby Responsive Tabs ===
Contributors: numeeja
Donate link: http://cubecolour.co.uk/wp
Tags: tabs, tab, responsive, accordion, shortcode
Requires at least: 3.7
Tested up to: 4.5
Stable tag: 1.2.3
License: GPLv2 / MIT

Create responsive tabs inside your posts, pages or custom post content by adding simple shortcodes inside the post editor.

== Description ==

* Adds a set of horizontal tabs which changes to an accordion on narrow viewports
* Tabs and accordion are created with jQuery
* Supports multiple sets of tabs on same page
* Uses Semantic header and Content markup
* Aria attributes and roles aid screen reader accessibility
* Tabs and content are accessible via keyboard

The Tabby Responsive Tabs plugin is designed to be an easy and lightweight way to add responsive tabs to your content. There is no admin panel and experienced developers should be able to easily customise how the tabs display on their site by replacing the built-in CSS rules with a customised version (see note below for more details of this).

= Optional Add-ons =
> The [Tabby Responsive Tabs Customiser](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser") add-on adds a settings panel with several parameters for customising your tabs. to provide the easiest way to customise the display of your tabs without editing any code. You can use the default tabby styles or one of the included one-click presets as a starting point for customisation. It also enables you to easily add icons to your tab titles.

> The [Tabby Link to Tab](http://cubecolour.co.uk/downloads/tabby-link-to-tab/ "Tabby Link to Tab") add-on provides a simple shortcode to create links to specific tabs which can appear anywhere on the same page as the tabgroup without the page needing to reload.

> The [Tabby Tab to URL Link](http://cubecolour.co.uk/downloads/tabby-tab-to-url-link/ "Tabby Tab to URL Link") add-on enables you to set one or more of your tabs to act as a link to any URL.

> The [Tabby Load Accordion Closed](http://cubecolour.co.uk/downloads/tabby-load-accordion-closed/ "Tabby Load Accordion Closed") add-on changes the default behaviour when the tabs are displayed as an accordion so no accordion sections are open when the page initially loads.
= Usage: =

There are two shortcodes which should both be used `[tabby]` and `[tabbyending]`

`[tabby title="tabname"]`

*replace tabname with the name of your tab.*

Add the tab content after the shortcode.

Add a `[tabbyending]` shortcode after the content of the last tab in a tabgroup.

= Example =
*If you copy & paste this example into your own page instead of typing them, ensure that any stray &lt;code&gt; or &lt;pre&gt; tags are deleted.*

`

[tabby title="First Tab"]

Tabby ipsum dolor sit amet, kitty sunbathe dolor, feed me.

[tabby title="Second Tab"]

Lay down in your way catnip stuck in a tree, sunbathe kittens.

[tabby title="Third Tab"]

sleep in the sink climb the curtains attack, give me fish.

[tabbyending]

`

*note: To prevent stray paragraph tags being introduced by WordPress's wpautop filter, ensure that you leave a blank line above and below each tabby shortcode and the tabbyending.*

You can see the tabs on the [demo page](http://cubecolour.co.uk/tabby-responsive-tabs/ "Tabby Responsive Tabs demo").

If you want to change how the tabs and accordion display on your site, you have two options:

1. Use the [Tabby Responsive Tabs Customiser](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser") plugin which provides a very easy way to customise the display of your tabs without needing to edit any code.

2. Copy the contents of the plugin's stylesheet into your child theme or custom styles plugin and make the changes to the copy as required. If you do this you will also need to prevent the built-in styles from loading by adding the following line to your child theme's functions.php or a custom functionality plugin:

`<?php remove_action('wp_print_styles', 'cc_tabby_css', 30); ?>`

= Additional Shortcode attributes =

**Open**

The first (leftmost) tab panel will be open by default in 'tab view' and in 'accordion view'.

If you want a specific tab other than the first tab to be open by default when the page first loads, you can add the parameter & value **open="yes"** to the shortcode for that tab:

`
[tabby title="My Tab" open="yes"]
`

If you use the 'open' shortcode parameter in one of your tab shortcodes, ensure that you only add it to single tab as having more than one tab open within a tab group is not supported.

**Icon**

The markup required to show an icon alongside a tab title can be added by using the **'icon'** attribute. Tabby responsive tabs does not add the icons files, you will also need to use a theme or plugin (such as the tabby responsive tabs customiser add-on) to add the icon files:
`

[tabby title="My Tab" icon="cog"]

`
This adds a pseudo element before the tab title with the classes "fa" and "fa-cog". Other icon font sets can be used if you ensure the CSS rules target the classes added by the plugin.

The [Tabby Responsive Tabs Customiser](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser") plugin can be used to add the Font Awesome files required to display the icons in the tab titles.


= Controlling which tab is open when linking to the page =
You can use a 'target' URL parameter to set which tab will be open when the page initially loads. The value of this parameter is based on the tab title specified in the tabby shortcode, but formatted with punctuation & special characters removed, accents removed, and with dashes replacing the spaces.

If you want to link to a 'contacts' page with a tab titled 'Phone Numbers' open, the url you use to link to this page would look like:
`
yoursite.com/contact/?target=phone-numbers

`
If you want a tab with the title 'email addresses' to be open, the url would look like:
`
yoursite.com/contact/?target=email-addresses
`
If you want a tab with the title 'entr&eacute;es' to be open (with an acute accent over the second e), the url would look like:
`
yoursite.com/contact/?target=entrees
`
== Installation ==

1. Upload the Tabby Responsive Tabs plugin folder to your '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why isn't it working for me? =

There are a few things you can investigate when troubleshooting any plugin which is not working as expected:

**Incorrectly formed shortcodes**
If you copied &amp; pasted in the Tabby Responsive Tabs shortcodes from a web page showing an example usage rather than directly typing them into the page, it is possible that there may be invisible or invalid characters in the shortcode text, or the shortcodes are enclosed within code tags. Correct this by deleting the shortcodes and type them directly instead.

**Plugin or theme conflicts**
To troubleshoot whether you have a plugin or theme conflicting with the Tabby Responsive Tabs plugin, switch to a default theme such as Twenty-Thirteen. If the plugin starts working correctly at that point, you know that the theme needs to be investigated.

If changing the theme makes no difference, deactivate all plugins except Tabby Responsive Tabs. If your Tabs appear correctly at that point, discover which plugin caused the issue by reactivating the plugins one by one until Tabby Responsive Tabs stops working again.

*If the plugin isn't working for you*, please read the documentation carefully to check whether your issue is covered. Then review the topics in the [plugin support forum](http://wordpress.org/support/plugin/tabby-responsive-tabs/ "Tabby Responsive Tabs plugin support forum"). You may find an appropriate solution outlined in a resolved topic if someone had the same or a similar issue. If you do not find an answer that enables you to solve your issue, please post a new topic on the forum so we have an opportunity to get it working before you consider leaving a review.

= What levels of support are available? =
You can receive free support for the plugin if you have problems getting it working. To access this please open a new topic on the [plugin support forum](http://wordpress.org/support/plugin/tabby-responsive-tabs/ "Tabby Responsive Tabs plugin support forum") all communication must take place on the forum for free support

If you require a greater level of support than can be provided on the plugin support forum on WordPress.org - eg you prefer not to post the url or you require CSS support to fit specific requirements for your site, you can request non-free support via the [paid email support form for cubecolour plugins](http://cubecolour.co.uk/premium-support/ "paid email support form for cubecolour plugins") form.

= How can I remove extra paragraph tags which appear at the beginning or end of the tab content? =
These extra tags are often be added by WordPress's wpautop function. It is recommended to leave a blank line before and after each tabby shortcode to prevent these from appearing.

= Pasted-in shortcodes aren't working or the tabs have a 'stepped' appearance =
If you are copying & pasting the example shortcodes into the visual editor and the shortcodes don't seem to be working or the tabs appear in a stepped configuration, look at the page in the text editor to be sure that you aren't adding in any extra markup that isn't visible in the visual editor. Delete any opening and closing &lt;pre&gt; and/or &lt;code&gt; tags pairs surrounding the tab shortcodes. (this would apply to any plugin using shortcodes).

= Where is the plugin's admin page? =

There isn't one. This is a lightweight plugin with no options. If you want to be able to customise your tabs using an admin page, the [Tabby Responsive Tabs Customiser plugin](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser plugin") is available.

= Does this plugin work with responsive Themes? =

Yes - it should work with any well-coded responsive theme.

= Does it work with non-responsive Themes? =

The plugin should also work with non-responsive Themes, however this is not really recommended; if you are using a non-responsive theme the tabs may not switch to an accordion display on a mobile device.

= Does it work with a multisite installation of WordPress? =

Yes

= How can I change the colours? =

The recommended method for experienced developers to customise how the tabs display is to copy the css rules from the plugin's stylesheet into the child theme's stylesheet and then customise the colours and other CSS as required. When using customised version of the plugin's styles in the child theme, you should also prevent the plugin's default built-in styles from loading by adding the following line to the child theme's functions.php (or a custom functionality plugin):
`<?php remove_action('wp_print_styles', 'cc_tabby_css', 30); ?>`

If you prefer to use a settings page in your WordPress admin to set a custom tab style, you can use the [Tabby Responsive Tabs Customiser plugin](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser plugin") which contains several tab style presets which can be further customised with a comprehensive set of easy to set options. The cusomiser plugin was designed to be easy for non-developers to use to customise how the tabs display.

= Can I change the responsive breakpoint from the default 767px? =

Yes, you can see where that is set in the plugin's CSS. Refer to the answer above about using custom css to use a custom value.

This value can also be set using the Tabby Responsive Tabs Customiser plugin's admin panel.

= Why Doesn't my slider or (non native WP) gallery work in any tab except the first one? =

Some carousel/slider/gallery plugins render their content with zero height & width if the tab containing the content is not visible on page load. If you need to place a slider in a tab, I would suggest trying Meteor Slides as it seems to work reliably in my tests. Native WordPress galleries also work with no problems.

= How can I get rid of unmatched opening or closing paragraph tags in the tabs making my markup invalid? =

This is caused by WordPress's wpautop filter which is applied to your post/page content. To prevent stray paragraph tags appearing, ensure that you leave a blank line before and after each shortcode.

= Why Doesn't my Google Map work in any tab except the first one? =

Some google maps plugins render their content with zero height & width if the tab containing the content is not visible on page load. I have been able to show maps within tabs using the [WP Flexible Map](https://wordpress.org/plugins/wp-flexible-map/ "WP Flexible Map") plugin.

= Can I display multiple tab groups on a single page? =

Yes you can have as many sets of tabs as you like.

= Can I include tabs in my sidebar? =

It is possible to include tabs within a text widget if you have added shortcode support to text widgets by adding the filter below to your child theme's functions.php or a custom functionality plugin.

`
add_filter('widget_text', 'do_shortcode');
`
This filter will enable you to use any shortcodes within text widgets.


= Can I nest a tag group within an existing tab? =

No, this is not supported.

= Can I specify which tab is open when the page initially loads? =

Yes, see the documentation for the 'open' shortcode parameter for details.

= Can I specify which tab is open from a link pointing to the page =

Yes, see the documentation for the usage of a 'target' URL parameter in the link.

= I've just updated the plugin and the tabs are now displaying differently =

The default CSS has changed in version 1.0.2 and version 1.1.0. If your tabs now appear 'broken' after an update, this may be due to your site using customised tab styles added to your theme instead of following the recommended method of replacing the default CSS with a complete customised version. To fix this remove the CSS rules you added to your theme to target the tabs and add the custom styles using the method outlined in the documentation.

= Can you create a customised stylesheet for me to fit in with the colours or style of my website? =

Site-specific customisation work is beyond of the scope of the free support I am able to provide. I am happy to take on CSS work as a paid job or you can style your tabs to match your theme using the optional [Tabby Responsive Tabs Customiser](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser") add-on plugin.

Plugin support for Tabby Responsive Tabs is provided at the [plugin's support forum](http://wordpress.org/support/plugin/tabby-responsive-tabs/ "Tabby Responsive Tabs plugin support forum") on WordPress.org.

= Why can't I get the Target Parameter to Work? =

This is used just like any other URL parameter in a query string so you need to use a valid structure for the query string.

If there's already a parameter in a query string, including the one included in the url when using default ugly permalinks, subsequent parameters must be appended using an ampersand.
eg:

`
yoursite.com/?page_id=1&target=phone-numbers
`

= How can I use the Target Parameter on a link on the same page as the tabgroup without the page reloading? =

This is not possible with the target parameter, however this can be achieved by using the optional [Tabby Link to Tab plugin](http://cubecolour.co.uk/downloads/tabby-link-to-tab/ "Tabby Link to Tab plugin")

= How will the tabs print? =

Basic print styles are included in a print media query at the end of the plugin stylesheet (from version 1.2). This is designed to print the tab titles and content in series. If you are using custom styles for your tabs, you can copy these print styles to the end of your custom styles and customise them.

= Are there any other free cubecolour plugins? =

If you like Tabby Responsive Tabs, you may like some of my other plugins in the WordPress.org plugins directory. These are listed on my [profile](http://profiles.wordpress.org/numeeja/ "cubecolour profile") page under the 'plugins' tab.

= Who or what is cubecolour? =

My name is Michael Atkins. Cubecolour is the name of my web design and development business in South London where I work with businesses, organisations and individuals to build and support their websites using WordPress. I enjoy attending local WordCamps and WordPress meetups. I have used WordPress since 2007 and I am a moderator on the WordPress.org support forums. When I'm not sitting in front of my MacBook I can usually be found playing bass or ukulele.

= Why do you spell the word 'color' incorrectly? =

I don't, I'm from England and 'colour' is the correct spelling.

= I am using the plugin and love it, how can I show my appreciation? =

You can donate any amount via [my donation page](http://cubecolour.co.uk/wp/ "cubecolour donation page") or you could purchase a copy of the [Tabby Responsive Tabs Customiser plugin](http://cubecolour.co.uk/tabby-responsive-tabs-customiser/ "Tabby Responsive Tabs Customiser plugin").

If you find Tabby Responsive Tabs useful, I would also appreciate a review on the [plugin review page](http://wordpress.org/support/view/plugin-reviews/tabby-responsive-tabs/ "Tabby Responsive Tabs plugin reviews")

= Is the Tabby Responsive Tabs Customiser a Premium or Pro Version of Tabby Responsive Tabs? =

No, Tabby Responsive Tabs works great on its own and customising how the tabs display should be straightforward for anyone comfortable with editing a child theme. The Tabby Responsive Tabs Customiser plugin is an add-on which is designed to be useful for anyone who wants an easy way to customise how their tabs display without touching any code.

= Why is the Tabby Responsive Tabs Customiser an add-on plugin rather than part of Tabby Responsive Tabs? =

The free Tabby Responsive Tabs plugin was designed as a lightweight plugin for WordPress developers to add responsive tabs to their WordPress site. The functionality provided by the optional add-on customiser plugin was never intended to be included as part of Tabby Responsive Tabs and is designed to be particularly useful for non-coders.

= What is the Tabby Link to Tab plugin? =

Tabby Link to tab is an optional add-on for Tabby Responsive Tabs which provides a simple shortcode to create links to specific tabs which can appear anywhere on the same page as the tabgroup. When this is used, the tab becomes active without the page reloading. This add-on is not required in most cases but can be useful if you want to include links to specific tabs within the tab content or in a different area of the page.

For more details please see: [Tabby Link to Tab plugin](http://cubecolour.co.uk/downloads/tabby-link-to-tab/ "Tabby Link to Tab plugin"). This add-on was developed after several users requested the functionality.

= How much do the Tabby Responsive Tabs Customiser & Tabby Link to Tab add-ons cost? =

These cost 19GBP each for use on a single site. A developer option for each is also available or 79GBP which can be used on all the sites you own or control.

== Screenshots ==

1. On a desktop browser the content is displayed within tabs.
2. When the browser width is below the size set in the media query, the tabs are replaced by an accordion.
3. The basic print styles are intended to present the tab titles & content appropriately when printed out.

== Changelog ==

= 1.2.3 =

* Enable targeting the tab from url query string when the title contains an accent

= 1.2.2 =

* Included print stylesheet as a separate file

= 1.2.1 =

* Added index.php to prevent the content of plugin directories being viewed if the site has not had directory browsing disallowed.

= 1.2.0 =

* Added basic print styles to default stylesheet

= 1.1.1 =

* Improvements to default CSS
* Addition of 'open' shortcode attribute to allow tabs other than the first to be open when the page loads
* First tab now is open by default when displayed as accordion
* Changed links in plugin table
* Get Plugin Version function
* Prevent tabs overlapping if there are too many
* Remove hard coded paragraph tags in tab content & improve
* Added icon font support. Note: Font Awesome needs to be loaded by your theme or another plugin (including the Tabby Responsive Tabs Customiser)
* Added functionality to allow target url parameter to control which tab is open on page load.

= 1.0.3 =

* improved theme compatibility with default css

= 1.0.2 =

* enqueue plugin js only when needed
* css for improved specificity

= 1.0.1 =

* Updated js & css

= 1.0.0 =

* Initial Version

== Upgrade Notice ==

= 1.2.3 =

* Enable targeting the tab from url query string when the title contains an accent

= 1.2.2 =

* Included print stylesheet as a separate file

= 1.2.1 =

* Added index.php to prevent the content of plugin directories being viewed if the site has not had directory browsing disallowed.

= 1.2.0 =

* Added print styles to default stylesheet

= 1.1.1 =

* Added Support for Tabby Responsive Tabs Customiser add-on
* Further improved theme compatibility with default css
* Control which tab is open on page load using short code parameter or url parameter
* Font Awesome icon support in tabs

= 1.0.3 =

* improved theme compatibility with default css

= 1.0.2 =

* improved efficiency - enqueue plugin only when needed
* improved theme compatibility with default css

= 1.0.1 =

* Updated js & css

= 1.0.0 =

* Initial Version
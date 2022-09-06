=== Popularity Posts Widget ===
Contributors: Mihail Barinov
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GSE37FC4Y7CEY
Tags: widget, widgets, post, posts, blog, popular posts, popular, plugin, plugins, sidebar, popularity, most viewed, views, most popular, image, images, sidebar,page, pages, wordpress, visits, visitors, count, counter, recent, recent posts, thumbnail, thumbnails, post thumbnail
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 1.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

With help of this plugin you can display the most popular posts on your blog. 

== Description ==

Want something to say - write me to *mih.barinov at gmail dot com* 

This plugin allows you to show your most popular posts as a widget on your blog!

= Main Features =

* **Time Range** - You can choose a specific time range (today, last 7 days, last 30 days, all time) to sort youre posts and know the most popular of them within that period
* **Flexibel Display Settings** - How many posts to display? Whats the posts title length must be? Should views, comments or dates displayed in every post in list? To all this quastions you can answer witch help of flexible widget settings!
* Display a **thumbnail** of your posts!
* **Categories Filter** - Should youre posts displays from all categories or only from specific one?
* **Easy to Change CSS Styles** - ppw.css file in youre full possession. Do with him all what you want!
* **Shortcode and Template tag support** - use the [ppw] shortcode or *ppw_get_popular_posts()* template tag to showcase your most popular posts. For detail information, please refer to the [faq section](http://wordpress.org/extend/plugins/popularity-posts-widget/faq/).

== Installation ==

1. Upload `popularitypostswidget` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enable the Widget through the Appearance --> Widgets tab.
4. Edit the widget Settings in your widget Administration Panel.

== Frequently Asked Questions ==

= How does this work? =

It stores the user details every time a post is viewed, the totals are incremented so it will show the posts with the most hits in the widget.

= Why are no posts displaying when I use the widget? =

99% of the time this is because no posts have been visited yet. Simply click on a blog post to read it on your blog and it will display in the list of most popular posts.

= How does Popularity Posts Widget select my posts thumbnails? =

First of all plugin will try and use the Featured Image you have selected for each of your posts and use it to create a thumbnail. If none is set, Popularity Posts Widget will search for fist image of each post to create thumbnails. If there is no images in youre post, then "no_photo" image will be used instead. 

= I want help you to translate this plugin into my language. How can I do this? =

Simple take PO file from languages folder and translate it into youre language witch help of some programs, such as [Poedit](http://www.poedit.net/). Then send me youre PO and MO files to *mih.barinov at gmail dot com*. 

= Can I request a feature? =

Yes, please do so on the WordPress support forum for the plugin. I will consider it and if I feel it is worth adding, I will schedule it for a future release.

= How can I use shortcode or template tag? =

You can use shortcode [ppw] for showing most popular posts in youre pages or posts. It can be used with some options, that you can see below, or without them (in this case will be used defaults ones).

*Usage:*

`[ppw]`

`[ppw range=all stats_views=1]`

If you can't or don't want to use widget - *ppw_get_popular_posts()* template tag is for you. Like and shortcode, it can be use with options or without them.

*Usage:*

Without any parameters:

`<?php if (function_exists('ppw_get_popular_posts')) ppw_get_popular_posts(); ?>`

Using parameters:

`<?php if (function_exists('ppw_get_popular_posts')) ppw_get_popular_posts("range=all&stats_views=1"); ?>`

*Here is the list of available options:*

`Parameter			  Description							 Possible values  

*head.................Sets a heading for the list............Text string
*range................Time period for sorting: all time,.....'all'(default),
*.....................last week, last month, today...........'weekly','monthly','today'
*limit................Maximum number of posts to show........Positive integer
*title_length.........Short each post title to 'n'...........Positive integer
*.....................characters.............................
*stats_comments.......Show comment count(1) or don't.........1 (true), 0 (false)
*.....................show(0) for each post..................	
*stats_views..........Show views count(1) or don't...........1 (true), 0 (false)
*.....................show(0) for each post..................	
*stats_date...........Show date(1) or don't show(0)..........1 (true), 0 (false)
*.....................for each post..........................
*stats_date_format....Sets the date format(example:..........Text string
*.....................'F j, Y', 'Y/m/d', 'm/d/Y')............
*category.............If set, plugin will retrieve...........Text string
*.....................all entries that belong to the......... 
*.....................specified category(ies) ID(s)..........
*thumbnail_width......If set, will display thumbnails........Positive integer
*.....................for each post. This attribute.......... 
*.....................sets the width for thumbnails..........
*thumbnail_height.....If set, will display thumbnails........Positive integer
*.....................for each post. This attribute..........
*.....................sets the height for thumbnails.........`
					 
== Screenshots ==

1. Popularity Posts Widget witch thumbnails
2. Popularity Posts Widget on theme's sidebar
3. Popularity Posts Widget settings

== To-Do List ==

This is the list of some features, that I planning to add in next versions of plugin. Please [donate](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GSE37FC4Y7CEY) to help me make all this things.

* New styles for plugin.
* Unique visitors filter.
* Compatibility with caching plugins (such as WP Super Cache/W3 Total Cache).
* Compatibility for external images.

If you have some other suggestions you can mail me to *mih.barinov at gmail dot com*. 

== Changelog ==

= 1.13 =
* Add shortcode and template tags support
* New features for cat filter
* Some small fixes in code

= 1.12 =
* Another fix of categories filter. Now it must work proper

= 1.11 =
* Fix bug in categories filter
* Fix some issues witch thumbnails

= 1.10 =
* Thumbnail feature added
* New file - uninstall.php, that clear all generated data when delet plugin

= 1.00 =
* First Release
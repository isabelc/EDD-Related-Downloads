=== Easy Digital Downloads - Related Downloads ===
Contributors: isabel104, eliorivero
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40isabelcastillo%2ecom
Tags: EDD, related downloads, easy digital downloads, related posts, download category, download tag, downloads categories, downloads tags, related items, related products
Requires at least: 3.8
Tested up to: 4.2.3
Stable Tag: 1.6.1
License: GNU Version 2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically show related downloads by tag or category when using Easy Digital Downloads plugin.

== Description ==

This is an extension for [Easy Digital Downloads](http://wordpress.org/plugins/easy-digital-downloads/) that automatically adds related downloads after the single download content on the single download page. It is simple and light. It works by default without a need for any settings. By default, 3 related downloads will be shown, related by download_tag. The title and featured image will be displayed, centered nicely. The related downloads are added right below the content of the single download.

**Optional - use it as widget**
It also adds a Related Downloads widget. You can use the widget if you prefer, and disable the automatic related downloads under the content.


 - Unobtrusive Styling - Fits right into your theme
 - Responsive CSS is included so it looks good on any size screen.

**Works automatically. No settings needed.**

This plugin has 6 optional settings. These options are located at `Downloads --> Settings --> Extensions tab`.

1. Filter by Tag or Category
2. Change the number of related items to show
3. Custom Related Downloads Title
4. Disable Related Downloads From Being Added to Content
5. Change The Default Method of Sorting (Orderby)
6. Change The Default Sort Order

For more info, see the [Documentation](http://isabelcastillo.com/docs/category/easy-digital-downloads-related-downloads-wordpress-plugin).

For support or to report bugs, use the support forum link above.

Fork on [GitHub](https://github.com/isabelc/EDD-Related-Downloads).

Please [rate the plugin](http://wordpress.org/support/view/plugin-reviews/easy-digital-downloads-related-downloads). Thank you.

**Translations**

Includes a `.pot` file so you can easily translate into other languages.



== Installation ==

1. Unzip `easy-digital-downloads-related-downloads.zip` directly into the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress. That's it. At this point, related downloads, if any, will appear underneath each download.
3. Optional: Go to `Downloads --> Settings --> Extensions tab` to change the default settings.
4. Optional: Use the widget at `Appearances --> Widgets`.

== Frequently Asked Questions ==

= Why aren't there any related downloads showing up on my single download page? =
Most likely, there are no other downloads that share that download's tag or category.

= I don't want them below my content. Can I use a template tag in single-download.php to position the related downloads wherever I want? =
Yes. Use WordPress's function `the_widget`. If you want the default output, use:

`the_widget('edd_related_downloads_widget');`

If you want to specify parameters for this instance, use:
`
	$inst = array( 
		'title' => 'You May Also Like',
		'number' => 3,
		'taxcat' => false,
	);
	the_widget('edd_related_downloads_widget', $inst, $args);`


However, the 2 above will style it just like the sidebar widget, which is list-style. If you want this to appear grid-style, like the default Related Downloads that get added below the content, you have to add the `$args` parameter, like so (you can change the $inst, but not the $args if you want grid-style):



`	$inst = array( 
		'title' => 'You May Also Like',
		'number' => 3,
		'taxcat' => false,
	);
	$args = array(
		'before_widget' => '<div id="isa-related-downloads" class="widget">',// make it grid-style
		'after_widget' => '</div>',
	);
	the_widget('edd_related_downloads_widget', $inst, $args);
`

= Can I use a custom image size for the featured image? =

Yes, [see this](http://isabelcastillo.com/docs/different-size-featured-image-edd-related-downloads)

= Can I use custom images instead of the featured image for the Related Downloads? = 

Yes, [see this](http://isabelcastillo.com/docs/use-custom-images-instead-of-featured-image-for-related-downloads).

== Screenshots ==

1. Settings at Downloads -> Settings -> Extensions tab

== Changelog ==

= 1.6.1 =
* New - A new filter, edd_related_downloads_image_size, lets you modify the image size retrieved for the featured image. Thanks to eliorivero.
* New - Checks if the image URL has the correct scheme in SSL environments. Thanks to eliorivero.

= 1.6 =
* Maintenance - updated widget args to work with the WordPress 4.0 live widget customizer.
* Maintenance - Removed several PHP warning notices.

= 1.5.1 = 
* New: the textdomain for translations has changed to the plugin slug in order to meet WordPress standards. You must update your .mo and .po filenames with the new textdomain - easy-digital-downloads-related-downloads_. Old .mo and .po language files have been removed.
* New: moved the settings from the "Downloads -> Misc" tab to "Downoads -> Extenstions" tab.
* New: added width and height attributes to related images for increased page loading speed.
* New: singleton class.
* Maintenance: Tested and passed for WP 3.9 compatibility.

= 1.5 =
* New: added option to choose sort order and orderby parameters.
* Tested for WP 3.8 compatibility.

= 1.4.9 =
* Tested for WP 3.7.1 compatibility.

= 1.4.8 =
* Tweak: Now compatible with EDD Hide Download plugin.
* Minor fix: link to Documentation was incorrect
* Tweak: removed rate link. Added link to Docs instead.

= 1.4.7 =
* New: added filter to image src so you can filter it to use custom promo images instead of the regular featured image.
* Tweak: minified CSS file
* Minified readme since added link to Instruction Guide.
* Tested for WP 3.6.1 compatibility

= 1.4.6 =
* Update: compatible with WP 3.6

= 1.4.5 = 
* Tweak: Added compatibility with EDD Changelog plugin, moved related downloads down below the changelog.
* Tweak: removed extend from plugin URI.


= 1.4.4 = 
* New: added CSS for grid-style widget
* Updated translation files.

= 1.4.3 = 
* Fixed discrepancy with the Widgets Pack extension by Matt Varone
* Tweak: added clearfix to sidebar widget
* Tweak: removed itemprop="name" from titles of related downloads, so as to not interfere with main title

= 1.4.2 = 
* Tweak: widget title will not be displayed if no related downloads exist.
* New: ability to disable related downloads from being added to bottom of content, useful when using sidebar widget instead.
* New: added translations for French, Portuguese, Spanish, as well as `.pot` file

= 1.4.1 = 
* Fixed incorrect version tagged as stable.

= 1.4 = 
* Minor tweaks to readme. Changed plugin url, added donate link, added rate it link.

= 1.3 = 
* New: added related downloads widget

= 1.2 =
* Tweak: Gave late priority to related downloads action to ensure that related items proceed after any other inserted stuff by other plugins


= 1.1 =
* Cosmetic tweak: Centered misaligned titles.

= 1.0 =
* New: Added setting - How many related items to show.
* New: Added setting - Custom Related Downloads Title.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 1.6.1 =
Adds new image size filter and checks for ssl.

= 1.6 =
Updated widget to work with the WordPress 4.0 live widget customizer.

= 1.4.7 =
Tweak: Gave late priority to related downloads action to ensure that related items proceed after any other inserted stuff by other plugins.

= 1.5 =
New: option to choose sort order and orderby parameters.

= 1.5.1 =
Moved the settings from the "Downloads -> Misc" tab to "Downoads -> Extenstions".
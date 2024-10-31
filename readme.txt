=== Schema Breadcrumbs ===
Contributors: deano1987
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MPJDRVYM87ZR4
Tags: breadcrumb, schema, semantic, google, seo, RDFa, semantic, breadcrumbs
Requires at least: 2.2
Tested up to: 6.5.2
stable tag: 2.1.4

Very easily add breadcrumbs to your site with valid Schema Breadcrumb Markup, this plugin is also a drop-in replacement for RDFa Breadcrumb, just install this and deactivate RDFa Breadcrumb.

== Description ==

Easily add schema valid breadcrumbs to your site. If you're using RDFa Breadcrumb or Yoast Breadcrumb, this plugin automatically takes it's place, if your using a supported WordPress framework, it's as easy as enabling the plugin and checking the "Try to add automatically box", if you're not using one of those, adding it is as simple as adding one line of code to your template!

= Supported Frameworks =
Currently the breadcrumbs plugin supports the following frameworks out the box:

* Thematic
* Hybrid
* Thesis
* WP Framework

And is a drop-in replacement for:

* RDFa Breadcrumbs
* Yoast Breadcrumbs

If you dont currently use RDFa Breadcrumb, Yoast Breadcrumbs or a supported framework then no problem, its very easy to add the breadcrumbs output to your templates, just simply call the below function where you want breadcrumbs to output:

schema_breadcrumb();

Alternatively you can call the breadcrumbs with a shortcode:

[schema_breadcrumb]

== Changelog ==

= 2.1.4 =
* Plugin clash fix

= 2.1.2 =
* PHP 8.1 compatibility fix

= 2.1.1 =
* Fixed a bug with Gutenberg editor

= 2.1.0 =
* PHP 8 compatibility
* Added ability to remove final crumb from display

= 2.0.0 =
* added shortcode ability and latest wordpress support

= 1.9.1 =
* fixed issue with multiple cats

= 1.9.0 =
* add woocommerce categories to product pages


= 1.8.3 =
* Fixes

= 1.8 =
* Fixed issue with categories schema

= 1.6 =
* overhauled how breadcrumbs is generated and outputted

= 1.5.2 =
* escaping breadcrumb names in schema to avoid errors when text has quotes

= 1.5.1 =
* fixed some bugs with custom taxonomies

= 1.5.0 =
* fixed some bugs with blog categories

= 1.4.9 =
* custom page_for_posts url now supported.

= 1.4.8 =
* fixed small issue in schema.

= 1.4.6 =
* fixed another small issue with custom taxonomys.


= 1.4.5 =
* fixed issue with custom taxonomy.

= 1.4.4 =
* fixed serious bug apologies!

= 1.4.3 =
* added url to JSON+LD
* breadcrumbs now support custom taxonomys

= 1.4.2 =
* Fixes to JSON+LD Schema Breadcrumbs

= 1.4.1 =
* You can now set the Tag prefix

= 1.4.0 =
* Now uses JSONLD

= 1.3.1 =
* Incorrect breadcrumb issue fixes

= 1.3.0 =
* Admin panel improvements
* You can now decide to enable/disable the "blog" crumb depending on your setup.
* Added support for a drop-in replacement of Yoast Breadcrumb


= 1.2.1 =
* Admin panel improvements

= 1.2.0 =
* RDFa Breadcrumbs replacement

= 1.1.0 =
* Global Prefix and suffix
* Prefix just for pages/blogs/categories
* Fixed small problem for categories
* Ability to replace the RDFa breadcrumbs function

= 1.0.0 =
* Initial release


== Installation ==

* Upload the plugin to your plugins directory
* Activate the plugin
* Check the options in Settings > Schema Breadcrumbs
* Enable automatic if you have a supported theme

--OR--

* Paste the code below into the templates where you want to display the breadcrumbs

schema_breadcrumb();

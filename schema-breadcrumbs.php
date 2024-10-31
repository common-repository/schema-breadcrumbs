<?php /*
Plugin Name:  Schema Breadcrumbs
Plugin URI:   http://webdesires.co.uk
Description:  Outputs a fully Schema valid breadcrumb
Version:      2.1.4
Author:       Dean Williams
Author URI:   http://deano.me

Copyright (C) 2008-2010, Dean Williams
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of WebDesires, Dean Williams nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/

// Load some defaults
$opt 						= array();
$opt['home'] 				= "Home";
$opt['blog'] 				= "Blog";
$opt['knowledge'] 			= "Knowledge Base";
$opt['portfolio'] 			= "Portfolio";
$opt['sep'] 				= "&raquo;";
$opt['prefix']				= "<div class='breadcrumb breadcrumbs'><p>";
$opt['suffix']				= "</p></div>";
$opt['boldlast'] 			= true;
$opt['blogparent'] 			= true;
$opt['removefinal'] 			= false;
$opt['nofollowhome'] 		= false;
$opt['nofollowcurrent'] 		= false;
$opt['singleparent'] 		= 0;
$opt['singlecatprefix']		= true;
$opt['normalprefix'] 		= "You are here:";
$opt['archiveprefix'] 		= "Archives for ";
$opt['tagprefix'] 		= "Tag: ";
$opt['searchprefix'] 		= "Search for";
add_option("schema_breadcrumbs",$opt);

if ( ! class_exists( 'WDSBPanelAdmin' ) ) {
	require_once('WDPanelAdmin.php');
}
if ( ! class_exists( 'Schema_Breadcrumbs_Admin' ) ) {
	class Schema_Breadcrumbs_Admin extends WDSBPanelAdmin {

		var $hook 		= 'schema-breadcrumbs';
		var $longname	= 'Schema Breadcrumbs Configuration';
		var $shortname	= 'Schema Breadcrumbs';
		var $filename	= 'breadcrumbs/schema-breadcrumbs.php';
		var $ozhicon	= 'script_link.png';

		function config_page() {
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Schema Breadcrumbs options.'));
				check_admin_referer('schema-breadcrumbs-updatesettings');

				foreach (array('home', 'blog', 'knowledge', 'portfolio', 'sep', 'singleparent', 'prefix', 'suffix', 'archiveprefix', 'tagprefix', 'normalprefix', 'searchprefix', 'breadcrumbprefix', 'breadcrumbsuffix') as $option_name) {


					if (isset($_POST[$option_name])) {
						$opt[$option_name] = htmlentities(html_entity_decode($_POST[$option_name]));
					}
				}

				foreach (array('boldlast', 'blogparent', 'removefinal', 'nofollowhome', 'nofollowcurrent', 'singlecatprefix', 'trytheme') as $option_name) {
					if (isset($_POST[$option_name])) {
						$opt[$option_name] = true;
					} else {
						$opt[$option_name] = false;
					}
				}

				update_option('schema_breadcrumbs', $opt);
			}

			$opt  = get_option('schema_breadcrumbs');
			?>
			<div class="wrap">

				<h2>Schema Breadcrumbs Configuration</h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">
						<div class="meta-box-sortables">
							<form action="" method="post" id="schemabreadcrumbs-conf">

								<?php if (function_exists('wp_nonce_field'))
										wp_nonce_field('schema-breadcrumbs-updatesettings');

								$rows = array();
								$rows[] = array(
									"id" => "sep",
									"label" => __('Separator between breadcrumbs'),
									"content" => '<input type="text" name="sep" id="sep" value="'.htmlentities($opt['sep']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "home",
									"label" => __('Anchor text for the Homepage'),
									"content" => '<input type="text" name="home" id="home" value="'.stripslashes($opt['home']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "blog",
									"label" => __('Anchor text for the Blog'),
									"content" => '<input type="text" name="blog" id="blog" value="'.stripslashes($opt['blog']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "knowledge",
									"label" => __('Anchor text for the Knowledge Base'),
									"content" => '<input type="text" name="knowledge" id="knowledge" value="'.stripslashes($opt['knowledge']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "portfolio",
									"label" => __('Anchor text for the Portfolio'),
									"content" => '<input type="text" name="portfolio" id="portfolio" value="'.stripslashes($opt['portfolio']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "prefix",
									"label" => __('Global Prefix for the breadcrumb path'),
									"content" => '<input type="text" name="prefix" id="prefix" value="'.stripslashes($opt['prefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "suffix",
									"label" => __('Global Suffix for the breadcrumb path'),
									"content" => '<input type="text" name="suffix" id="suffix" value="'.stripslashes($opt['suffix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "normalprefix",
									"label" => __('Prefix for Blog/Page/Category breadcrumbs'),
									"content" => '<input type="text" name="normalprefix" id="normalprefix" value="'.stripslashes($opt['normalprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "archiveprefix",
									"label" => __('Prefix for Archive breadcrumbs'),
									"content" => '<input type="text" name="archiveprefix" id="archiveprefix" value="'.stripslashes($opt['archiveprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "tagprefix",
									"label" => __('Prefix for Tag breadcrumbs'),
									"content" => '<input type="text" name="tagprefix" id="tagprefix" value="'.stripslashes($opt['tagprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "searchprefix",
									"label" => __('Prefix for Search Page breadcrumbs'),
									"content" => '<input type="text" name="searchprefix" id="searchprefix" value="'.stripslashes($opt['searchprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "singlecatprefix",
									"label" => __('Show category in post breadcrumbs?'),
									"desc" => __('Shows the category inbetween Home and the blogpost'),
									"content" => '<input type="checkbox" name="singlecatprefix" id="singlecatprefix" '.checked($opt['singlecatprefix'],true,false).' />',
								);
								$rows[] = array(
									"id" => "singleparent",
									"label" => __('Show Parent Page for Blog posts'),
									"desc" => __('Adds another page inbetween Home and the blogpost'),
									"content" => wp_dropdown_pages("echo=0&depth=0&name=singleparent&show_option_none=-- None --&selected=".$opt['singleparent']),
								);
								$rows[] = array(
									"id" => "blogparent",
									"label" => __('Show Blog in breadcrumb path'),
									"desc" => __('Enable/Disable the Blog crumb in the breadcrumb'),
									"content" => '<input type="checkbox" name="blogparent" id="blogparent" '.checked($opt['blogparent'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "removefinal",
									"label" => __('Remove Final Crumb'),
									"desc" => __('Remove the final crumb in the breadcrumb, ie the page you are on.'),
									"content" => '<input type="checkbox" name="removefinal" id="removefinal" '.checked($opt['removefinal'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "boldlast",
									"label" => __('Bold the last page in the breadcrumb'),
									"content" => '<input type="checkbox" name="boldlast" id="boldlast" '.checked($opt['boldlast'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "nofollowhome",
									"label" => __('Nofollow the link to the home page?'),
									"content" => '<input type="checkbox" name="nofollowhome" id="nofollowhome" '.checked($opt['nofollowhome'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "nofollowcurrent",
									"label" => __('Nofollow the link to the current page?'),
									"content" => '<input type="checkbox" name="nofollowcurrent" id="nofollowcurrent" '.checked($opt['nofollowcurrent'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "trytheme",
									"label" => __('Try to add automatically'),
									"desc" => __('If you\'re using Hybrid, Thesis or Thematic, check this box for some lovely simple action'),
									"content" => '<input type="checkbox" name="trytheme" id="trytheme" '.checked($opt['trytheme'],true,false).'/>',
								);

								$table = $this->form_table($rows);


								$this->postbox('breadcrumbssettings',__('Setting for Schema Breadcrumbs'), '<b>TIP:</b> Call the breadcrumbs easily in your templates by calling <b>schema_breadcrumb();</b> in your templates, or if you want to use shortcode you can use <b>[schema_breadcrumb]</b>. '.$table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Breadcrumbs Settings" /></div>')
								?>
							</form>
							<b>RDFa Plugin:</b>
							<br>
							If you are using the RDFa Breadcrumbs plugin, this plugin will automatically take over, just disable RDFa, and any function calls in your theme will automatically work with Schema Breadcrumb, if you would like to use the same DIVs as RDFa set prefix to:
							<br><i>&lt;div class="breadcrumb breadcrumbs"&gt;&lt;p"&gt;</i><br>
							and set the suffix to:<br>
							<i>&lt;/p"&gt;&lt;/div"&gt;</i>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:30%;padding-left:10px;box-sizing: border-box;">
					<div class="metabox-holder">
						<div class="meta-box-sortables">
							<center style="background-color:white;">
								<a href="https://webdesires.co.uk">
									<div style="margin-bottom:20px;padding:5px 10px 10px 10px">
										<img style="width:100%" src="https://webdesires.co.uk/wp-content/themes/webdesires/images/logo/WebDesiresLogo.png" alt="WebDesires - Web Development" title="WebDesires - Web Development" /><br>
										Looking for a developer?<br>
										Professional UK WordPress Web Development Company
									</div>
								</a>
							</center>
							<?php
								$this->plugin_like();
								$this->plugin_support();
								$this->wd_knowledge();
								$this->wd_news();
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>

<?php		}
	}

	$ybc = new Schema_Breadcrumbs_Admin();
}
if (!function_exists('rdfa_breadcrumb')) {
	function rdfa_breadcrumb() {
		schema_breadcrumb();
	}
}
if (!function_exists('yoast_breadcrumb')) {
	function yoast_breadcrumb($l='',$r='') {
		schema_breadcrumb();
	}
}

if ( ! is_admin() ) {
	add_action( 'init', 'schema_breadcrumb_shortcode' );

	function schema_breadcrumb_shortcode() {
		add_shortcode( 'schema_breadcrumb', 'schema_breadcrumb_shortcode_func' );
	}

	function schema_breadcrumb_shortcode_func( ) {
		//ob_start();
		return schema_breadcrumb('','',false);
		//return ob_get_clean();
	}
}

$schema = array();
function schema_breadcrumb($prefix = '', $suffix = '', $display = true) {
	global $wp_query, $post, $schema;

	$opt = get_option("schema_breadcrumbs");

	$nofollow = ' ';
	if ($opt['nofollowhome']) {
		$nofollow = ' rel="nofollow" ';
	}

	$on_front = get_option('show_on_front');

	$homelink = '<a'.$nofollow.'href="'.get_permalink(get_option('page_on_front')).'"><span>'.$opt['home'].'</span></a>';

	$homelinkschema = '{
			   "@type": "ListItem",
			   "position": '.(count($schema)+1).',
			   "item":
			   {
				"@id": "'.get_permalink(get_option('page_on_front')).'",
					"url": "'.get_permalink(get_option('page_on_front')).'",
				"name": "'.str_replace('"', '', $opt['home']).'"
				}
			  }';

	if (!is_404()) {
		if ($on_front == "page") {
			$obj = get_post_type_object( get_post_type() );

			$page_url = wp_get_post_type_link(get_post_type());


			if (get_post_type() == 'post') {
				$page_name = $opt['blog'];
			} else {
				$obj = get_post_type_object( get_post_type() );
				$page_name = $obj->labels->name;
			}

			//echo $obj->labels->singular_name;


			//$link = explode('/',get_permalink());
			//if(in_array('knowledge-base', $link)){
				//$page_url = "https://webdesires.co.uk/knowledge-base/";
				//$page_name = 'Knowledge Base';
			//} else if(in_array('portfolio', $link)){
				//$page_url = "https://webdesires.co.uk/portfolio/";
				//$page_name = 'Portfolio';
			//}

			//} else if(in_array('portfolio', $link)){
				//$page_url = "https://webdesires.co.uk/portfolio/";
				//$page_name = 'Portfolio';
			//}



			$link = explode('/',$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

			//custom breadcrumbs

			//$homelink = do_action('schema_breadcrumbs_custom');


			//custom breadcrumbs
			//if(in_array('blog', $link) && count($link) > 3){
				//$page_url = "/blog/";
				//$page_name = 'Blog';
				//$homelink .= ' '.$opt['sep'].' <a'.$nofollow.'href="'.$page_url.'" itemprop="url"><span itemprop="title">'.$page_name.'</span></a>';
			//}

			if ( get_post_type() == 'product' ) {
				$bloglink = array();
				$bloglink[] = $homelink;
				$schema = array();
				$schema[] = $homelinkschema;
			} else {
				if (get_post_type() == 'post') {
					if ($opt['blogparent']) {
						$bloglink = array();
						$bloglink[] = $homelink;
						$bloglink[] = '<a href="'.$page_url.'"><span>'.$page_name.'</span></a>';
						$schema = array();
						$schema[] = $homelinkschema;
						$schema[] = '{
					   "@type": "ListItem",
					   "position": '.(count($schema)+1).',
					   "item":
					   {
						"@id": "'.$page_url.'",
							"url": "'.$page_url.'",
						"name": "'.str_replace('"', '', $page_name).'"
						}
					  }';
					} else {
						$bloglink = array();
						$bloglink[] = $homelink;
						$schema = array();
						$schema[] = $homelinkschema;
					}
				} else {
					$bloglink = array();
					$bloglink[] = $homelink;
					$bloglink[] = '<a href="'.$page_url.'"><span>'.$page_name.'</span></a>';
					$schema = array();
					$schema[] = $homelinkschema;
					/*$schema[] = '{
					   "@type": "ListItem",
					   "position": '.(count($schema)+1).',
					   "item":
					   {
						"@id": "'.$page_url.'",
						"name": "'.$page_name.'"
						}
					  }';*/
				}

			}
			} else {
			$homelink = '<a'.$nofollow.'href="'.get_bloginfo('url').'"><span>'.$opt['home'].'</span></a>';
			$schema[] = '{
					   "@type": "ListItem",
					   "position": '.(count($schema)+1).',
					   "item":
					   {
						"@id": "'.get_bloginfo('url').'",
							"url": "'.get_bloginfo('url').'",
						"name": "'.str_replace('"', '', $opt['home']).'"
						}
					  }';
			$bloglink = array();
			$bloglink[] = $homelink;
		}
		//if(count($link) > 4){
			//print_r($bloglink);
			//print_r($homelink);
		//}

		$linker = '';

		if ( ($on_front == "page" && is_front_page()) || ($on_front == "posts" && is_home()) ) {
			$output = array($opt['home']);
			$schema = array();
			$schema[] = $homelinkschema;
		} elseif ( $on_front == "page" && (is_home() || (is_archive() && !is_author() && !is_category() && !is_tag() && !is_tax())) ) {
			$schema = array();
			$schema[] = $homelinkschema;
			$output = array($homelink);
			$output[] = '<a href="'.$page_url.'"><span>'.$page_name.'</span></a>';
		} elseif ( !is_page() ) {
			//todo make this optional?
			$opt['showsinglecategory'] = true;
			$opt['showsinglecategoryifmultiple'] = true;


			//if ( ( is_single() || is_category() || is_tag() || is_date() || is_author() ) && $opt['singleparent'] != false) {
				//$homelink .= ' '.$opt['sep'].' <a href="'.get_permalink($opt['singleparent']).'">'.get_the_title($opt['singleparent']).'</a>';
				//$linker = 'blog/';
			//}
			if ($opt['showsinglecategory'] === true) {

				//custom taxonomy
				$categories = get_the_terms(get_the_ID(), $obj->taxonomies[0]);
				$link = explode('/',$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

				$customtax = false;

				$counter = 0;
				foreach( $categories as $category ) {
					if (isset($category->slug) && in_array($category->slug, $link)){
						$counter++;
					}
				}

				if (is_single()) {
					$counter++;
				}

				$i=0;

				foreach( $categories as $category ) {
					//echo '<br><br>'.$category->slug;
					if (isset($category->slug) && in_array($category->slug, $link)){
						$i++;
						$customtax = true;

						$bloglink[] = '<a href="'.get_term_link($category->slug, $obj->taxonomies[0]).'"><span>'.$category->name.'</span></a>';


						$schema = array();
						$schema[] = $homelinkschema;
						$schema[] = '{
						   "@type": "ListItem",
						   "position": '.(count($schema)+1).',
						   "item":
						   {
							"@id": "'.get_term_link($category->slug, $obj->taxonomies[0]).'",
							"url": "'.get_term_link($category->slug, $obj->taxonomies[0]).'",
							"name": "'.str_replace('"', '', $category->name).'"
							}
						  }';
					}

					//echo $category->term_id . ', ' . $category->slug . ', ' . $category->name . '<br />';
				}

				if ($customtax === false) {
					if (is_single() && is_array(get_the_category()) && count(get_the_category()) > 1) {
						if ($opt['showsinglecategoryifmultiple'] === true) {
							$cats = get_the_category();

							$cat = $cats[0]->cat_ID;
							//$schema = array();
							//$schema[] = $homelinkschema;
							//TODO?? ISSUE??
							//$output = $bloglink.' '.$opt['sep'].' '.schema_get_category($cat, false, " ".$opt['sep']." ");
							$output = $bloglink;
						} else {
							$output = $bloglink;
						}
					} else {
						$cats = get_the_category();


							$cat = $cats[0]->cat_ID;
							$cat = intval( get_query_var('cat') );

						//$schema = array();
						//$schema[] = $homelinkschema;
						$output = $bloglink;
						$output = array_merge($output, schema_get_category($cat, false));
					}
				} else {
					$output = $bloglink;
					if (is_single()) {
						$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
						$actual_link = $actual_link[0];
						$output[] = '<a href="'.$actual_link.'"><span>'.get_the_title().'</span></a>';
					}
				}
			} else {
				$output = $bloglink;
			}

			if (is_single() && $opt['singlecatprefix']) {
				$cats = get_the_category();


				$link = explode('/',$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				foreach ($cats as $cat) {
					if (isset($cat->category_nicename) && in_array($cat->category_nicename, $link)){
						if ( is_object($cat) ) {
							if ($cat->parent != 0) {
								$parents = explode("##sep##", get_category_parents($cat->term_id, true, "##sep##"));
								$output = array_merge($output, array_filter($parents));
								$schema[] = '{
							   "@type": "ListItem",
							   "position": '.(count($schema)+1).',
							   "item":
							   {
								"@id": "'.get_category_link($cat->term_id).'",
									"url": "'.get_category_link($cat->term_id).'",
								"name": "'.str_replace('"', '', $cat->name).'"
								}
							  }';
							} else {
								$output[] = '<a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a>';
								$schema[] = '{
							   "@type": "ListItem",
							   "position": '.(count($schema)+1).',
							   "item":
							   {
								"@id": "'.get_category_link($cat->term_id).'",
									"url": "'.get_category_link($cat->term_id).'",
								"name": "'.str_replace('"', '', $cat->name).'"
								}
							  }';
							}
						}
					}
				}


			}
			if ($customtax === false) {
				if ( is_category() ) {
					$cat = intval( get_query_var('cat') );
					$schema = array();
					$output = array();
					$schema[] = $homelinkschema;
					$output[] = $homelink;
					$output = array_merge($output, schema_get_category_parents($cat, false));

				} elseif ( get_post_type() == 'product' ) {

					$post = $wp_query->get_queried_object();
					$descendant = get_the_terms( $post->ID, 'product_cat' );
					if (is_array($descendant)) {
						$descendant = array_reverse($descendant);
					}
					$descendant = $descendant[0];
					$descendant_id = $descendant->term_id;

					$ancestors = array_reverse(get_ancestors($descendant_id, 'product_cat'));

					foreach ($ancestors as $ancestor) {
						$ancestor_term = get_term_by("id", $ancestor, "product_cat");
						$ancestor_link = get_term_link( $ancestor_term->slug, $ancestor_term->taxonomy );

						$output[] = '<a href="'.$ancestor_link.'"><span>'.$ancestor_term->name.'</span></a>';

						$schema[] = '{
							"@type": "ListItem",
							"position": '.(count($schema)+1).',
							"item":
							{
							 "@id": "'.$ancestor_link.'",
								 "url": "'.$ancestor_link.'",
							 "name": "'.str_replace('"', '', $ancestor_term->name).'"
							 }
						   }';

					}

					$descendant_term = get_term_by("id", $descendant_id, "product_cat");

					if ($descendant_term !== false) {
						$descendant_link = get_term_link( $descendant_term->slug, $descendant_term->taxonomy );

						$output[] = '<a href="'.$descendant_link.'"><span>'.$descendant->name.'</span></a>';

						$schema[] = '{
							"@type": "ListItem",
							"position": '.(count($schema)+1).',
							"item":
							{
							"@id": "'.$descendant_link.'",
								"url": "'.$descendant_link.'",
							"name": "'.str_replace('"', '', $descendant->name).'"
							}
						}';
					}

					if (is_product()) {
						$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
						$actual_link = $actual_link[0];
						$output[] = '<a href="'.$actual_link.'"><span>'.get_the_title().'</span></a>';
					} else {
						$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
						$actual_link = $actual_link[0];
						$output[] = '<a href="'.$actual_link.'"><span>'.woocommerce_page_title( false ).'</span></a>';

						$schema[] = '{
							"@type": "ListItem",
							"position": '.(count($schema)+1).',
							"item":
							{
							"@id": "'.$actual_link.'",
								"url": "'.$actual_link.'",
							"name": "'.str_replace('"', '', woocommerce_page_title( false )).'"
							}
						}';
					}
				} elseif ( is_tag() || is_tax()) {
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$output[] = '<a href="'.$actual_link.'"><span>'.($opt['tagprefix'].single_cat_title('',false)).'</span></a>';
				} elseif ( is_date() ) {
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$output[] = '<a href="'.$actual_link.'"><span>'.($opt['archiveprefix'].single_month_title(' ',false)).'</span></a>';
				} elseif ( is_author() ) {

					$user = get_userdatabylogin($wp_query->query_vars['author_name']);
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$output[] = '<a href="'.$actual_link.'"><span>'.('Author: ' . $user->display_name).'</span></a>';
				} elseif ( is_search() ) {
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$output[] = '<a href="'.$actual_link.'"><span>'.('Results For: "'.stripslashes(strip_tags(get_search_query())).'"').'</span></a>';
				} else if ( is_tax() ) {
					$taxonomy 	= get_taxonomy ( get_query_var('taxonomy') );
					$term 		= get_query_var('term');
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$output[] = '<a href="'.$actual_link.'"><span>'.$taxonomy->label .': '.( $term).'</span></a>';
				} else {
					//Double check the url to ensure breadcrumb is ok
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$output[] = '<a href="'.$actual_link.'"><span>'.get_the_title().'</span></a>';
				}
			}

		} else {
			$post = $wp_query->get_queried_object();

			// If this is a top level Page, it's simple to output the breadcrumb
			if ( 0 == $post->post_parent ) {
				$schema = array();
				$schema[] = $homelinkschema;
				$output = array();
				$output[] = $homelink;
				$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
				$actual_link = $actual_link[0];
				$output[] = '<a href="'.$actual_link.'"><span>'.get_the_title().'</span></a>';
			} else {
				if (isset($post->ancestors)) {
					if (is_array($post->ancestors))
						$ancestors = array_values($post->ancestors);
					else
						$ancestors = array($post->ancestors);
				} else {
					$ancestors = array($post->post_parent);
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse($ancestors);

				// Add the current Page to the ancestors list (as we need it's title too)
				$ancestors[] = $post->ID;

				$links = array();
				foreach ( $ancestors as $ancestor ) {
					$tmp  = array();
					$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
					$tmp['url'] 	= get_permalink($ancestor);
					$tmp['cur'] = false;
					if ($ancestor == $post->ID) {
						$tmp['cur'] = true;
					}
					$links[] = $tmp;
				}

				$output = array();
				$output[] = $homelink;
				$schema = array();
				$schema[] = $homelinkschema;
				foreach ( $links as $link ) {

					if (!$link['cur']) {
						$output[] = '<a href="'.$linker.$link['url'].'"><span>'.$link['title'].'</span></a>';
						$schema[] = '{
					   "@type": "ListItem",
					   "position": '.(count($schema)+1).',
					   "item":
					   {
						"@id": "'.$linker.$link['url'].'",
							"url": "'.$linker.$link['url'].'",
						"name": "'.str_replace('"', '', $link['title']).'"
						}
					  }';
					} else {
						$output[] = ($link['title']);
					}
				}
			}
		}

		$vars = apply_filters('schema_breadcrumbs_custom', array('output' => $output, 'schema' => $schema, 'link' => $link, 'homelink' => $homelink, 'opt' => $opt, 'nofollow' =>$nofollow));
		$output = $vars['output'];
		$schema = $vars['schema'];

		$html = '';

		$opt = get_option("schema_breadcrumbs");

		if ($opt['removefinal'] === true) {
			array_pop($output);
		}

		foreach ($output as $key => $out) {
			/* if (count($output) == $key+1) {
				$opt = get_option("schema_breadcrumbs");
				if ($opt['boldlast']) {
					if ($opt['nofollowcurrent']) {
						return '<span '.$child.'><a href="'.$actual_link.'" onclick="return false;" style="text-decoration:none" rel="nofollow"><span><strong>'.$input.'</strong></span></a></span>';
					} else {
						return '<span '.$child.'><a href="'.$actual_link.'" style="text-decoration:none"><span><strong>'.$input.'</strong></span></a></span>';
					}
				}
			} else {

			} */




			if (count($output) == $key+1) {

				if (count($output) > count($schema)) {
					$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$actual_link = $actual_link[0];
					$schema[] = '{
							"@type": "ListItem",
							"position": '.(count($schema)+1).',
							"item":
							{
								"@id": "'.$actual_link.'",
									"url": "'.$actual_link.'",
								"name": "'.str_replace('"', '', get_the_title()).'"
								}
							}';
				}

				if ($opt['boldlast']) {
					if ($opt['nofollowcurrent']) {
						$out = str_replace('<a ', '<a onclick="return false;" style="text-decoration:none;font-weight:bold" rel="nofollow"', $out);

					} else {
						$out = str_replace('<a ', '<a style="text-decoration:none;font-weight:bold"', $out);
					}
				} else {
					$out = str_replace('<a ', '<a style="text-decoration:none"', $out);
				}

				$html .= $out;
			} else {
				$html .= $out.' '.$opt['sep'].' ';
			}
		}

		/*$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		$actual_link = $actual_link[0];
		$schema[] = '{
					   "@type": "ListItem",
					   "position": '.(count($schema)+1).',
					   "item":
					   {
						"@id": "'.$actual_link.'",
							"url": "'.$actual_link.'",
						"name": "'.addslashes($input).'"
						}
					  }';
		$opt = get_option("schema_breadcrumbs");
			if ($opt['boldlast']) {
				if ($opt['nofollowcurrent']) {
					return '<span '.$child.'><a href="'.$actual_link.'" onclick="return false;" style="text-decoration:none" rel="nofollow"><span><strong>'.$input.'</strong></span></a></span>';
				} else {
					return '<span '.$child.'><a href="'.$actual_link.'" style="text-decoration:none"><span><strong>'.$input.'</strong></span></a></span>';
				}

			} else {
				return $input;
			}*/

		$output = str_replace('>Blogs<', '>Blog<', $html);
		$output = str_replace('/./', '/', $output);

		$output = '<span id="breadcrumbs">' . $output . '</span>';

		ob_start();

			?>
			<script type="application/ld+json">
			{
			 "@context": "http://schema.org",
			 "@type": "BreadcrumbList",
			 "itemListElement":
			 [
				<?php $c=0; foreach ($schema as $breadcrumb) { if ($breadcrumb == '') {continue;} $c++; if ($c > 1) { echo ',';}
					echo str_replace('/./', '/', $breadcrumb);
				} ?>
			 ]
			}
			</script>
			<?php
		$output = $output . ob_get_clean();

		if (is_archive()) {
			$output = $opt['normalprefix']." " . $output;
		} else if (is_search()) {
			$output = $opt['normalprefix']." " . $output;
		} else {
			$output = $opt['normalprefix']." " . $output;
		}

		if ($opt['prefix'] != "") {
			$output = html_entity_decode(stripslashes($opt['prefix']))." ".$output;
		}

		if ($opt['suffix'] != "") {
			$output = $output . " " . html_entity_decode(stripslashes($opt['suffix']));
		}

		if ($display) {
			echo $output;
		} else {
			return $output;
		}
	}
}

function schema_breadcrumb_output() {
	$opt = get_option('schema_breadcrumbs');
	if ($opt['trytheme'])
		schema_breadcrumb('<div id="schema">','</div>');
	return;
}

if( !function_exists( 'wp_get_post_type_link' )  ) {
    function wp_get_post_type_link( &$post_type ){

        global $wp_rewrite;

        if ( ! $post_type_obj = get_post_type_object( $post_type ) )
            return false;

        if ( get_option( 'permalink_structure' ) && is_array( $post_type_obj->rewrite ) ) {

            $struct = $post_type_obj->rewrite['slug'] ;
            if ( $post_type_obj->rewrite['with_front'] )
                $struct = $wp_rewrite->front . $struct;
            else
                $struct = $wp_rewrite->root . $struct;

            $link = home_url( user_trailingslashit( $struct, 'post_type_archive' ) );

        } else if ($post_type == 'post'){
			//$link = home_url( '/blog/' );
			$link = get_permalink( get_option('page_for_posts' ) );
		} else {
            $link = home_url( '?post_type=' . $post_type );
        }

        return apply_filters( 'the_permalink', $link );
    }
}


if (!function_exists('bold_or_not')) {

	function notbold_or_not($input, $child = '') {
		global $schema;
		if ($child === true) {
			$child = '';
		}
		$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		$actual_link = $actual_link[0];
		$opt = get_option("schema_breadcrumbs");
		if ($opt['boldlast']) {

			$schema[] = '{
				   "@type": "ListItem",
				   "position": '.(count($schema)+1).',
				   "item":
				   {
					"@id": "'.$actual_link.'",
						"url": "'.$actual_link.'",
					"name": "'.str_replace('"', '', $input).'"
					}
				  }';

			return '<span '.$child.'><a href="'.$actual_link.'" onclick="return false;" style="text-decoration:none"><span>'.$input.'</span></a></span>';
		} else {
			return $input;
		}
	}
}

if (!function_exists('schema_get_category_parents')) {
	// Copied and adapted from WP source
	function schema_get_category_parents($id, $link = FALSE, $nicename = FALSE){
		global $schema;

		$parent = &get_category($id);
		$parent_id = $id;
		if ( is_wp_error( $parent ) )
		   return $parent;

		if ( $nicename )
		   $name = $parent->slug;
		else
		   $name = $parent->cat_name;

		//if ( $parent->parent && ($parent->parent != $parent->term_id) )
		   //$chain = explode("#sep#", get_category_parents($parent->parent, true, '#sep#', $nicename));

		//$chain[] = ($name);
		$chain = array();

		//print_r($parent);

		$schemax = array();
		$data = array();

		do {
			$parent = &get_category($parent_id);

			$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

			$data[] = array('link'=>get_category_link($parent->cat_ID),'name'=>$parent->name);



			$parent_id = $parent->parent;
		} while($parent_id > 0);

		$data = array_reverse($data);

		$i=0;foreach ($data as $dat) {
			$schemax[] = '{
				"@type": "ListItem",
				"position": '.(count($schema)+1+$i).',
				"item":
				{
					"@id": "'.$dat['link'].'",
						"url": "'.$dat['link'].'",
					"name": "'.str_replace('"', '', $dat['name']).'"
					}
				}';

			$chain[] = '<a href="'.$dat['link'].'"><span>'.$dat['name'].'</span></a>';
		$i++;}


		$schema = array_merge($schema, $schemax);

		return $chain;
	}
}

if (!function_exists('schema_get_category')) {
	// Copied and adapted from WP source
	function schema_get_category($id, $link = FALSE, $nicename = FALSE){
		global $schema;

		$parent = &get_category($id);

		if ( is_wp_error( $parent ) )
		   return array();

		$chain = explode("#sep#", get_category_parents($parent, true, '#sep#', $nicename));

		return $chain;
	}
}

add_action('thesis_hook_before_content','schema_breadcrumb_output',10,1);
add_action('hybrid_before_content','schema_breadcrumb_output',10,1);
add_action('thematic_belowheader','schema_breadcrumb_output',10,1);
add_action('framework_hook_content_open','schema_breadcrumb_output',10,1);

?>
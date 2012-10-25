<?php
#-----------------------------------------------------------------
# CSS Header Settings - do not remove this part!
#-----------------------------------------------------------------
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

// Access to WordPress
require_once( $path_to_wp . '/wp-load.php' );

$offset = 60 * 60 ;
$ExpStr = "Expires: " .
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);

$custom_css = get_option('option_tree');
$color_scheme = get_option_tree( 'color_scheme');


//Navigation Level 2 Hover
$hovercolor = $custom_css['drop_down_font_color_hover'];
if(isset($_GET['color_scheme'])) { $hovercolor = $color_scheme; }

echo '#navigation ul li li a:hover {';

	echo 'color:'.$hovercolor.';';
	echo 'background-color:'.$custom_css['drop_down_background_color_hover'].';';

echo '}';


//Navigation Level 2 Backgroundcolor
echo '#navigation ul.sub-menu {';

	echo 'background-color:'.$custom_css['drop_down_background_color'].';';

echo '}';


#-----------------------------------------------------------------
# Color Variations
#-----------------------------------------------------------------
?>

#navigation ul li a:hover {
	color:<?php echo $custom_css['navigation_hover']; ?>;
}
#navigation ul li.current-menu-parent a,
#navigation ul li.current-menu-item a {
	color:<?php echo $custom_css['navigation_hover']; ?>;
}
#navigation ul li.current-menu-parent ul.sub-menu li a {
	color:<?php echo $custom_css['drop_down_font_color']; ?>;	
}
#navigation ul.sub-menu li a:hover,
#navigation ul.sub-menu li.current-menu-item a,
#navigation ul.sub-menu li.current_page_item a {
	color:<?php echo $custom_css['	drop_down_font_color_hover']; ?> !important;	
	background-color: rgb(<?php echo $custom_css['drop_down_background_color_hover']; ?>);
	background:rgba(<?php echo $custom_css['drop_down_background_color_hover']; ?>, 0.9);
}

#navigation ul.sub-menu {
	-webkit-box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
	-moz-box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
	-ms-box-shadow:0px 0px 2px rgba(0, 0, 0, 0.2);
	-o-box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
	box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.2);
	background-color: rgb(<?php echo $custom_css['drop_down_background_color']; ?>);
	background:rgba(<?php echo $custom_css['drop_down_background_color']; ?>, 0.9);
	border-top:2px solid <?php echo $color_scheme; ?>;
}



.widget_rss a.rsswidget:hover {
	color: <?php echo $color_scheme; ?>;
}

.flex-control-nav li a:hover,
.flex-control-nav li a.active,
a.comment-reply-link:hover,
.edit-link a:hover,
.permalink-hover:hover,
#slider-nav a#slider-next:hover,
#slider-nav a#slider-prev:hover,
.post-slider-nav a.slider-prev:hover,
.post-slider-nav a.slider-next:hover,
#slider-bullets a.activeSlide,
#slider-bullets a:hover  {
	background-color:<?php echo $color_scheme; ?>;
}

ul.archive li a:hover,
.author-link,
p.search-title span, 
p.tag-title span,
.author-name a,
ul.filter_portfolio a:hover,
ul.filter_portfolio a.selected,
#logo h1 a:hover,
.entry-content a,
.portfolio-info a,
ul.archive,
.entry-meta a:hover,
.entry-meta-single-post a:hover,
blockquote cite, 
blockquote cite a, 
blockquote cite a:visited, 
blockquote cite a:visited,
.quote cite,
.entry-title a:hover,
#teaser-content a,
span.current,   
.themecolor,
a:hover,
.tag-links a:hover,
.h-link,
.widget_recent_comments a{
	color:<?php echo $color_scheme; ?>;
}

::-moz-selection  {
	color: #FFFFFF !important;
	background:<?php echo $color_scheme; ?>;
}
::selection {
	color: #FFFFFF !important;
	background:<?php echo $color_scheme; ?>;
}

.entry-attachment .entry-caption,
.gallery-caption,
.lambda-pricingtable.featured .lambda-pricingtable-top,
.testimonial-company {
	background:<?php echo $color_scheme; ?>;
}

#vmenu li.selected:hover h3,
#vmenu li.selected,
.camera_wrap .camera_pag .camera_pag_ul li:hover > span,
.camera_wrap .camera_pag .camera_pag_ul li.cameracurrent > span,
.camera_bar_cont span,
.link-post span,
.lambda-dropcap2,
.lambda-highlight1 {
	background-color:<?php echo $color_scheme; ?>;
}

.camera_commands,
.camera_prev,
.camera_next,
.flex-direction-nav a,
.lambda-featured-header-caption,
.hover-overlay {
	background-color: rgb(<?php echo $custom_css['hover_color_rgb']; ?>);
	background:rgba(<?php echo $custom_css['hover_color_rgb']; ?>, <?php echo $custom_css['hover_opacity']; ?>);
}

.lambda_widget_flickr .flickr_items img {
	background-color:<?php echo $color_scheme; ?>;
}

#footer .lambda_widget_flickr .flickr_items img {
	background-color:<?php echo $color_scheme; ?>;
}

.mm-button {
	background-image: url(../../images/default/mobile-menu.png);
}

#toTop {
	background-image: url(../../images/default/to-top.png);
}
.lambda-like {
	background-image: url(../../images/default/lambda-like.png);
}
ul.tabs li a.active {
	border-top:2px solid <?php echo $color_scheme; ?>;
}

.testimonial-entry {
	background-attachment: scroll;
	background-image: url(../../images/default/blockquote.png);
	background-repeat: no-repeat;
	background-position: 44px 30px;
}

.testimonial-name span {
	color:<?php echo $color_scheme; ?>;
}

.more-link:hover,
.excerpt:hover {
	background-image: url(../../images/default/excerpt-icon-hover.png);
}

h3.trigger:hover a,
p.trigger:hover a{
	color:<?php echo $color_scheme; ?>;
}

/* Up Toggle State */
h3.trigger,
p.trigger {
	border-bottom: 1px solid #D9D9D9;
	background-color: #FFFFFF;
	background-image: url(../../images/default/toggle-open.png);
	background-repeat: no-repeat;
	background-position: left 3px;
}
p.trigger {
	background-position: left 1px;
}

h3.trigger.active a,
p.trigger.active a {
	color:<?php echo $color_scheme; ?>;
}


#nav-portfolio .nav-next a:hover {
	background-image: url(../../images/default/excerpt-icon-hover.png);	
}
#nav-portfolio .nav-previous a:hover {
	background-image: url(../../images/default/excerpt-icon-back-hover.png);	
}

.tprev:hover{
	background-image: url(../../images/default/excerpt-icon-back-hover.png);	
}
.tnext:hover {
	background-image: url(../../images/default/excerpt-icon-hover.png);
}

ul.archive li,
.widget_links li,
.widget_nav_menu li,
.widget_pages li,
.widget_meta li,
.widget_categories li,
.widget_archive li,
.widget_recent_entries li {
	background-image: url(../../images/default/arrow-right.gif);
	color:#333333;
}
.widget_recent_comments li {
	background-image: url(../../images/default/comment.png);
}
.widget_search #s {/* This keeps the search inputs in line */
	background-image: url(../../images/default/zoom.png);
}
.tweet_list li {
	background-image: url(../../images/default/twitter-widget.png);
}
.pformat .post_format_image  {
	background-image: url('../../images/default/pformat-image.png');
}
.pformat .post_format_standard  {
	background-image: url('../../images/default/pformat-standard.png');
}
.pformat .post_format_audio {
	background-image: url('../../images/default/pformat-audio.png');
}
.pformat .post_format_gallery {
	background-image: url('../../images/default/pformat-gallery.png');
}

.pformat .post_format_video {
	background-image: url('../../images/default/pformat-video.png');
}
.pformat .post_format_link {
	background-image: url('../../images/default/pformat-link.png');
}
.pformat .post_format_quote {
	background-image: url('../../images/default/pformat-quote.png');
}
.pformat .post_format_aside {
	background-image: url('../../images/default/pformat-aside.png');
}

.lambda-address {
	background-image: url('../../images/default/contact-adress.png');
}
.lambda-phone {
	background-image: url('../../images/default/contact-phone.png');
}
.lambda-fax {
	background-image: url('../../images/default/contact-fax.png');
}
.lambda-email {
	background-image: url('../../images/default/contact-email.png');
}
.lambda-internet {
	background-image: url('../../images/default/contact-internet.png');
}

.lambda-most-liked-posts li {
	background-image: url('../../images/default/like.png');
}
.lambda_widget_mostlikesposts {
	color:<?php echo $color_scheme; ?>;
}
.response span{
	color:<?php echo $color_scheme; ?>;
}
.nevada-caption.dark .excerpt:hover,
.nevada-caption.white .excerpt:hover,
.tweet_text a,
#sidebar .lambda_widget_recent_comments a,
#sidebar_second .lambda_widget_recent_comments a,
.tweet_time a:hover,
.more-link:hover,
.excerpt:hover {
	color:<?php echo $color_scheme; ?> !important;
}
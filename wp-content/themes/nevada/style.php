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


#-----------------------------------------------------------------
# Declare CSS Font Stacks for reuse
#-----------------------------------------------------------------
$websafefonts = array(
	'arial'		 	=> 'Arial, Helvetica, sans-serif',
	'georgia'	 	=> 'Georgia, serif',
	'helvetica' 	=> '"HelveticaNeue","Helvetica Neue",Helvetica,Arial,sans-serif',
	'tahoma' 		=> 'Tahoma, Geneva, sans-serif',
	'times' 		=> '"Times New Roman", Times, serif',
	'trebuchet' 	=> '"Trebuchet MS", Helvetica, sans-serif',
	'verdana' 		=> 'Verdana, Geneva, sans-serif',
	'impact' 		=> 'Impact, Charcoal, sans-serif',
	'palatino'	 	=> '"Palatino Linotype", "Book Antiqua", Palatino, serif',
	'century' 		=> 'Century Gothic, sans-serif',
	'lucida'		=> '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
	'luciaconsole'	=> '"Lucida Console", Monaco, monospace',
	'arialblack'	=> '"Arial Black", Gadget, sans-serif',
	'arialnarrow' 	=> '"Arial Narrow", sans-serif',
	'copperplate'	=> 'Copperplate / Copperplate Gothic Light, sans-serif',
	'gillsans'		=> 'Gill Sans / Gill Sans MT, sans-serif',
	'courier'		=> '"Courier New", Courier, monospace'
);



#-----------------------------------------------------------------
# Load Option Tree & Meta Data
#-----------------------------------------------------------------
$custom_css = get_option('option_tree');
$color_scheme = get_option_tree('color_scheme');
$color_scheme = (isset($_GET['color_scheme'])) ? '#'.$_GET['color_scheme'] : $color_scheme;

#-----------------------------------------------------------------
# Body Typography
#-----------------------------------------------------------------
echo 'body {';
	if (!empty($custom_css['bodyfont'])) {
		
		echo 'color:'.$custom_css['bodyfont']['font-color'].';';
		echo 'font-size:'.$custom_css['bodyfont']['font-size'].';';
		echo 'font-family:'.$websafefonts[$custom_css['bodyfont']['font-family']].';';
		echo 'font-weight:'.$custom_css['bodyfont']['font-weight'].';';
		echo 'font-style:'.$custom_css['bodyfont']['font-style'].';';
		
	}
echo '}'; 


#-----------------------------------------------------------------
# Navigation Customisation
#-----------------------------------------------------------------

//Navigation Level 1
echo '#navigation ul li a {';
	if (!empty( $custom_css['navigation_font'] )) {
		
		echo 'color:'.$custom_css['navigation_font']['font-color'].';';
		echo 'font-size:'.$custom_css['navigation_font']['font-size'].';';
		echo 'font-family:'.$websafefonts[$custom_css['navigation_font']['font-family']].';';
		echo 'font-weight:'.$custom_css['navigation_font']['font-weight'].';';
		echo 'font-style:'.$custom_css['navigation_font']['font-style'].';';
		echo 'text-transform:'.$custom_css['navigation_font']['font-transform'].';';
	
	}
echo '}'; 

//Navigation Level 2 Link Style
echo '#navigation ul.sub-menu li a {';
	if ( !empty($custom_css['drop_down_font_color']) ) {
		
		echo 'color:'.$custom_css['drop_down_font_color']['font-color'].';';
		echo 'font-size:'.$custom_css['drop_down_font_color']['font-size'].';';
		echo 'font-family:'.$websafefonts[$custom_css['drop_down_font_color']['font-family']].';';
		echo 'font-weight:'.$custom_css['drop_down_font_color']['font-weight'].';';
		echo 'font-style:'.$custom_css['drop_down_font_color']['font-style'].';';
		echo 'text-transform:'.$custom_css['drop_down_font_color']['font-transform'].';';
	
	}	
echo '}'; 


#-----------------------------------------------------------------
# Headlines
#----------------------------------------------------------------- 
$fontface = ($theme_options['headline_font_face_type'] == 'headline_font_face_google') ? polish_font_name($custom_css['headline_font_face_google']['font-family']) : $websafefonts[$custom_css['headline_font_face_websafe']['font-family']]; ?>
	
h1 { font-family: <?php echo $fontface; ?> !important; 
   	 font-size: <?php echo $custom_css['h1_font_size']['0'].$custom_css['h1_font_size']['1']; ?>;
}
	
h2 { font-family: <?php echo $fontface; ?> !important; 
   	 font-size: <?php echo $custom_css['h2_font_size']['0'].$custom_css['h2_font_size']['1']; ?>;
}
		
h3 { font-family: <?php echo $fontface; ?> !important; 
   	 font-size: <?php echo $custom_css['h3_font_size']['0'].$custom_css['h3_font_size']['1']; ?>;
}
	
h4 { font-family: <?php echo $fontface; ?> !important; 
     font-size: <?php echo $custom_css['h4_font_size']['0'].$custom_css['h4_font_size']['1']; ?>; 
}
	
h5 { font-family: <?php echo $fontface; ?> !important;  
     font-size: <?php echo $custom_css['h5_font_size']['0'].$custom_css['h5_font_size']['1']; ?>; 
}
	
h6 { font-family: <?php echo $fontface; ?> !important; 
     font-size: <?php echo $custom_css['h6_font_size']['0'].$custom_css['h6_font_size']['1']; ?>; 
}

h1, h2, h3, h4, h5, h6 {
	color:<?php echo $custom_css['headline_font_color']; ?> ;
}

<?php if($theme_options['headline_font_face_type'] == 'headline_font_face_websafe') { ?>
	
	h1, h2, h3, h4, h5, h6 {
		font-weight: <?php echo $custom_css['headline_font_face_websafe']['font-weight']; ?>;
		font-style: <?php echo $custom_css['headline_font_face_websafe']['font-style']; ?>;
	}
	
<?php } ?>

.caption.themecolor_background {
	background-color: <?php echo $color_scheme; ?>;
}
.caption.themecolor_normal {
	color: <?php echo $color_scheme; ?>;
}


<?php

// User Custom CSS
echo $custom_css['custom_css'];


?>



	
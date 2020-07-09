<?php get_header(); ?>
<?php get_footer(); ?>
Directory to Files
<?php bloginfo( 'template_url' ); ?>
Get to Home 
<?php echo get_option('home'); ?>
Get Menu
<?php wp_nav_menu( array( 'container'=> false, 'menu' => 'Principal' ) ); ?>
Get Option Field <?php echo get_option('home'); ?>/">
Get Custom Field from Post
$email = get_field('e-mail', 'option');
ASPECT
Custom Post Type
<!-- /* Custom Post Type Start */ -->
function create_posttype() {
    register_post_type( 'news',
    // CPT Options
    array(
        'labels' => array(
        'name' => __( 'news' ),
        'singular_name' => __( 'News' )
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'news'),
        )
    );
    }
<!-- // Hooking up our function to theme setup -->
add_action( 'init', 'create_posttype' );
<!-- /* Custom Post Type End */ -->
Custom Field Repeater Example
<div class="containerBox" id="chargrilled">
   <?php while ( have_rows('chargrilled') ) : the_row(); ?>
   <div class="menuFood">
       <img src="<?php the_sub_field('imagen_chargrilled'); ?>">
       </div>
       <?php endwhile; ?>
</div>
Query to get ACF from another Post Type
<?php
$args = array(
'post_type' => 'slider',
'post_status' => 'publish',
'posts_per_page' => -1
);
$slider = new WP_Query( $args );
?>
<?php if ( have_posts() : ) while ( have_posts() : ) : the_post(); ?> <?php endwhile; endif; ?>
<?php wp_reset_postdata(); ?>
<?php wp_reset_query(); ?>
<?php wp_redirect( home_url(), '301' ); ?>
<?php
$args = array(
'post_type' => 'premios',
'post_status' => 'publish',
'orderby' => 'post_date',
'order' => 'DESC',
);
$premios = new WP_Query( $args ); ?>
<?php while ( $premios->have_posts() ) : $premios->the_post(); ?>
<div class="premios">
<img src="<?php bloginfo( 'template_url' ); ?>/assets/img/portrait-big.png" alt="">
<div class="premios-txt">
<div class="nombre"><?php echo get_the_title(); ?></div>
<div class="anio"><?php echo get_field('year'); ?></div>
<p><?php echo get_field('texto'); ?></p>
</div>
</div>
<?php endwhile; ?>
WHILE SCHLEIFE FÜR REAPEATER
<?php while ( have_rows('invitado') ) : the_row(); ?>
<?php the_sub_field('imagen'); ?> <?php the_sub_field('content_2'); ?>
<?php endwhile; ?>
Beispiel if Vorhanden = Show
<?php $streaming = get_field('link_streaming'); ?>
<?php if( !empty($streaming) ): ?>
<div class="streamingAnda">
<iframe src="<?php the_field('link_streaming'); ?>" allow="autoplay; fullscreen" frameborder="0" allowfullscreen allowscriptaccess="always" scrolling="no"></iframe>
</div>
<?php endif; ?>
  flush_rewrite_rules(); Paste in functions.php, upload and then delete and upload again!
change Month in English to Month in Spanish
$end_date = get_field('fecha');
$end_month = date_i18n( 'd F Y', strtotime( $end_date ) );
echo $end_month;
<!-- REDIRECT TO LAST POST -->
<?php
    $last_post = get_posts( array(
        'post_type' => 'edicion',
        'posts_per_page' => 1
    ) );
    if ( $last_post ) wp_safe_redirect( get_permalink( $last_post[0]->ID ) );
?>
<!-- Get List of Taxonomys by ID of Post/Page/PostObject -->
$terms = get_the_terms( $lastEdicion->ID, 'category_revista' );
if ( !empty( $terms ) ){
    // get the first term
    $term = array_shift( $terms );
    echo $term->slug;
}
<!-- LIST TAXONOMY TERMS AND REMOVE DUPLICATES FROM CUSTOM POST TYPE BY QUERY POST ID -->
 $args = (array(
                  'post_status' => 'publish',
                  'numberposts' => -1,
                  'post_type'   => 'revista',
                  'meta_query' => array(
                    array(
                      'key' => 'ediciones',
                      'value' => $ID,
                      'compare' => '='
                    )
                )
                ));
        $revistas = new WP_Query( $args ); ?>
            <?php
            // echo 'ID de Edición: ' . $ID . '<br>___________________'; 
            ?>
            <ul class="hide-sm">
<?php
            $terms = array(); 
            while ( $revistas->have_posts() ) : 
              $revistas->the_post();
              $terms_tags = get_the_terms( $lastEdicion->ID, $taxonomies );
              foreach ( $terms_tags as $term_tag ) { 
                $terms[$term_tag->slug] = '<li><a href="' . get_option('home') . '/' . 'revista-seccion/' . $term_tag->slug . '/?id=' . $ID . '">'.$term_tag->name . '</a></li>';  
              }
            endwhile; 
            echo implode($terms,' '); // this goes outside the while loop ?>
            <?php wp_reset_postdata(); ?>
            <?php wp_reset_query(); ?>


<!-- Add class "active" Nav -->
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }
    return $classes;
}

<!-- Redirect to Parent Page if Child Page does not exist -->
function __404_template_redirect()
{
    if( is_404() )
    {
        $req = $_SERVER['REQUEST_URI'];
        if ( is_file( $req )) {
            return; // don't reduce perf by redirecting files to home url
        }
        // pull the parent directory and convert to site url
        $base_dir = dirname( $req );
        $parent_url = site_url( $base_dir );
        // redirect to parent directory
        wp_redirect( $parent_url, 301 );
        exit();
    }
}
add_action( 'template_redirect', '__404_template_redirect' );

<!-- //X-HEADERS -->

add_filter('pre_comment_content', 'wp_specialchars');
add_action( 'send_headers', 'add_header_xcontenttype' );
function add_header_xcontenttype() {
header( 'X-Content-Type-Options: nosniff' );
}
add_action( 'send_headers', 'add_header_xframeoptions' );
function add_header_xframeoptions() {
header( 'X-Frame-Options: SAMEORIGIN' );
}
add_action( 'send_headers', 'add_header_xxssprotection' );
function add_header_xxssprotection() {
header( 'X-XSS-Protection: 1;mode=block' );
}
<!-- // Resize Image -->
function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
}
<!-- /*THUMBNAILS IMAGENES*/ -->
add_image_size( 'revista_home', 550, 550, true );
// increase file size
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );
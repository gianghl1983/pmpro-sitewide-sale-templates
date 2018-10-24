<?php
/*
Plugin Name: Paid Memberships Pro - Sitewide Sale Templates
Plugin URI: https://www.paidmembershipspro.com/add-ons/sitewide-sale/
Description: A collection of templates for the Paid Memberships Pro - Sitewide Sale Add On.
Version: .1
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com
Text Domain: pmpro-sitewide-sale-templates
*/

function pmpro_sitewide_sale_templates_register_styles() {
	wp_register_style( 'pmpro-sitewide-sale-templates-styles', plugins_url( 'css/pmpro-sitewide-sale-templates.css', __FILE__ ) );
	wp_enqueue_style( 'pmpro-sitewide-sale-templates-styles' );
}
add_action( 'wp_enqueue_scripts', 'pmpro_sitewide_sale_templates_register_styles' );

function pmproswst_shortcode_atts( $out, $default_atts, $atts, $shortcode  ) {
    $default_atts[] = array(
		'template' => false,
	);
	return $default_atts;
}
add_filter( 'shortcode_atts_pmpro_sws', 'pmproswst_shortcode_atts', 10, 4 );

function pmproswst_body_class( $classes ) {
	global $post;
    $pattern = get_shortcode_regex();
	if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
        && array_key_exists( 2, $matches )
        && in_array( 'pmpro_sws', $matches[2] ) )
    {
        // shortcode is being used
		$shortcode_string = $matches[0][0];
		$shortcode_string = str_replace( "[pmpro_sws", "", $shortcode_string );
		$shortcode_string = substr( $shortcode_string, 0, strlen($shortcode_string) - 1 );
		$atts = shortcode_parse_atts( $shortcode_string );
		$template = $atts['template'];
		if ( ! empty( $template ) ) {
			$classes[] = 'pmpro-sitewide-sale-landing-page-' . $template;
		}
    }
	
	return $classes;
}
add_filter( 'body_class', 'pmproswst_body_class', 15 );

function pmproswst_pmpro_sws_landing_page_content( $r, $atts ) {
	if( ! empty( $atts ) ) {
		$template = $atts[ 'template' ];
	}
	if ( isset( $template ) ) {
		$newcontent = '<div id="pmpro_sitewide_sale_landing_page_template-' . esc_html( $template ) . '" class="pmpro_sitewide_sale_landing_page_template">';
		$newcontent .= $r;
		$newcontent .= '</div>';
		$r = $newcontent;
	}
	
	return $r;
}
add_filter( 'pmpro_sws_landing_page_content', 'pmproswst_pmpro_sws_landing_page_content', 10, 2 );

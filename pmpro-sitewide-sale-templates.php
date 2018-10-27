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

function pmproswst_memberlite_before_content( ) {
	global $post;
	if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $shortcode ) {
			if ( 'pmpro_sws' === $shortcode[2] ) {
				$shortcode_atts = shortcode_parse_atts( $shortcode[3] );
				if ( ! empty( $shortcode_atts['template'] ) ) {
					$classes[] = 'pmpro-sitewide-sale-landing-page-' . $shortcode_atts['template'];
				}
			}
		}
	}
}
//add_action( 'memberlite_before_content', 'pmproswst_memberlite_before_content' );

function pmproswst_body_class( $classes ) {
	global $post;
    $pattern = get_shortcode_regex();
	if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $shortcode ) {
			if ( 'pmpro_sws' === $shortcode[2] ) {
				$shortcode_atts = shortcode_parse_atts( $shortcode[3] );
				if ( ! empty( $shortcode_atts['template'] ) ) {
					$classes[] = 'pmpro-sitewide-sale-landing-page-' . $shortcode_atts['template'];
				}
			}
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
		if ( in_array( $template, array( 'photo', 'scroll' ) ) ) {
			$background_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_queried_object_id() ), 'full' );
			if( ! empty( $background_image[0] ) ) {
				$newcontent .= '<div class="pmpro_sitewide_sale_landing_page_template-background-image" style="background-image: url(' . $background_image[0] . ')">';
				$newcontent .= $r;
				$newcontent .= '</div> <!-- .pmpro_sitewide_sale_landing_page_template-background-image -->';
				$newcontent .= '</div> <!-- .pmpro_sitewide_sale_landing_page_template -->';
			}
		} else {
			$newcontent .= $r;
			$newcontent .= '</div> <!-- .pmpro_sitewide_sale_landing_page_template -->';
		}
		$r = $newcontent;
	}
	
	return $r;
}
add_filter( 'pmpro_sws_landing_page_content', 'pmproswst_pmpro_sws_landing_page_content', 10, 2 );

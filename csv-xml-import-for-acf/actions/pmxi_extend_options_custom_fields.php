<?php
/**
 * @param $post_type
 * @param $post
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pmai_pmxi_extend_options_custom_fields($post_type, $post)
{
	$acf_controller = new PMAI_Admin_Import();										
	$acf_controller->index($post_type, $post);
}

<?php
/**
 * Block Render Callback Template
 *
 * This file is used to render dynamic blocks via PHP.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/#render_callback
 *
 * @package Base_Theme_Package
 * @since 1.0.0
 */


$sts_var_testimonial_ids = $attributes['testimonialIds'] ?? null;
$sts_var_post_values = array_merge($sts_var_testimonial_ids, $sts_var_stay_ids);
$selectionMode = $attributes['selectionMode'] ?? 'recent';

var_dump($sts_var_post_values);

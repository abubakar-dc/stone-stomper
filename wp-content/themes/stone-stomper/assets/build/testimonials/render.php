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
$selectionMode = $attributes['selectionMode'] ?? 'recent';

var_dump($attributes['testimonialIds']);
var_dump($selectionMode);



?>

<?php if($selectionMode === 'recent'){ ?>

	<h2>Recetn Code</h2>

<?php } else { ?>
		<h2>manual Code </h2>
<?php }  ?>

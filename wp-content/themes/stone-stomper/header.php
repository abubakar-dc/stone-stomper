<?php
/**
 * The template for displaying website header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

// Page Tags - Advanced custom fields variables.
$sts_var_tracking = $sts_option_fields['custom_scripts'] ?? '';
$sts_var_ccss     = $sts_option_fields['custom_css'] ?? '';
$sts_var_hscripts = $sts_option_fields['head_scripts'] ?? '';
$sts_var_bscripts = $sts_option_fields['body_scripts'] ?? '';

$sts_var_tbar_vsblty     = $sts_option_fields['sts_var_tbar_vsblty'] ?? null;
$sts_var_tbar_btn     = $sts_option_fields['sts_var_tbar_btn'] ?? null;
$sts_var_tbar_text    = $sts_option_fields['sts_var_tbar_text'] ?? null;


$sts_var_tohdr_btn     = $sts_option_fields['sts_var_tohdr_btn'] ?? null;
$sts_var_tohdr_btn_two = $sts_option_fields['sts_var_tohdr_btn_two'] ?? null;
$sts_var_tbar_vsblty   = $sts_option_fields['sts_var_tbar_vsblty'] ?? null;
$sts_var_tbar_text     = $sts_option_fields['sts_var_tbar_text'] ?? null;
$sts_var_tbar_btn      = $sts_option_fields['sts_var_tbar_btn'] ?? null;
// Page variables - Advanced custom fields variables.

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<?php
		// Add Head Scripts.
	if ( StoneStomper::if_live() ) {

		if ( '' !== $sts_var_hscripts ) {
			echo html_entity_decode( $sts_var_hscripts, ENT_QUOTES );
		}
	}
	?>
	<link rel="apple-touch-icon" sizes="180x180"
		href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32"
		href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16"
		href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/favicon-16x16.png">
	<link rel="icon" sizes="any"
		href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/favicon.ico">
	<link rel="icon" type="image/svg+xml"
		href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/icon.svg">
	<link rel="manifest"
		href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/site.webmanifest">
	<meta name="theme-color" content="#0047FE">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="application-name" content="Stone Stomper">
	<!-- Windows Phone -->
	<meta name="msapplication-navbutton_color" content="#0047FE">
	<meta name="msapplication-TileColor" content="#0047FE">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="msapplication-TileImage"
		content="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/pwa/pwa-icon-144.png">
	<!-- iOS Safari -->
	<meta name="apple-mobile-web-app-status-bar-style" content="#0047FE">
	<?php
		// Tracking Code.
	if ( '' !== $sts_var_tracking ) {
		echo html_entity_decode( $sts_var_tracking, ENT_QUOTES );
	}

		// Custom CSS.
	if ( '' !== $sts_var_ccss ) {
		echo '<style type="text/css">';
		echo html_entity_decode( $sts_var_ccss, ENT_QUOTES );
		echo '</style>';
	}
	?>
	<?php wp_head(); ?> <script>
	"serviceWorker" in navigator && window.addEventListener("load", function() {
		navigator.serviceWorker.register("/sw.js").then(function(e) {
			console.log("ServiceWorker registration successful with scope: ", e.scope)
		}, function(e) {
			console.log("ServiceWorker registration failed: ", e)
		})
	});
	jQuery(document).ready(function() {
		if (jQuery('#top-bar-ajax').length > 0) {
			jQuery('#top-bar-ajax').topBar();
		}
	});
	</script>

</head>

<body <?php body_class(); ?>> <?php wp_body_open(); ?>
	<?php
	if ( StoneStomper::if_live() ) {
		if ( '' !== $sts_var_bscripts ) {
			?>
			<div style="display: none;">
				<?php echo html_entity_decode( $sts_var_bscripts, ENT_QUOTES ); ?>
			</div>
		<?php }
	}
	?>

	<a class="skip-link screen-reader-text"
		href="#page-section"><?php esc_html_e( 'Skip to content', 'basetheme_td' ); ?></a>
	<header id="header-section" class="header-section">
		<!-- Header Start -->
		<?php if ( $sts_var_tbar_vsblty ) { ?>
			<div class="top-bar" id="top-bar-ajax" style="display:none;">
				<div class="header-wrapper">
					<div class="top-bar-text">
						<?php
						if ( $sts_var_tbar_text ) {
							echo html_entity_decode( $sts_var_tbar_text );
						}
						?>
						<?php
						if ( $sts_var_tbar_btn ) {
							echo StoneStomper::button( $sts_var_tbar_btn, '' );
						}
						?>
					</div>
				</div>
				<div class="top-bar-cross">
					<span>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/topbar-cross-icon.svg"
							width="16" height="16" alt="<?php esc_attr_e( 'Top bar', 'basetheme_td' ); ?>">
					</span>
				</div>
			</div>
		<?php } ?>
		<div class="header-wrapper header-inner d-flex align-items-center justify-content-between">
			<div class="header-logo logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img
						src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/site-logo.svg"
						alt="Site Logo" /></a>
			</div>
			<div class="right-header header-navigation">
				<div class="nav-overlay">
					<div class="nav-container">
						<div class="header-nav">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'header-nav',
									'fallback_cb'    => 'StoneStomper::nav_fallback',
									'walker'         => new StoneStomper\Walker\WP_Theme_Walker_Nav(),
									'container'      => 'nav',
								)
							);
							?>
						</div>
						<div class="header-btns desktop-hide">
							<?php echo do_shortcode('[whmc_mini_cart]');
							if ( function_exists( 'wc_get_page_id' ) ) {
								$account_page_url = get_permalink( wc_get_page_id( 'myaccount' ) );
								?>
								<a href="<?php echo esc_url( $account_page_url ); ?>" class="account-icon">
									<!-- Example SVG icon (user/account) -->
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
								</a>
								<?php
							}

							?>
						</div>
					</div>
				</div>
				<div class="menu-btn">
					<span class="top"></span>
					<span class="middle"></span>
					<span class="bottom"></span>
				</div>
			</div>
			<div class="header-btns">

				<?php echo do_shortcode('[whmc_mini_cart]');
				if ( function_exists( 'wc_get_page_id' ) ) {
				$account_page_url = get_permalink( wc_get_page_id( 'myaccount' ) );
				?>
				<a href="<?php echo esc_url( $account_page_url ); ?>" class="account-icon">
					<!-- Example SVG icon (user/account) -->
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
				</a>
				<?php
				}
				?>

			</div>
			<!-- header buttons -->
		</div>
		<!-- Header End -->
	</header>
	<!-- Main Area Start -->
	<main id="main-section" class="main-section">

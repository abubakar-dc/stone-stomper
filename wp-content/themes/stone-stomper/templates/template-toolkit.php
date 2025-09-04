<?php
/**
 * Template Name: Toolkit
 * Template Post Type: page
 *
 * This template is for displaying home page.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

// Include header.

get_header();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Toolkit - StoneStomper</title>
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/styles.min.css" />
</head>

<body>
	<header class="header-section">
		<div class="top-bar center-align" id="top-bar-ajax">
			<div class="header-wrapper">
				<div class="top-bar-text">Lorem ipsum dolor, sit amet consectetur adipisicing elit.
					<a href="">Learn More</a>
				</div>
			</div>
			<div class="top-bar-cross">
				<span>
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/topbar-cross-icon.svg" width="16" height="16" alt="Top bar">
				</span>
			</div>
		</div>
		<div class="header-wrapper header-inner d-flex align-items-center justify-content-between">
			<div class="header-logo logo">
				<a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/site-logo.svg" alt="Site Logo" /></a>
			</div>
			<div class="right-header header-navigation">
				<div class="nav-overlay">
					<div class="nav-container">
						<div class="header-nav">
							<ul>
								<li><a href="toolkit.html">Toolkit</a></li>
								<li class="menu-item current-menu-item menu-item-has-children">
									<a href="#">Forms</a>
									<ul>
										<li><a href="./basetheme/gravity-form.html">Gravity Form</a></li>
										<li><a href="./basetheme/hubspot-form.html">Hubspot Form</a></li>
									</ul>
								</li>
								<li><a href="./basetheme/html-formatting-tags.html">Html Tags</a></li>
								<li><a href="./basetheme/wordpress-blocks.html">WordPress Blocks</a></li>
								<li class="menu-item current-menu-item menu-item-has-children">
									<a href="./basetheme/#">Default Pages</a>
									<ul>
										<li class="menu-item current-menu-item">
											<a href="./basetheme/blog-template.html">Blog
												Template</a>
										</li>
										<li><a href="./basetheme/default-archive.html">Default Archive</a></li>
										<li><a href="./basetheme/default-index.html">Default index</a></li>
										<li><a href="./basetheme/default-page.html">Default Page</a></li>
										<li><a href="./basetheme/default-post.html">Default post</a></li>
										<li><a href="./basetheme/404.html">404</a></li>

										<li><a href="./basetheme/default-archive.html">Default Archive</a></li>
									</ul>
								</li>
							</ul>
							<div class="header-btns desktop-hide">
								<a href="#" class="button" title="Get In touch">Get In touch</a>
							</div>
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
				<a href="#" class="button" title="Get In touch">Get In touch</a>
			</div>
		</div>
	</header>
	<main id="main-section" class="main-section">
		<section id="hero-section" class="hero-section hero-section-default">
			<!-- hero start -->
			<div class="hero-default center-align ctn-760">
				<div class="wrapper">
					<h1 class="heading">Toolkit</h1>
					<div class="banner-text">
						<p> Vitae morbi donec in sed eget sit consectetur cursus. Sit dictum sagittis egestas felis sed
							viverra
							libero vitae. Phasellus a, ullamcorper in nulla viverra arcu. Sed scelerisque ornare auctor
							sed neque.
						</p>
						<a href="#" class="button">CTA Button</a>
					</div>
				</div>
			</div>
			<!-- hero end -->
		</section>
		<section id="" class="page-section">


			<div class="st-s72"></div>
			<!-- image-at-right variation -->
			<section>
				<div class="wrapper">
					<div class="iat-section two-columns justify-content-between align-items-center image-at-right">
						<div class="iat-image column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/uploads/default-image.webp" alt="">
						</div>
						<div class="iat-text column">
							<h2>iat heading</h2>
							<h2 style="visibility: hidden; opacity: 0;">iat heading</h2>
							<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime sint ad aperiam atque
								eos quaerat
								quod nemo fugiat consequatur alias?</p>
							<a href="#" class="button">Get in Touch</a>
						</div>
					</div>
				</div>
			</section>
			<div class="st-s96"></div>
			<!-- image-at-left variation-->
			<section>
				<div class="wrapper">
					<div class="iat-section two-columns justify-content-between align-items-center image-at-left">
						<div class="iat-image column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/admin/defaults/default-image.webp" alt="">
						</div>
						<div class="iat-text column">
							<h2>iat heading</h2>
							<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maxime sint ad aperiam atque
								eos quaerat
								quod nemo fugiat consequatur alias?</p>
							<a href="#" class="button">Get in Touch</a>
						</div>
					</div>
				</div>
			</section>
			<div class="st-s60"></div>

		</section>
	</main>
	<footer id="footer-section" class="footer-section">
		<!-- Footer Start -->
		<div class="footer-ctn">
			<div class="wrapper">
				<div class="footer-widgets d-flex justify-content-between flex-wrap">
					<div class="single-widget">
						<div class="footer-logo">
							<a href="#">
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/site-logo-white.svg" alt="Logo">
							</a>
						</div>
						<h5> Wordpress theme by DevCause.</h5>
						<div class="address">
							<p>501 Congress Avenue, Suite 150, Austin, Texas 78701<br> Call <a
									href="tel:512.215.4236">512.215.4236</a></p>
						</div>
						<div class="social-icons d-flex">
							<a href="#" class="facebook flex-center"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/facebook-icon.svg"
									alt="Facebook Icon"></a> <a href="#" class="tweeter flex-center"><img
									src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/linkedin-icon.svg" alt="LinkedIn Icon"></a> <a href="#"
								class="linkdhin flex-center"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/twitter-icon.svg"
									alt="Twitter Icon"></a> <a href="#" class="instagram flex-center"><img
									src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/src/images/instagram-icon.svg" alt="Instagram Icon"></a>
						</div>
					</div>
					<div class="single-widget">
						<div class="footer-nav">
							<div class="menu-footer-nav-one-container">
								<ul id="menu-footer-nav-one" class="menu">
									<li><a href="#">Front Page</a></li>
									<li><a href="#">Clearing Floats</a></li>
									<li><a href="#">Page B</a></li>
									<li><a href="#">Page A</a></li>
									<li><a href="#">Team</a></li>
									<li><a href="#">Resources page</a></li>
									<li><a href="#">Alignment</a></li>
									<li><a href="#">Sample Page</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="single-widget">
						<div class="footer-nav">
							<div class="menu-footer-nav-two-container">
								<ul id="menu-footer-nav-two" class="menu">
									<li id="menu-item-1901"
										class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home first-menu-item menu-item-1901">
										<a href="http://basethemedevcause.local/">Home</a>
									</li>
									<li><a href="#">Sample Page</a></li>
									<li><a href="#">Level 2a</a></li>
									<li><a href="#">Level 2b</a></li>
									<li><a href="#">Block: Image</a></li>
									<li><a href="#">Block: Button</a></li>
									<li><a href="#">Alignment</a></li>
									<li> <a href="#">Sample Page</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="single-widget">
						<div class="footer-nav">
							<div class="menu-footer-menu-three-container">
								<ul id="menu-footer-menu-three" class="menu">
									<li><a href="#">Sample Page</a></li>
									<li><a href="#">Level 2b</a></li>
									<li><a href="#">Block: Image</a></li>
									<li><a href="#">Block: Button</a></li>
									<li><a href="#">Alignment</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="st-s72"></div>
				<div class="footer-bottom d-flex align-items-center justify-content-between">
					<div class="copy-right">Copyright © 2024 By DevCause</div>
					<div class="legal-nav">
						<div class="menu-legal-nav-container">
							<ul id="menu-legal-nav" class="menu">
								<li>
									<a href="#">Sitemap</a>
								</li>
								<li><a href="#">Terms of Use</a></li>
								<li>
									<a href="#">Privacy Policy</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/js/vendors/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/scripts.min.js"></script>

</body>

</html>

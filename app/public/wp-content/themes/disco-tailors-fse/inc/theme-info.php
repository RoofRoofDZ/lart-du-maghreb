<?php
/**
 * Add Theme info Page
 */

function disco_tailors_fse_menu() {
	add_theme_page( esc_html__( 'Disco Tailors FSE', 'disco-tailors-fse' ), esc_html__( 'About Disco Tailors FSE', 'disco-tailors-fse' ), 'edit_theme_options', 'about-disco-tailors-fse', 'disco_tailors_fse_theme_page_display' );
}
add_action( 'admin_menu', 'disco_tailors_fse_menu' );

function disco_tailors_fse_admin_theme_style() {
	wp_enqueue_style('disco-tailors-fse-custom-admin-style', esc_url(get_template_directory_uri()) . '/assets/css/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'disco_tailors_fse_admin_theme_style');

/**
 * Display About page
 */
function disco_tailors_fse_theme_page_display() {
	$theme = wp_get_theme();

	if ( is_child_theme() ) {
		$theme = wp_get_theme()->parent();
	} ?>

		<div class="Grace-wrapper">
			<div class="Grcae-info-holder">
				<div class="Grcae-info-holder-content">
					<div class="Grace-Welcome">
						<h1 class="welcomeTitle"><?php esc_html_e( 'About Theme Info', 'disco-tailors-fse' ); ?></h1>                        
						<div class="featureDesc">
							<?php echo esc_html__( 'Disco Tailors FSE is a free tailor WordPress theme enabling you to design a professional and visually captivating website without any coding knowledge.', 'disco-tailors-fse' ); ?>
						</div>
						
                        <h1 class="welcomeTitle"><?php esc_html_e( 'Theme Features', 'disco-tailors-fse' ); ?></h1>

                        <h2><?php esc_html_e( 'Block Compatibale', 'disco-tailors-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'disco-tailors-fse' ); ?>
                        </div>
                        
                        <h2><?php esc_html_e( 'Responsive Ready', 'disco-tailors-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'disco-tailors-fse' ); ?>
                        </div>
                        
                        <h2><?php esc_html_e( 'Cross Browser Compatible', 'disco-tailors-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE11 and above.', 'disco-tailors-fse' ); ?>
                        </div>
                        
                        <h2><?php esc_html_e( 'E-commerce', 'disco-tailors-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'disco-tailors-fse' ); ?>
                        </div>

					</div> <!-- .Grace-Welcome -->
				</div> <!-- .Grcae-info-holder-content -->
				
				
				<div class="Grcae-info-holder-sidebar">
                        <div class="sidebarBX">
                            <h2 class="sidebarBX-title"><?php echo esc_html__( 'Get Strategist PRO', 'disco-tailors-fse' ); ?></h2>
                            <p><?php echo esc_html__( 'More features availbale on Premium version', 'disco-tailors-fse' ); ?></p>
                            <a href="<?php echo esc_url( 'https://gracethemes.com/themes/tailor-shop-wordpress-theme/' ); ?>" target="_blank" class="button"><?php esc_html_e( 'Get the PRO Version &rarr;', 'disco-tailors-fse' ); ?></a>
                        </div>


						<div class="sidebarBX">
							<h2 class="sidebarBX-title"><?php echo esc_html__( 'Important Links', 'disco-tailors-fse' ); ?></h2>

							<ul class="themeinfo-links">
                                <li>
									<a href="<?php echo esc_url( 'https://gracethemesdemo.com/disco-tailors/' ); ?>" target="_blank"><?php echo esc_html__( 'Demo Preview', 'disco-tailors-fse' ); ?></a>
								</li>                               
								<li>
									<a href="<?php echo esc_url( 'https://gracethemesdemo.com/documentation/disco-tailors/#homepage-lite' ); ?>" target="_blank"><?php echo esc_html__( 'Documentation', 'disco-tailors-fse' ); ?></a>
								</li>
								
								<li>
									<a href="<?php echo esc_url( 'https://gracethemes.com/wordpress-themes/' ); ?>" target="_blank"><?php echo esc_html__( 'View Our Premium Themes', 'disco-tailors-fse' ); ?></a>
								</li>
							</ul>
						</div>

						<div class="sidebarBX">
							<h2 class="sidebarBX-title"><?php echo esc_html__( 'Leave us a review', 'disco-tailors-fse' ); ?></h2>
							<p><?php echo esc_html__( 'If you are satisfied with Disco Tailors FSE, please give your feedback.', 'disco-tailors-fse' ); ?></p>
							<a href="https://wordpress.org/support/theme/disco-tailors-fse/reviews/" class="button" target="_blank"><?php esc_html_e( 'Submit a review', 'disco-tailors-fse' ); ?></a>
						</div>

				</div><!-- .Grcae-info-holder-sidebar -->	

			</div> <!-- .Grcae-info-holder -->
		</div><!-- .Grace-wrapper -->
<?php } ?>
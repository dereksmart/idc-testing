<?php
/*
Plugin Name: IDC Testing stuff for Jeff
Version: 999.0
*/

// Opt in
add_filter( 'jetpack_sync_idc_optin', '__return_true' );

add_action( 'admin_init', 'idc_testing_admin_init' );
function idc_testing_admin_init() {
	if ( ! class_exists( 'Jetpack' ) ) {
		return;
	}

	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url = parse_url( $url, PHP_URL_PATH );
	if ( isset( $_GET[ 'trigger-idc' ] ) ) {
		Jetpack_Options::delete_option( 'safe_mode_confirmed' );
		Jetpack_Options::update_option(
			'sync_error_idc',
			array_merge(
				Jetpack::get_sync_error_idc_option(),
				array(
					'wpcom_home' => 'fakehome.com',
					'wpcom_siteurl' => 'fakesite.com'
				)
			)
		);
		header( 'Location: ' . $url );
	} else if ( isset( $_GET[ 'clear-idc' ] ) ) {
		Jetpack_Options::delete_option( 'sync_error_idc' );
		header( 'Location: ' . $url );
	}
}

add_action( 'admin_notices', 'idc_testing_actions' );
function idc_testing_actions() { ?>
	<div class="notice" id="idc-testing">
		<form action="" method="get">
			<button name="trigger-idc">
				Trigger IDC
			</button>
			<button name="clear-idc">
				Clear IDC
			</button>
		</form>
	</div>
<?php }

add_action( 'admin_enqueue_scripts', 'idc_testing_styles' );
function idc_testing_styles() { ?>
	<style>
		#idc-testing {
			position: fixed;
			bottom: 0;
			right: 0;
			background-color: #72af3a;
			z-index: 10000;
		}
	</style>
<?php }

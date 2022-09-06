<?php
if ( ! class_exists( 'WPSmushNextGenBulk' ) ) {
	class WPSmushNextGenBulk extends WpSmushNextGen {

		function __construct() {
			add_action( 'wp_ajax_wp_smushit_nextgen_bulk', array( $this, 'smush_bulk' ) );
		}

		function smush_bulk() {

			global $wpsmushnextgenstats, $wpsmush_stats, $wpsmushit_admin, $wpsmushnextgenadmin, $WpSmush;

			$stats = array();

			if ( empty( $_GET['attachment_id'] ) ) {
				wp_send_json_error( 'missing id' );
			}

			$send_error = false;

			$atchmnt_id = absint( (int) $_GET['attachment_id'] );

			$smush  = $this->smush_image( $atchmnt_id, '', false );

			if ( is_wp_error( $smush ) ) {
				$send_error = true;
				$msg = '';
				$error = $smush->get_error_message();
				//Check for timeout error and suggest to filter timeout
				if( strpos( $error, 'timed out') ) {
					$msg = '<p class="wp-smush-error-message">' . esc_html__( "Smush request timed out, You can try setting a higher value ( > 60 ) for `WP_SMUSH_API_TIMEOUT`.", "wp-smushit" ) . '</p>';
				}
			} else {
				//Check if a resmush request, update the resmush list
				if( !empty( $_REQUEST['is_bulk_resmush']) && $_REQUEST['is_bulk_resmush'] ) {
					$wpsmushit_admin->update_resmush_list( $atchmnt_id, 'wp-smush-nextgen-resmush-list' );
				}
			}

			//Get the Latest Stats
			$stats = $wpsmushnextgenstats->get_smush_stats();

			if ( $WpSmush->lossy_enabled ) {
				//Most of the time the stats would be update and the function won't need to go thorugh all the
				//images to get the count, but in case it has to, we provide the SMushed attachment list
				$stats['super_smushed'] = $wpsmush_stats->super_smushed_count('nextgen', $wpsmushnextgenstats->get_ngg_images('smushed' ) );
			}
			if( empty( $wpsmushnextgenadmin->resmush_ids ) ) {
				$wpsmushnextgenadmin->resmush_ids = get_option( 'wp-smush-nextgen-resmush-list' );
			}

			$resmush_count = ! empty( $wpsmushnextgenadmin->resmush_ids ) ? count( $wpsmushnextgenadmin->resmush_ids ) : count( $wpsmushnextgenadmin->resmush_ids = get_option( 'wp-smush-nextgen-resmush-list' ) );
			$smushed_count = $wpsmushnextgenstats->get_ngg_images( 'smushed', true );

			$stats['total'] = $wpsmushnextgenstats->total_count();

			$stats['smushed'] = ! empty( $wpsmushnextgenadmin->resmush_ids ) ? $smushed_count - $resmush_count : $smushed_count;

			$send_error ? wp_send_json_error( array( 'stats'     => $stats,
			                                         'error_msg' => $msg
			) ) : wp_send_json_success( array( 'stats' => $stats ) );
		}

	}
}
<?php

class WPPluginAnalytics {

	private $plugslug;
	private $file;

	function __construct( $plugslug, $file ) {

		// Init variables
		$plugslug = preg_replace('/[^a-z0-9]/', '', strtolower( $plugslug ));
		$this->plugslug = $plugslug;
		$this->file = $file;

		// Hooks
		register_activation_hook( $this->file, array( &$this, 'install_plugin' ) );
		register_deactivation_hook( $this->file, array( &$this, 'deactivate_plugin' ) );


	}

	private function socketIO($method, $host, $port, $path, $data = "", $timeout = 5) {

		$fp = @fsockopen($host, $port, $errno, $errstr, $timeout);

		if (!$fp) { return false; }

		@fputs($fp, "$method $path HTTP/1.1\r\nHost: $host\r\n");

		if ($method == "POST") {
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($data) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data);
		} else {
			fputs($fp, "Connection: close\r\n\r\n");
		}

		$inData = "";

		while (($data = @fread($fp, 4096)) !== '' && $data !== false) {
			$inData .= $data;
		}

		@fclose($fp);

		$endHeaders = strpos($inData, "\r\n\r\n");
		return substr($inData, $endHeaders + 4);
	}

        // Runs when the plugin is activated
        function install_plugin() {
                $data = http_build_query(array ('plugin' => $this->plugslug,
						'action' => 'activate',
						'site' => get_site_url(),
						'wpversion' => get_bloginfo('version'),
						'version' => $this->version()));

                $status = $this->socketIO("POST", "dev.nickpowers.info", 80, "/installed.php", $data);
        }

	// Runs when the plugin is deactivated
	function deactivate_plugin() {
		$data = http_build_query(array ('plugin' => $this->plugslug,
						'action' => 'deactivate',
						'site' => get_site_url(),
						'wpversion' => get_bloginfo('version'),
						'version' => $this->version()));

		$status = $this->socketIO("POST", "dev.nickpowers.info", 80, "/installed.php", $data);
	}

	// Get plugin version
	function version() {

	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( $this->file ) ) );
	$plugin_file = basename( ( $this->file ) );
	return $plugin_folder[$plugin_file]['Version'];
	}


}
?>

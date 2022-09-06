<?php
/*
Plugin Name: NoSpamNX
Plugin URI: http://wordpress.org/extend/plugins/nospamnx/
Description: To protect your blog from automated spambots, this plugin adds invisible formfields to your comment form.
Version: 5.2.1
Author: Sven Kubiak
Author URI: http://svenkubiak.de
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: nospamnx
Domain Path: /languages

Copyright 2008-2016 Sven Kubiak

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
global $wp_version;
define('NXISWP30', version_compare($wp_version, '3.0', '>='));
define('NXCURLTO', 5);

if (!class_exists('NoSpamNX'))
{
	Class NoSpamNX
	{
		var $nospamnx_names;
		var $nospamnx_count;
		var $nospamnx_operate;
		var $nospamnx_blacklist;
		var $nospamnx_blacklist_part;
		var $nospamnx_blacklist_global;
		var $nospamnx_blacklist_global_url;
		var $nospamnx_blacklist_global_update;
		var $nospamnx_blacklist_global_lu;
		var $nospamnx_activated;
		var $nospamnx_commentid;
		var $nospamnx_salt;

		function nospamnx() {
			if (function_exists('load_plugin_textdomain')) {
				load_plugin_textdomain('nospamnx', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
			}
			if (!NXISWP30) {
				add_action('admin_notices', array(&$this, 'wpVersionFail'));
				return;
			}
			if (function_exists('register_activation_hook')) {
				register_activation_hook(__FILE__, array(&$this, 'activate'));
			}
			if (function_exists('register_uninstall_hook')) {
				register_uninstall_hook(__FILE__, 'uninstall');
			}

			$this->getOptions();
			$this->loadGlobalBlacklist();

			add_action('init', array(&$this, 'checkCommentForm'));
			add_action('admin_menu', array(&$this, 'nospamnxAdminMenu'));
			add_action('rightnow_end', array(&$this, 'nospamnxStats'));
			add_action('comment_form', array(&$this, 'addHiddenFields'));
			add_filter('comment_form_field_comment', array(&$this, 'replaceCommentField'));
			add_filter('plugin_action_links', array('NoSpamNX', 'nospamnxSettingsLinks'),9999,2);
			add_filter('plugin_row_meta', array('NoSpamNX', 'nospamnxPluginLinks'),9999,2);
			add_action('bbp_theme_after_topic_form', array(&$this, 'addHiddenFields'));
			add_action('bbp_theme_after_reply_form', array(&$this, 'addHiddenFields'));
		}

		function wpVersionFail() {
			$this->displayError(__('Your WordPress is to old. NoSpamNX requires at least WordPress 3.0!','nospamnx'));
		}

		function addHiddenFields() {
			$time = time();
			$nospamnx = $this->nospamnx_names;
			echo '<p style="display:none;">';
			echo '<input type="text" name="nxts" value="'.$time.'" />';
			echo '<input type="text" name="nxts_signed" value="'.sha1($time . $this->nospamnx_salt).'" />';
			if (rand(1,2) == 1) {
				echo '<input type="text" name="'.$nospamnx['nospamnx-1'].'" value="" />';
				echo '<input type="text" name="'.$nospamnx['nospamnx-2'].'" value="'.$nospamnx['nospamnx-2-value'].'" />';
			} else {
				echo '<input type="text" name="'.$nospamnx['nospamnx-2'].'" value="'.$nospamnx['nospamnx-2-value'].'" />';
				echo '<input type="text" name="'.$nospamnx['nospamnx-1'].'" value="" />';
			}
			echo '</p>';
		}

		function checkCommentForm() {
			if (basename($_SERVER['PHP_SELF']) == 'wp-comments-post.php' ||	(isset($_POST['action']) &&	($_POST['action'] == 'bbp-new-topic' ||	$_POST['action'] == 'bbp-new-reply'))) {
				//first line of defense -> replaced comment field (by Marcel Bokhorst)
				if (isset($_POST['comment-replaced'])) {
					$hidden_field = $_POST['comment'];
					$plugin_field = $_POST['comment-' . $this->nospamnx_commentid];
					if (empty($hidden_field) && !empty($plugin_field)) {
						$_POST['comment'] = $plugin_field;
					} else {
						$this->birdbrained();
					}
				}

				//re-arange post vars for blacklist check (by Marcel Bokhorst)
				$author = isset($_POST['author']) 	? $_POST['author'] : null;
				$email = isset($_POST['email']) 	? $_POST['email'] : null;
				$url = isset($_POST['url']) 		? $_POST['url'] : null;
				$comment = isset($_POST['comment']) ? $_POST['comment'] : null;

				//re-arange post vars for bbPress (by Marcel Bokhorst)
				$author = isset($_POST['bbp_anonymous_name']) ? $_POST['bbp_anonymous_name'] : $author;
				$email = isset($_POST['bbp_anonymous_email']) ? $_POST['bbp_anonymous_email'] : $email;
				$url = isset($_POST['bbp_anonymous_website']) ? $_POST['bbp_anonymous_website'] : $url;
				$comment = isset($_POST['bbp_topic_content']) ? $_POST['bbp_topic_content'] : $comment;
				$comment = isset($_POST['bbp_reply_content']) ? $_POST['bbp_reply_content'] : $comment;

				//second line of defense -> local and global blacklist
				$this->blacklistCheck($author, $email, $url, $comment, $_SERVER['REMOTE_ADDR']);

				//third line of defense -> hidden fields and timestamp
				$nospamnx = $this->nospamnx_names;
				if (!array_key_exists($nospamnx['nospamnx-1'],$_POST)) {
					$this->birdbrained();
				} else if ($_POST[$nospamnx['nospamnx-1']] != "") {
					$this->birdbrained();
				} else if (!array_key_exists($nospamnx['nospamnx-2'],$_POST)) {
					$this->birdbrained();
				} else if ($_POST[$nospamnx['nospamnx-2']] != $nospamnx['nospamnx-2-value']) {
					$this->birdbrained();
				} else if (!array_key_exists('nxts',$_POST) || !array_key_exists('nxts_signed',$_POST)) {
					$this->birdbrained();
				} else if (sha1($_POST['nxts'] . $this->nospamnx_salt) != $_POST['nxts_signed']) {
					$this->birdbrained();
				} else if (time() < $_POST['nxts'] + apply_filters('nospamnx_comment_delay', 10)) {
					$this->delayed();
				}
			}
		}

		//replace comment form (by Marcel Bokhorst)
		function replaceCommentField($field) {
			if (!empty($this->nospamnx_commentid)) {
				$new_field = preg_replace("#<textarea(.*?)name=([\"\'])comment([\"\'])(.+?)</textarea>#s", "<textarea$1name=$2comment-" . $this->nospamnx_commentid . "$3$4</textarea><textarea name=\"comment\" rows=\"1\" cols=\"1\" style=\"display:none\"></textarea>", $field, 1);
				if (strcmp($field, $new_field)) {
					$new_field .= '<input type="hidden" name="comment-replaced" value="true" />';
				}
				return $new_field;
			} else {
				return $field;
			}
		}

		function birdbrained() {
			if ($this->nospamnx_operate == 'mark') {
				add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
			} else {
				$this->nospamnx_count++;
				$this->setOptions();
				$message = "<p>Sorry, but your comment seems to be Spam and has been blocked.</p>";
				$message .= "<p><a href='javascript:history.back()'>Back</a></p>";
				wp_die($message, '', array('response' => 403));
			}
		}

		function delayed() {
			$message = "<p>Sorry, but you are commenting to fast.</p>";
			$message .= "<p><a href='javascript:history.back()'>Back</a></p>";
			wp_die($message, '', array('response' => 403));
		}

		function blacklistCheck($author, $email, $url, $comment, $remoteip) {
			$blacklist = array(
				0 => $this->nospamnx_blacklist,
				1 => $this->nospamnx_blacklist_global
			);

			$author		= trim($author);
			$email 		= trim($email);
			$url 		= trim($url);
			$comment 	= trim($comment);
			$author		= strtolower($author);
			$email 		= strtolower($email);
			$url 		= strtolower($url);
			$comment 	= strtolower($comment);

			for ($i=0; $i <= 1; $i++) {
				$words = explode("\n", $blacklist[$i]);
				foreach ((array)$words as $word) {
					$word = trim($word);
					if (empty($word)) {
						continue;
					}

					if ($this->checkCIDR($word) == 1 && $this->checkIP($remoteip, $word) == 1) {
						$this->birdbrained();
					}

					$word = strtolower($word);
					if ($this->nospamnx_blacklist_part == 1) {
						$word = preg_quote($word, '#');
						$pattern = "#$word#i";
						if (preg_match($pattern, $author)  	||
							preg_match($pattern, $email)    ||
							preg_match($pattern, $url)      ||
							preg_match($pattern, $remoteip) ||
							preg_match($pattern, $comment)) {
							$this->birdbrained();
						}
					} else {
						if ($word == $author || $word == $email || $word == $url || $word == $remoteip || $word == $comment) {
							$this->birdbrained();
						}
					}
				}
			}
		}

		function generateNames() {
			$nospamnx = array(
				'nospamnx-1'		=> $this->generateRandomString(),
				'nospamnx-2'		=> $this->generateRandomString(),
				'nospamnx-2-value'	=> $this->generateRandomString()
			);
			return $nospamnx;
		}

		//Check an IP adress against a CIDR (from http://framework.zend.com/svn/framework/extras/incubator/library/ZendX/Whois/Adapter/Cidr.php)
		function checkIP ($ip, $cidr) {
	        list($base, $bits) = explode('/', $cidr);
	        list($a, $b, $c, $d) = explode('.', $base);
	        $i    = ($a << 24) + ($b << 16) + ($c << 8) + $d;
	        $mask = $bits == 0 ? 0: (~0 << (32 - $bits));
	        $low = $i & $mask;
	        $high = $i | (~$mask & 0xFFFFFFFF);
	        list($a, $b, $c, $d) = explode('.', $ip);
	        $check = ($a << 24) + ($b << 16) + ($c << 8) + $d;

	        if ($check >= $low && $check <= $high) {
	            return 1;
	        } else {
	            return 0;
	        }
		}

		function checkCIDR($word) {
			return preg_match("^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/(\d|[1-2]\d|3[0-2]))$^", $word);
		}

		function generateRandomString() {
			return substr(sha1(uniqid(rand(), true)), rand(8, 32));
		}

		function nospamnxAdminMenu() {
			add_options_page('NoSpamNX', 'NoSpamNX', 'manage_options', 'nospamnx', array(&$this, 'nospamnxOptionPage'));
		}

		function displayMessage($message) {
			echo "<div id='message' class='updated'><p>".$message."</p></div>";
		}

		function displayError($message) {
			echo "<div id='message' class='error'><p>".$message."</p></div>";
		}

		static function nospamnxSettingsLinks($links, $file) {
			if ($file == 'nospamnx/nospamnx.php' && function_exists("admin_url")) {
				$settings_link = '<a href="' . admin_url('options-general.php?page=nospamnx' ). '">' . __('Settings') . '</a>';
				array_push($links, $settings_link);
			}
			return $links;
		}

		static function nospamnxPluginLinks($links, $file) {
			if ($file == 'nospamnx/nospamnx.php' && function_exists("admin_url")) {
				$faq_link = '<a href="http://wordpress.org/extend/plugins/nospamnx/faq/" target="_blank">' . __('FAQ') . '</a>';
				array_push($links, $faq_link);
			}
			return $links;
		}

		function nospamnxOptionPage() {
			if (!current_user_can('manage_options')) {
				wp_die(__('Sorry, but you have no permissions to change settings.','nospamnx'), '', array('response' => 403));
			}

			(isset($_REQUEST['_wpnonce'])) 		? $nonce = $_REQUEST['_wpnonce'] : $nonce = '';
			(isset($_POST['save_settings'])) 	? $save_settings = $_POST['save_settings'] : $save_settings = '';
			(isset($_POST['reset_counter'])) 	? $reset_counter = $_POST['reset_counter'] : $reset_counter = '';
			(isset($_POST['update_blacklist'])) ? $update_blacklist = $_POST['update_blacklist'] : $update_blacklist = '';

			if ($save_settings == 1 && $this->verifyNonce($nonce)) {
				switch($_POST['nospamnx_operate']) {
					case 'block':
						$this->nospamnx_operate = 'block';
						break;
					case 'mark':
						$this->nospamnx_operate = 'mark';
						break;
					default:
						$this->nospamnx_operate = 'mark';
				}
				$this->setOptions();
				$this->displayMessage(__('NoSpamNX settings were saved successfully.','nospamnx'));
			} else if ($reset_counter == 1 && $this->verifyNonce($nonce)) {
				$this->nospamnx_count = 0;
				$this->nospamnx_activated = time();
				$this->setOptions();
				$this->displayMessage(__('NoSpamNX Counter was reseted successfully.','nospamnx'));
			} else if ($update_blacklist == 1 && $this->verifyNonce($nonce)) {
				(isset($_POST['blacklist'])) 				? $blacklist 		= $_POST['blacklist'] : $blacklist = '';
				(isset($_POST['blacklist_part'])) 			? $blacklist_part 	= $_POST['blacklist_part'] : $blacklist_part = '';
				(isset($_POST['blacklist_global_url'])) 	? $blacklist_url 	= $_POST['blacklist_global_url'] : $blacklist_url = '';
				(isset($_POST['blacklist_global_update'])) 	? $blacklist_update = $_POST['blacklist_global_update'] : $blacklist_update = '';
				$this->nospamnx_blacklist = $this->sortBlacklist($blacklist);
				$this->nospamnx_blacklist_part = $blacklist_part;
				$this->nospamnx_blacklist_global_url = $blacklist_url;
				$this->nospamnx_blacklist_global_update = $blacklist_update;
				$this->setOptions();
				$this->displayMessage(__('NoSpamNX Blacklist was updated successfully.','nospamnx'));
			}

			$mark = '';
			$block = '';
			switch ($this->nospamnx_operate) {
				case 'block':
					$block = 'checked';
				break;
				case 'mark':
					$mark = 'checked';
				break;
				default:
					$block = 'checked';
			}

			$confirm = __('Are you sure you want to reset the counter?','nospamnx');
			$nonce = wp_create_nonce('nospamnx-nonce');

			?>

			<div class="wrap">
				<div id="icon-options-general" class="icon32"></div>
				<p><h2><?php echo __('NoSpamNX Settings','nospamnx'); ?></h2></p>
				<div id="poststuff">
					<div class="postbox opened">
						<h3><?php echo __('Statistic','nospamnx'); ?></h3>
						<div class="inside">
							<table>
								<tr>
									<td valign="top"><p><b><?php $this->nospamnxStats(); ?></b></p></td>
									<td>
										<script id='fbg2qtl'>(function(i){var f,s=document.getElementById(i);f=document.createElement('iframe');f.src='//api.flattr.com/button/view/?uid=svenkubiak&url=http%3A%2F%2Fwordpress.org%2Fplugins%2Fnospamnx%2F';f.title='Flattr';f.height=62;f.width=55;f.style.borderWidth=0;s.parentNode.insertBefore(f,s);})('fbg2qtl');</script>
									</td>
								</tr>
							</table>
							<form action="options-general.php?page=nospamnx&_wpnonce=<?php echo $nonce ?>" method="post" onclick="return confirm('<?php echo $confirm; ?>');">
								<input type="hidden" value="1" name="reset_counter">
								<p><input name="submit" class='button-primary' value="<?php echo __('Reset','nospamnx'); ?>" type="submit" /></p>
							</form>
						</div>
					</div>

					<div class="postbox opened">
						<h3><?php echo __('Operating mode','nospamnx'); ?></h3>
						<div class="inside">
							<p><?php echo __('By default all Spambots are marked as Spam, but the recommended Mode is "block". If you are uncertain what will be blocked, select "Mark as Spam" at first and switch to "block" later on.','nospamnx'); ?></p>
							<form action="options-general.php?page=nospamnx&_wpnonce=<?php echo $nonce ?>" method="post">
							<table class="form-table">
									<tr>
										<th scope="row" valign="top"><b><?php echo __('Mode','nospamnx'); ?></b></th>
										<td>
											<input type="hidden" value="true" name="nospamnx_mode">
											<input type="radio" name="nospamnx_operate" <?php echo $block; ?> value="block"> <?php echo __('Block (recommended)','nospamnx'); ?>
											<br />
											<input type="radio" <?php echo $mark; ?> name="nospamnx_operate" value="mark"> <?php echo __('Mark as Spam','nospamnx'); ?>
										</td>
									</tr>
							</table>
							<input type="hidden" value="1" name="save_settings">
							<p><input name="submit" class='button-primary' value="<?php echo __('Save','nospamnx'); ?>" type="submit" /></p>
							</form>
						</div>
					</div>

					<div class="postbox opened">
						<h3><?php echo __('Blacklist','nospamnx'); ?></h3>
						<div class="inside">
							<p><?php echo __('By default the Entries in the Blacklist will match Substrings (e.g. \'foobar\' will match, if you have \'foo\' in your Blacklist). Uncheck the following Option to only match exact words.','nospamnx'); ?></p>
							<form action="options-general.php?page=nospamnx&_wpnonce=<?php echo $nonce ?>" method="post">
							<table class="form-table">
								<tr>
									<td colspan="2"><b><?php echo __('Match Substrings','nospamnx'); ?></b>&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="blacklist_part" <?php if ($this->nospamnx_blacklist_part == 1) {echo "checked";}?>/></td>
								</tr>
								<tr>
									<td width="50%"><b><?php echo __('Local Blacklist','nospamnx'); ?></b></td>
									<td width="50%"><b><?php echo __('Global Blacklist','nospamnx'); ?></b></td>
								</tr>
								<tr>
									<td width="50%" valign="top"><?php echo __('The local Blacklist is comparable to the WordPress Blacklist. However, the local Blacklist enables you to block comments containing certain values, instead of putting them in moderation queue. Thus, the local blacklist only makes sense when using NoSpamNX in blocking mode. The local Blacklist checks the given values against the ip address, the author, the E-Mail Address, the comment and the URL field of a comment. If a pattern matches, the comment will be blocked. Please use one value per line. The local Blacklist is case-insensitive.','nospamnx'); ?></td>
									<td width="50%" valign="top"><?php echo __('The global Blacklist gives you the possibility to use one Blacklist for multiple WordPress Blogs. You need to setup a place where you store your Blacklist (e.g. Webspace, Dropbox, etc. - but HTTP only) and put it into the Field "Update URL". How you Built up your Blacklist (e.g. PHP-Script with Database, simple Textfile, etc.) is up to, but you need to make sure, your Update URL returns one value per line seperated by "\n". Put the Update URL in all your Blogs where you want your Blacklist, and setup the update rotation according to your needs. The global Blacklist will be activated by adding an Update URL. The global Blacklist is case-insensitive.','nospamnx'); ?>
								</tr>
								<tr>
									<td width="50%" valign="top"><textarea name="blacklist" class="large-text code" cols="50" rows="10"><?php echo $this->nospamnx_blacklist; ?></textarea></td>
									<td width="50%" valign="top"><textarea name="blacklist_global" readonly class="large-text code" cols="50" rows="10"><?php echo $this->nospamnx_blacklist_global; ?></textarea>
									<br />
									<?php
										if (empty($this->nospamnx_blacklist_global_lu)) {
											echo __('Last update','nospamnx').": -";
										} else {
											echo __('Last update','nospamnx').": ".date_i18n("M j, Y @ G:i", $this->nospamnx_blacklist_global_lu, true);
										}
									?>
									</td>
								</tr>
								<tr>
									<td width="50%">&nbsp;</td>
									<td width="50%"><b><?php echo __('Update URL (e.g. http://www.mydomain.com/myblacklist.txt)','nospamnx'); ?></b><br /><input type="text" name="blacklist_global_url" value="<?php echo $this->nospamnx_blacklist_global_url; ?>" class="large-text code" /></td>
								</tr>
								<tr>
									<td width="50%">&nbsp;</td>
									<td width="50%"><b><?php echo __('Update every','nospamnx'); ?>&nbsp;<input type="text" name="blacklist_global_update" value="<?php echo $this->nospamnx_blacklist_global_update; ?>" size="5"/>&nbsp;<?php echo __('minutes.','nospamnx'); ?></b></td>
								</tr>
							</table>
							<input type="hidden" value="1" name="update_blacklist">
							<p><input name="submit" class='button-primary' value="<?php echo __('Save','nospamnx'); ?>" type="submit" /></p>
							</form>
						</div>
					</div>
				</div>
			</div>

			<?php
		}

		function verifyNonce($nonce) {
			if (!wp_verify_nonce($nonce, 'nospamnx-nonce')) {
				wp_die(__('Security-Check failed.','nospamnx'), '', array('response' => 403));
			}

			return true;
		}

		function activate() {
	    	if (!get_option('nospamnx')) {
				$options = array(
					'nospamnx_names' 					=> $this->generateNames(),
					'nospamnx_count'					=> 0,
					'nospamnx_operate'					=> 'mark',
					'nospamnx_blacklist_part'			=> 1,
					'nospamnx_blacklist_global_url'		=> '',
					'nospamnx_blacklist_global_update'	=> '',
					'nospamnx_blacklist_global_lu'		=> 0,
					'nospamnx_activated'				=> time(),
					'nospamnx_commentid'				=> $this->generateRandomString(),
					'nospamnx_salt'						=> $this->generateRandomString()
				);
				add_option('nospamnx', $options);
	    	} else {
				$options = get_option('nospamnx');
				$options['nospamnx_names'] = $this->generateNames();
				$options['nospamnx_commentid'] = $this->generateRandomString();
				$options['nospamnx_salt'] = $this->generateRandomString();
				if (!array_key_exists('nospamnx_count',$options) || empty($options['nospamnx_count'])) {
					$options['nospamnx_count'] = 0;
				}
				if (!array_key_exists('nospamnx_operate',$options) || empty($options['nospamnx_operate'])) {
					$options['nospamnx_operate'] = 'mark';
				}
				if (!array_key_exists('nospamnx_blacklist_global_url',$options)) {
					$options['nospamnx_blacklist_global_url'] = '';
				}
				if (!array_key_exists('nospmanx_blacklist_global_update',$options)) {
					$options['nospmanx_blacklist_global_update'] = '';
				}
				if (!array_key_exists('nospamnx_blacklist_global_lu',$options) || empty($options['nospamnx_blacklist_global_lu'])) {
					$options['nospamnx_blacklist_global_lu'] = 0;
				}
				if (!array_key_exists('nospamnx_blacklist_part',$options) || empty($options['nospamnx_blacklist_part'])) {
					$options['nospamnx_blacklist_part'] = 1;
				}
				if (!array_key_exists('nospamnx_activated',$options) || empty($options['nospamnx_activated'])) {
					$options['nospamnx_activated'] = time();
				}
				update_option('nospamnx', $options);
			}
			if (!get_option('nospamnx-blacklist-global')) { add_option('nospamnx-blacklist-global', ''); }
			if (!get_option('nospamnx-blacklist')) { add_option('nospamnx-blacklist', ''); }
		}

		static function uninstall() {
			delete_option('nospamnx');
			delete_option('nospamnx-blacklist');
			delete_option('nospamnx-blacklist-global');
		}

		function getOptions() {
			$options = get_option('nospamnx');
			$this->nospamnx_names 					= $options['nospamnx_names'];
			$this->nospamnx_count					= $options['nospamnx_count'];
			$this->nospamnx_operate					= $options['nospamnx_operate'];
			$this->nospamnx_blacklist_part			= $options['nospamnx_blacklist_part'];
			$this->nospamnx_blacklist_global_url	= $options['nospamnx_blacklist_global_url'];
			$this->nospamnx_blacklist_global_update	= $options['nospamnx_blacklist_global_update'];
			$this->nospamnx_blacklist_global_lu		= $options['nospamnx_blacklist_global_lu'];
			$this->nospamnx_activated				= $options['nospamnx_activated'];
			$this->nospamnx_commentid				= $options['nospamnx_commentid'];
			$this->nospamnx_salt					= $options['nospamnx_salt'];
			$this->nospamnx_blacklist_global		= get_option('nospamnx-blacklist-global');
			$this->nospamnx_blacklist				= get_option('nospamnx-blacklist');
		}

		function setOptions() {
			$options = array(
				'nospamnx_names'					=> $this->nospamnx_names,
				'nospamnx_count'					=> $this->nospamnx_count,
				'nospamnx_operate'					=> $this->nospamnx_operate,
				'nospamnx_blacklist_part'			=> $this->nospamnx_blacklist_part,
				'nospamnx_blacklist_global_url'		=> $this->nospamnx_blacklist_global_url,
				'nospamnx_blacklist_global_update'	=> $this->nospamnx_blacklist_global_update,
				'nospamnx_blacklist_global_lu'		=> $this->nospamnx_blacklist_global_lu,
				'nospamnx_activated'				=> $this->nospamnx_activated,
				'nospamnx_commentid'				=> $this->nospamnx_commentid,
				'nospamnx_salt'						=> $this->nospamnx_salt

			);
			update_option('nospamnx-blacklist', $this->nospamnx_blacklist);
		    update_option('nospamnx', $options);
		}

		function nospamnxStats() {
			$this->displayStats(true);
		}

		function getStatsPerDay() {
			$secs = time() - $this->nospamnx_activated;
			$days = ($secs / (24*3600));
			($days <= 1) ? $days = 1 : $days = floor($days);

			return ceil($this->nospamnx_count / $days);
		}

		function loadGlobalBlacklist() {
			$time = time();

			if (!function_exists('curl_init') || empty($this->nospamnx_blacklist_global_url)) { return; }
			if ((($time - $this->nospamnx_blacklist_global_lu)) < ($this->nospamnx_blacklist_global_update * 60)) { return; }

			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,$this->nospamnx_blacklist_global_url);
			curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,NXCURLTO);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$buffer = curl_exec($curl);

			if (curl_errno($curl) != 0) {
				curl_close($curl);
			} else {
				curl_close($curl);

				update_option('nospamnx-blacklist-global', $this->sortBlacklist($buffer));
				$this->nospamnx_blacklist_global = $blacklist;
				$this->nospamnx_blacklist_global_lu = $time;
				$this->setOptions();
			}
		}

		function sortBlacklist($blacklist) {
			$sortedBlacklist = explode("\n", $blacklist);
			natcasesort($sortedBlacklist);
			return implode("\n", $sortedBlacklist);
		}

		function displayStats($dashboard=false) {
			if (function_exists('_n')) {
				if ($dashboard) { echo "<p>"; }
				if ($this->nospamnx_count <= 0) {
					echo __("NoSpamNX has stopped no birdbrained Spambots yet.", 'nospamnx');
				} else {
					printf(_n(
							"Since %s %s has stopped %s birdbrained Spambot (approx. %s per Day).",
							"Since %s %s has stopped %s birdbrained Spambots (approx. %s per Day).",
							$this->nospamnx_count, 'nospamnx'),
							date_i18n(get_option('date_format'), $this->nospamnx_activated),
							'<a href="http://www.svenkubiak.de/nospamnx">NoSpamNX</a>',
							$this->nospamnx_count,
							$this->getStatsPerDay()
					);
				}
				if ($dashboard) { echo "</p>"; }
			}
		}
	}
	$nospamnx = new NoSpamNX();
}
?>

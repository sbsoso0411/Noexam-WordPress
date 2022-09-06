<?php
// Function called at the installation of the plugin
function IDG_Install() {

}
// Function called at the uninstallation of the plugin
function IDG_Uninstall() {

}

//In Depth Articles Generator
add_action('add_meta_boxes','idg_meta_box');
function idg_meta_box(){
	$args = array('capability_type' => 'post','objects');
	$post_types = get_post_types($args); 
	add_meta_box('idg-meta-box', 'Virante In-Depth Articles Options', 'idg_meta_box_function', $post_types);
}

add_action('save_post','save_idg_metaboxe');
function save_idg_metaboxe($post_ID){
  if(isset($_POST['idg_description'])){
	update_post_meta($post_ID,'idg_headline', $_POST['idg_headline']);
	update_post_meta($post_ID,'idg_alternativeHeadline', $_POST['idg_alternativeHeadline']);
	update_post_meta($post_ID,'idg_description', $_POST['idg_description']);
  }
 }
function idg_meta_box_function($post){
?>
<table>
	<tr>
		<td>Headline</td>
		<td><input type='text' name='idg_headline' size="80" value='<?PHP echo get_idg_option("idg_headline",$post->ID);  ?>' /></td>
	</tr>
	
	<tr>
		<td>Alternative Headline</td>
		<td><input type='text' name='idg_alternativeHeadline' size="80" value='<?PHP echo get_idg_option("idg_alternativeHeadline",$post->ID);  ?>' /></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><textarea name='idg_description' cols="82"><?PHP echo get_idg_option("idg_description",$post->ID);  ?></textarea></td>
	</tr>
</table>
<?PHP 
}

function get_idg_option($meta,$post_id){
	$meta_value=get_post_meta($post_id,$meta,true);
	if(strlen($meta_value)==0){
		return get_option($meta);
	}else{
		return $meta_value;
	}
}

add_action('admin_menu', 'idg_create_menu');

function idg_create_menu() {
	add_menu_page('IDG Settings', 'IDG Settings', 'administrator', __FILE__, 'idg_settings_page');
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	register_setting( 'idg-settings-group', 'idg_headline' );
	register_setting( 'idg-settings-group', 'idg_alternativeheadline' );
	register_setting( 'idg-settings-group', 'idg_description' );
}

function idg_settings_page() {
?>
<div class="wrap">
<h2>Virante In-Depth Articles</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'idg-settings-group' );
	?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Default Headline</th>
        <td><input type="text" name="idg_headline" size="80" value="<?php echo get_option('idg_headline'); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Default Alternative Headline</th>
        <td><input type="text" name="idg_alternativeheadline" size="80" value="<?php echo get_option('idg_alternativeheadline'); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Default description</th>
        <td>
			<textarea name='idg_description'  cols="82" ><?php echo get_option('idg_description'); ?></textarea>
		
		</td>
	
        </tr>
    </table>
	<p class='submit'> <?php submit_button(); ?>
		
	</p>

</form>
</div>
<?php } 
?>
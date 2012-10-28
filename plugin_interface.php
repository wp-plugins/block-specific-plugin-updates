<?php
// create custom plugin settings menu

if (!function_exists('get_plugins')){
	require_once (ABSPATH."wp-admin/includes/plugin.php");
}

if ($_POST['submit-bpu']){
	$blocked_plugins = @join('###',$_POST['block_plugin_updates']);
	update_option('bpu_update_blocked_plugins', $blocked_plugins);
	delete_option('_site_transient_update_plugins');	
	$updated = 'yes';
}

add_action('admin_menu', 'bpu_create_menu');

function bpu_create_menu() {
	add_options_page('Block Plugin Update', 'Block Plugin Update', 'administrator', __FILE__, 'bpu_settings_page');
	add_action('admin_init', 'register_bpusettings');
}


function register_bpusettings() {
	register_setting('bpu-settings-group', 'bpu_update_blocked_plugins');
}

function bpu_settings_page() {
	global $updated;
	$bpu_update_blocked_plugins 		= get_option('bpu_update_blocked_plugins');
	$bpu_update_blocked_plugins_array	= explode('###',$bpu_update_blocked_plugins);
	$plugins = get_plugins ();
?>
<style>input.space-right{margin-right:30px;}</style>
<div class="support_education wrap" style="width:250px; line-height:2.0; position:fixed; right:0;">
<h3>Educate a Child</h3>
We are sponsoring education for poor children. Donate and help us raise fund for them. For more details click <a href="http://dineshkarki.com.np/educate-child" target="_blank">here</a>
<br /><br />
<div align="center">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GNJJ22PDAAX48">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div>

<div class="wrap">
<h2>Block Specific Plugin Update</h2>

<?php if ($updated){ ?>
	<br />
<div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Settings saved.</strong></p></div>
<?php } ?>
    
    <h3>Tick the plugin you want disable for updates</h3>
    <form method="post" action="">
	<table class="form-table">
        <?php 
		if (!empty($plugins)){
		foreach ($plugins as $plugin_key_name=>$plugin): ?>
        <tr valign="top">
        <td scope="row">
        <input type="checkbox" class="space-right" name="block_plugin_updates[]" <?php echo in_array($plugin_key_name,$bpu_update_blocked_plugins_array)?'checked="checked"':''; ?> value="<?php echo $plugin_key_name; ?>" /><?php echo $plugin['Name']; ?></td>
        </tr>
        <?php endforeach; 
		} else { ?>
        <tr><td>No Plugin Found</td></tr>
        <?php } ?>
    </table>
    
    <p>
    <strong>Read Me Please</strong>
    <br />
Please logout and login to get the proper effect. We have cleared all the update information stored. So you need to logout and login again to get the updates of the plugin you want. Required only in some cases.</p>
    <p class="submit">
    <input type="submit" name="submit-bpu" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
</div>
<?php } ?>
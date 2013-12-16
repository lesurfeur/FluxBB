<?php

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);

// Load this plugins language file
if (file_exists(PUN_ROOT.'/lang/'.$pun_user['language'].'/admin_image_group.php'))
require PUN_ROOT.'/lang/'.$pun_user['language'].'/admin_image_group.php';
else
require PUN_ROOT.'/lang/English/admin_image_group.php';

// Did someone just hit "Submit"
if (isset($_POST['form_sent']))
{
	confirm_referrer('admin_loader.php?plugin=AP_Image_Group.php');
	
	$form = array_map('trim', $_POST['form']);
	
	// Clean ig_dir
	$form['ig_dir'] = str_replace("\0", '', $form['ig_dir']);

	// Make sure ig_dir doesn't end with a slash
	if (substr($form['ig_dir'], -1) == '/')
		$form['ig_dir'] = substr($form['ig_dir'], 0, -1);
	
	$form['ig_pos'] = intval($form['ig_pos']);
	$form['ig_width'] = intval($form['ig_width']);
	$form['ig_height'] = intval($form['ig_height']);
	$form['ig_size'] = intval($form['ig_size']);
	
	while (list($key, $input) = @each($form))
	{
		// Only update values that have changed
		if (array_key_exists($key, $pun_config) && $pun_config[$key] != $input)
		{
			if ($input != '' || is_int($input))
				$value = '\''.$db->escape($input).'\'';
			else
				$value = 'NULL';

			$db->query('UPDATE '.$db->prefix.'config SET conf_value='.$value.' WHERE conf_name=\''.$db->escape($key).'\'') or error('Unable to update board config', __FILE__, __LINE__, $db->error());
		}
	}
	$d = dir(PUN_ROOT.'cache');
	while (($entry = $d->read()) !== false)
	{
		if (substr($entry, strlen($entry)-4) == '.php')
			@unlink(PUN_ROOT.'cache/'.$entry);
	}
	require_once PUN_ROOT.'include/cache.php';
	generate_quickjump_cache();

	redirect('admin_loader.php?plugin=AP_Image_Group.php', $lang_admin_image_group['success option']);
}
else
{
	// Display the admin navigation menu
	generate_admin_menu($plugin);

?>
	<div class="plugin blockform">
		<h2><span><?php echo $lang_admin_image_group['plugin name'] ?></span></h2>
		<div class="box">
			<div class="inbox">
				<p><?php echo $lang_admin_image_group['plugin desc'] ?></p>
			</div>
		</div>
		<h2 class="block2"><span><?php echo $lang_admin_image_group['options'] ?></span></h2>
		<div class="box">
		<form id="post" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang_admin_image_group['settings'] ?></legend>
						<div class="infldset">
						<table class="aligntop" cellspacing="0">
							<tr>
								<th scope="row"><?php echo $lang_admin_image_group['display'] ?></th>
								<td>
									<input type="radio" name="form[ig_pos]" value="1"<?php if ($pun_config['ig_pos'] == '1') echo ' checked="checked"' ?> tabindex="1" />&nbsp;<strong><?php echo $lang_admin_image_group['display up'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[ig_pos]" value="2"<?php if ($pun_config['ig_pos'] == '2') echo ' checked="checked"' ?> tabindex="2" />&nbsp;<strong><?php echo $lang_admin_image_group['display down'] ?></strong>
									<span><?php echo $lang_admin_image_group['display choice'] ?></span>
								</td>
							</tr>
							<tr>
									<th scope="row"><?php echo $lang_admin_image_group['directory'] ?></th>
									<td>
										<input type="text" name="form[ig_dir]" size="35" maxlength="50" value="<?php echo pun_htmlspecialchars($pun_config['ig_dir']) ?>" tabindex="3" />
										<span><?php echo $lang_admin_image_group['directory folder'] ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang_admin_image_group['width'] ?></th>
									<td>
										<input type="text" name="form[ig_width]" size="5" maxlength="5" value="<?php echo $pun_config['ig_width'] ?>" tabindex="4" />
										<span><?php echo $lang_admin_image_group['width comment'] ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang_admin_image_group['height'] ?></th>
									<td>
										<input type="text" name="form[ig_height]" size="5" maxlength="5" value="<?php echo $pun_config['ig_height'] ?>" tabindex="5" />
										<span><?php echo $lang_admin_image_group['height comment'] ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo $lang_admin_image_group['size'] ?></th>
									<td>
										<input type="text" name="form[ig_size]" size="6" maxlength="6" value="<?php echo $pun_config['ig_size'] ?>" tabindex="6" />
										<span><?php echo $lang_admin_image_group['size comment'] ?></span>
									</td>
								</tr>
						</table>
						<div><p><input type="submit" name="form_sent" value="<?php echo $lang_admin_image_group['save'] ?>" tabindex="7" /></p></div>
						</div>
					</fieldset>
				</div>
			</form>
		</div> 		
	</div>
<?php

}


// Note that the script just ends here. The footer will be included by admin_loader.php.

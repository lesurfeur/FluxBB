<?php
/***********************************************************************/

// Some info about your mod.
$mod_title      = 'Image Group';
$mod_version    = '1.2.1';
$release_date   = '2010-12-08';  // 7 décembre 2010
$author         = 'PascL for PunBB 1.2 - Adaptation by Spiky for FluxBB 1.4 / 1.5';
$author_email   = 'lesurfeur72@gmail.com';

// Versions of FluxBB this mod was created for. A warning will be displayed, if versions do not match
$fluxbb_versions= array('1.5.1');

// Set this to false if you haven't implemented the restore function (see below)
$mod_restore	= true;


// This following function will be called when the user presses the "Install" button
function install()
{
	global $db, $db_type, $pun_config;
	
	$db->query('ALTER TABLE '.$db->prefix.'groups ADD g_use_img TINYINT(1) NOT NULL DEFAULT 0 AFTER g_user_title') or error('Unable to add column g_use_img to table groups', __FILE__, __LINE__, $db->error());
	$db->query('INSERT INTO '.$db->prefix.'config (conf_name, conf_value) VALUES (\'ig_pos\', \'1\'),(\'ig_dir\', \'img/image_group\'),(\'ig_width\', \'60\'),(\'ig_height\', \'30\'),(\'ig_size\', \'10240\')') or error('Unable to insert data to table config', __FILE__, __LINE__, $db->error());

// Regenerate the config cache
	if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
	  require PUN_ROOT.'include/cache.php';

	generate_config_cache();

}

// This following function will be called when the user presses the "Restore" button (only if $mod_uninstall is true (see above))
function restore()
{
	global $db, $db_type, $pun_config;
	
	$errors = array();
	
	$db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name = \'ig_pos\' LIMIT 1') or error('Unable to delete data 1 from table config', __FILE__, __LINE__, $db->error());
	$db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name = \'ig_dir\' LIMIT 1') or error('Unable to delete data 2 from table config', __FILE__, __LINE__, $db->error());
	$db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name = \'ig_width\' LIMIT 1') or error('Unable to delete data 3 from table config', __FILE__, __LINE__, $db->error());
	$db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name = \'ig_height\' LIMIT 1') or error('Unable to delete data 4 from table config', __FILE__, __LINE__, $db->error());
	$db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name = \'ig_size\' LIMIT 1') or error('Unable to delete data 5 from table config', __FILE__, __LINE__, $db->error());
	$db->query('ALTER TABLE '.$db->prefix.'groups DROP g_use_img') or error('Unable to delete column g_url_img from table groups', __FILE__, __LINE__, $db->error());

// Regenerate the config cache
	if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
	  require PUN_ROOT.'include/cache.php';

	generate_config_cache();
}

/***********************************************************************/

// DO NOT EDIT ANYTHING BELOW THIS LINE!


// Circumvent maintenance mode
define('PUN_TURN_OFF_MAINT', 1);
define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';

// We want the complete error message if the script fails
if (!defined('PUN_DEBUG'))
	define('PUN_DEBUG', 1);

// Make sure we are running a Fluxbb version that this mod works with
if(!in_array($pun_config['o_cur_version'], $fluxbb_versions))
	exit('You are running a version of Fluxbb ('.$pun_config['o_cur_version'].') that this mod does not support. This mod supports Fluxbb versions: '.implode(', ', $fluxbb_versions));

$style = (isset($cur_user)) ? $cur_user['style'] : $pun_config['o_default_style'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $mod_title ?> installation</title>
<link rel="stylesheet" type="text/css" href="style/<?php echo $pun_config['o_default_style'].'.css' ?>" />
</head>
<body>

<div id="punwrap">
<div id="puninstall" class="pun" style="margin: 10% 20% auto 20%">

<?php

if (isset($_POST['form_sent']))
{
	if (isset($_POST['install']))
	{
		// Run the install function (defined above)
		install();

?>
<div class="block">
	<h2><span>Installation successful</span></h2>
	<div class="box">
		<div class="inbox">
			<p>Your database has been successfully prepared for <?php echo pun_htmlspecialchars($mod_title) ?>. See readme.txt for further instructions.</p>
		</div>
	</div>
</div>
<?php

	}
	else
	{
		// Run the restore function (defined above)
		restore();

?>
<div class="block">
	<h2><span>Restore successful</span></h2>
	<div class="box">
		<div class="inbox">
			<p>Your database has been successfully restored.</p>
		</div>
	</div>
</div>
<?php

	}
}
else
{

?>
<div class="blockform">
	<h2><span>Mod installation</span></h2>
	<div class="box">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?foo=bar">
			<div><input type="hidden" name="form_sent" value="1" /></div>
			<div class="inform">
				<p>This script will update your database to work with the following modification:</p>
				<p><strong>Mod title:</strong> <?php echo pun_htmlspecialchars($mod_title).' '.$mod_version ?></p>
				<p><strong>Author:</strong> <?php echo pun_htmlspecialchars($author) ?> (<a href="mailto:<?php echo pun_htmlspecialchars($author_email) ?>"><?php echo pun_htmlspecialchars($author_email) ?></a>)</p>
				<p><strong>Disclaimer:</strong> Mods are not officially supported by Fluxbb. Mods generally can't be uninstalled without running SQL queries manually against the database. Make backups of all data you deem necessary before installing.</p>
<?php if ($mod_restore): ?>				<p>If you've previously installed this mod and would like to uninstall it, you can click the restore button below to restore the database.</p>
<?php endif; ?>			</div>
			<p class="buttons"><input type="submit" name="install" value="Install" /><?php if ($mod_restore): ?><input type="submit" name="restore" value="Restore" /><?php endif; ?></p>
		</form>
	</div>
</div>
<?php

}

?>

</div>
</div>

</body>
</html>

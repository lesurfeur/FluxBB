##
##
##          Mod title:  Image Group
##
##        Mod version:  1.2.1
##    Works on FluxBB:  1.5.1
##       Release date:  2010-12-08 for FluxBB 1.4.2
##             Update:  2011-09-23 for FluxBB 1.4.6
##                      2012-10-19 for FluxBB 1.5.0
##						2012-11-14 for FluxBB 1.5.1
##
##             Author:  Spiky (lesurfeur72@gmail.com) - Version 1.0 : adaptation for FluxBB 1.4
##                  	PascL (kokoala2k3@free.fr)    - Version 1.0 for PunBB 1.2 - Written on 2007-09-08.
##
##        Description:  Allows to attribute an image to a group.
##					   (French: Permet d'attribuer une image à un groupe.)
##
##	   Affected files:  admin_groups.php
##                      viewtopic.php
##
##         Affects BD:  Yes (groups and config)
##
##         Disclaimer:  Please note that "mods" are not officially supported by
##                      FluxBB. Installation of this modification is done at
##                      your own risk. Backup your forum database and any and
##                      all applicable files before proceeding.
##


#
#---------[ 1. UPLOAD ]----------------------------------------------------
#---------[ 1. TELECHARGER LES FICHIERS ]----------------------------------
#

/files/install_mod.php					to	/your_forum_folder/
/files/plugins/AP_Image_Group			to	/your_forum_folder/plugins/
/files/lang/English/image_group.php		to	/your_forum_folder/lang/English/
/files/lang/French/image_group.php		to	/your_forum_folder/lang/French/
/files/img/image_group					to	/your_forum_folder/img/


#
#---------[ 2. RUN ]-------------------------------------------------------
#---------[ 2. LANCER ]----------------------------------------------------
#

install_mod.php


#
#---------[ 3. DELETE ]----------------------------------------------------
#---------[ 3. SUPPRIMER ]-------------------------------------------------
#

install_mod.php


#
#---------[ 4. OPEN ]------------------------------------------------------
#---------[ 4. OUVRIR ]----------------------------------------------------
#

admin_groups.php


#
#---------[ 5. FIND (ligne 24) ]------------------------------------------------------
#---------[ 5. TROUVER ]---------------------------------------------------
#

// Add/edit a group (stage 1)
if (isset($_POST['add_group']) || isset($_GET['edit_group']))

#
#---------[ 6. BEFORE, ADD ]-----------------------------------------------
#---------[ 6. AJOUTER AVANT ]---------------------------------------------
#

// [Modif] Mod Image Group
if (file_exists(PUN_ROOT.'/lang/'.$pun_user['language'].'/admin_image_group.php'))
require PUN_ROOT.'lang/'.$pun_user['language'] . '/admin_image_group.php';
    else
require PUN_ROOT.'/lang/English/admin_image_group.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action == 'upload_image' || $action == 'upload_image2')
{
	$result = $db->query('SELECT 1 FROM '.$db->prefix.'groups WHERE g_id='.$id) or error('Unable to fetch user group info', __FILE__, __LINE__, $db->error());
	if (!$db->num_rows($result))
		message($lang_common['Bad request']);
	
	if (isset($_POST['form_sent']))
	{
		if (!isset($_FILES['req_file']))
			message($lang_admin_image_group['No file']);
			
		$uploaded_file = $_FILES['req_file'];

		// Make sure the upload went smooth
		if (isset($uploaded_file['error']))
		{
			switch ($uploaded_file['error'])
			{
				case 1:	// UPLOAD_ERR_INI_SIZE
				case 2:	// UPLOAD_ERR_FORM_SIZE
					message($lang_admin_image_group['Too large ini']);
					break;

				case 3:	// UPLOAD_ERR_PARTIAL
					message($lang_admin_image_group['Partial upload']);
					break;

				case 4:	// UPLOAD_ERR_NO_FILE
					message($lang_admin_image_group['No file']);
					break;

				case 6:	// UPLOAD_ERR_NO_TMP_DIR
					message($lang_admin_image_group['No tmp directory']);
					break;

				default:
					// No error occured, but was something actually uploaded?
					if ($uploaded_file['size'] == 0)
						message($lang_admin_image_group['No file']);
					break;
			}
		}

		if (is_uploaded_file($uploaded_file['tmp_name']))
		{
			$allowed_types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
			if (!in_array($uploaded_file['type'], $allowed_types))
				message($lang_admin_image_group['Bad type']);

			// Make sure the file isn't too big
			if ($uploaded_file['size'] > $pun_config['ig_size'])
				message($lang_admin_image_group['Too large'].' '.$pun_config['ig_size'].' '.$lang_admin_image_group['bytes'].'.');

			// Determine type
			$extensions = null;
			if ($uploaded_file['type'] == 'image/gif')
				$extensions = array('.gif', '.jpg', '.png');
			else if ($uploaded_file['type'] == 'image/jpeg' || $uploaded_file['type'] == 'image/pjpeg')
				$extensions = array('.jpg', '.gif', '.png');
			else
				$extensions = array('.png', '.gif', '.jpg');

			// Move the file to the image directory. We do this before checking the width/height to circumvent open_basedir restrictions.
			if (!@move_uploaded_file($uploaded_file['tmp_name'], $pun_config['ig_dir'].'/'.$id.'.tmp'))
				message($lang_admin_image_group['Move failed'].' <a href="mailto:'.$pun_config['o_admin_email'].'">'.$pun_config['o_admin_email'].'</a>.');

			// Now check the width/height
			list($width, $height, $type,) = getimagesize($pun_config['ig_dir'].'/'.$id.'.tmp');
			if (empty($width) || empty($height) || $width > $pun_config['ig_width'] || $height > $pun_config['ig_height'])
			{
				@unlink($pun_config['ig_dir'].'/'.$id.'.tmp');
				message($lang_admin_image_group['Too wide or high'].' '.$pun_config['ig_width'].'x'.$pun_config['ig_height'].' '.$lang_admin_image_group['pixels'].'.');
			}
			else if ($type == 1 && $uploaded_file['type'] != 'image/gif')	// Prevent dodgy uploads
			{
				@unlink($pun_config['ig_dir'].'/'.$id.'.tmp');
				message($lang_admin_image_group['Bad type']);
			}			

			// Delete any old images and put the new one in place
			@unlink($pun_config['ig_dir'].'/'.$id.$extensions[0]);
			@unlink($pun_config['ig_dir'].'/'.$id.$extensions[1]);
			@unlink($pun_config['ig_dir'].'/'.$id.$extensions[2]);
			@rename($pun_config['ig_dir'].'/'.$id.'.tmp', $pun_config['ig_dir'].'/'.$id.$extensions[0]);
			@chmod($pun_config['ig_dir'].'/'.$id.$extensions[0], 0644);
		}
		else
			message($lang_admin_image_group['Unknown failure']);

		// Enable use_image (seems sane since the user just uploaded an image)
		$db->query('UPDATE '.$db->prefix.'groups SET g_use_img=1 WHERE g_id='.$id) or error('Unable to update image state', __FILE__, __LINE__, $db->error());

		redirect('admin_groups.php', $lang_admin_image_group['Image upload redirect']);
	}

	$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang_admin_common['Admin'], $lang_admin_common['User groups']);
	/* $page_title = pun_htmlspecialchars($pun_config['o_board_title']).' / Admin / Groupes'; */
	$required_fields = array('req_file' => $lang_admin_image_group['File']);
	$focus_element = array('upload_image', 'req_file');
	define('PUN_ACTIVE_PAGE', 'admin');
	require PUN_ROOT.'header.php';
	generate_admin_menu('groups');

?>
<div class="blockform">
	<h2><span><?php echo $lang_admin_image_group['Upload image'] ?></span></h2>
	<div class="box">
		<form id="upload_image" method="post" enctype="multipart/form-data" action="admin_groups.php?action=upload_image2&amp;id=<?php echo $id ?>" onsubmit="return process_form(this)">
			<div class="inform">
				<fieldset>
					<legend><?php echo $lang_admin_image_group['Upload image legend'] ?></legend>
					<div class="infldset">
						<input type="hidden" name="form_sent" value="1" />
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $pun_config['ig_size'] ?>" />
						<label><strong><?php echo $lang_admin_image_group['File'] ?></strong><br /><input name="req_file" type="file" size="40" /><br /></label>
						<p><?php echo $lang_admin_image_group['Image desc'].' '.$pun_config['ig_width'].' x '.$pun_config['ig_height'].' '.$lang_admin_image_group['pixels'].' '.$lang_common['and'].' '.$pun_config['ig_size'].' '.$lang_admin_image_group['bytes'].' ('.ceil($pun_config['ig_size'] / 1024) ?> KB).</p>
					</div>
				</fieldset>
			</div>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="upload" value="<?php echo $lang_admin_image_group['Upload'] ?>" />&nbsp;&nbsp;<a href="javascript:history.go(-1)"><?php echo $lang_common['Go back'] ?></a></p>
		</form>
	</div>
</div>
<div class="clearer"></div>
</div>
<?php

	require PUN_ROOT.'footer.php';
}

else if ($action == 'delete_image')
{

	confirm_referrer('admin_groups.php');

	@unlink($pun_config['ig_dir'].'/'.$id.'.jpg');
	@unlink($pun_config['ig_dir'].'/'.$id.'.png');
	@unlink($pun_config['ig_dir'].'/'.$id.'.gif');

	// Disable use_image
	$db->query('UPDATE '.$db->prefix.'groups SET g_use_img=0 WHERE g_id='.$id) or error('Unable to update image state', __FILE__, __LINE__, $db->error());

	redirect('admin_groups.php', $lang_admin_image_group['Image deleted redirect']);
}
else
{
// [Modif] End Mod Image Group

#
#---------[ 7. FIND ]-----------------------------------------------------
#---------[ 7. TROUVER ]--------------------------------------------------
#

<?php if ($group['g_moderator'] == '1' ): ?>							<p class="warntext"><?php echo $lang_admin_groups['Moderator info'] ?></p>
<?php endif; ?>						</div>
					</fieldset>
				</div>

#
#---------[ 8. AFTER, ADD ]-----------------------------------------------
#---------[ 8. AJOUTER APRES ]--------------------------------------------
#

<!-- [Modif] Mod Image Group -->
				<?php
		if ($mode == 'edit')
		{
						$image_field = '<a href="admin_groups.php?action=upload_image&amp;id='.$group_id.'">'.$lang_admin_image_group['Change image'].'</a>';
						if ($img_size = @getimagesize($pun_config['ig_dir'].'/'.$group_id.'.gif'))
							$image_format = 'gif';
						else if ($img_size = @getimagesize($pun_config['ig_dir'].'/'.$group_id.'.jpg'))
							$image_format = 'jpg';
						else if ($img_size = @getimagesize($pun_config['ig_dir'].'/'.$group_id.'.png'))
							$image_format = 'png';
						else
							$image_field = '<a href="admin_groups.php?action=upload_image&amp;id='.$group_id.'">'.$lang_admin_image_group['Upload image'].'</a>';
				
						// Display the delete image link?
						if ($img_size)
							$image_field .= '&nbsp;&nbsp;&nbsp;<a href="admin_groups.php?action=delete_image&amp;id='.$group_id.'">'.$lang_admin_image_group['Delete image'].'</a>';
							
				?>
				<div class="inform">
					<fieldset id="profileavatar">
						<legend><?php echo $lang_admin_image_group['Image legend'] ?></legend>
						<div class="infldset">
<?php if (isset($image_format)): ?>					<p><img src="<?php echo $pun_config['ig_dir'].'/'.$group_id.'.'.$image_format ?>" <?php echo $img_size[3] ?> alt="" /></p>
<?php endif; ?>					<p><?php echo $lang_admin_image_group['Image info'] ?></p>
							<div class="rbox">
								<label><input type="checkbox" name="use_img" value="1"<?php if ($group['g_use_img'] == '1') echo ' checked="checked"' ?> /><?php echo $lang_admin_image_group['Use image'] ?><br /></label>
							</div>
							<p class="clearb"><?php echo $image_field ?></p>
						</div>
					</fieldset>
				</div>
			<?php
		}
		?>
<!-- [Modif] End Mod Image Group -->

#
#---------[ 9. FIND ]-----------------------------------------------------
#---------[ 9. TROUVER ]--------------------------------------------------
#

	$user_title = pun_trim($_POST['user_title']);

#
#---------[ 10. AFTER, ADD ]-----------------------------------------------
#---------[ 10. AJOUTER APRES ]--------------------------------------------
#

	$use_img = isset($_POST['use_img']) ? intval($_POST['use_img']) : '0'; // [Modif] Mod Image Group added

#
#---------[ 11. FIND ]-----------------------------------------------------
#---------[ 11. TROUVER ]--------------------------------------------------
#	

		$db->query('INSERT INTO '.$db->prefix.'groups (g_title, g_user_title, g_promote_min_posts, g_promote_next_group, g_moderator, g_mod_edit_users, g_mod_rename_users, g_mod_change_passwords, g_mod_ban_users, g_read_board, g_view_users, g_post_replies, g_post_topics, g_edit_posts, g_delete_posts, g_delete_topics, g_post_links, g_set_title, g_search, g_search_users, g_send_email, g_post_flood, g_search_flood, g_email_flood, g_report_flood) VALUES(\''.$db->escape($title).'\', '.$user_title.', '.$promote_min_posts.', '.$promote_next_group.', '.$moderator.', '.$mod_edit_users.', '.$mod_rename_users.', '.$mod_change_passwords.', '.$mod_ban_users.', '.$read_board.', '.$view_users.', '.$post_replies.', '.$post_topics.', '.$edit_posts.', '.$delete_posts.', '.$delete_topics.', '.$post_links.', '.$set_title.', '.$search.', '.$search_users.', '.$send_email.', '.$post_flood.', '.$search_flood.', '.$email_flood.', '.$report_flood.')') or error('Unable to add group', __FILE__, __LINE__, $db->error());

#
#---------[ 12. REPLACE WITH ]---------------------------------------------
#---------[ 12. REMPLACER PAR ]--------------------------------------------
#	

		// [Modif] Mod Image Group added in query
		$db->query('INSERT INTO '.$db->prefix.'groups (g_title, g_user_title, g_promote_min_posts, g_promote_next_group, g_moderator, g_mod_edit_users, g_mod_rename_users, g_mod_change_passwords, g_mod_ban_users, g_use_img, g_read_board, g_view_users, g_post_replies, g_post_topics, g_edit_posts, g_delete_posts, g_delete_topics, g_post_links, g_set_title, g_search, g_search_users, g_send_email, g_post_flood, g_search_flood, g_email_flood, g_report_flood) VALUES(\''.$db->escape($title).'\', '.$user_title.', '.$promote_min_posts.', '.$promote_next_group.', '.$moderator.', '.$mod_edit_users.', '.$mod_rename_users.', '.$mod_change_passwords.', '.$mod_ban_users.', '.$use_img.', '.$read_board.', '.$view_users.', '.$post_replies.', '.$post_topics.', '.$edit_posts.', '.$delete_posts.', '.$delete_topics.', '.$post_links.', '.$set_title.', '.$search.', '.$search_users.', '.$send_email.', '.$post_flood.', '.$search_flood.', '.$email_flood.', '.$report_flood.')') or error('Unable to add group', __FILE__, __LINE__, $db->error());

#
#---------[ 13. FIND ]-----------------------------------------------------
#---------[ 13. TROUVER ]--------------------------------------------------
#	

		$db->query('UPDATE '.$db->prefix.'groups SET g_title=\''.$db->escape($title).'\', g_user_title='.$user_title.', g_promote_min_posts='.$promote_min_posts.', g_promote_next_group='.$promote_next_group.', g_moderator='.$moderator.', g_mod_edit_users='.$mod_edit_users.', g_mod_rename_users='.$mod_rename_users.', g_mod_change_passwords='.$mod_change_passwords.', g_mod_ban_users='.$mod_ban_users.', g_read_board='.$read_board.', g_view_users='.$view_users.', g_post_replies='.$post_replies.', g_post_topics='.$post_topics.', g_edit_posts='.$edit_posts.', g_delete_posts='.$delete_posts.', g_delete_topics='.$delete_topics.', g_post_links='.$post_links.', g_set_title='.$set_title.', g_search='.$search.', g_search_users='.$search_users.', g_send_email='.$send_email.', g_post_flood='.$post_flood.', g_search_flood='.$search_flood.', g_email_flood='.$email_flood.', g_report_flood='.$report_flood.' WHERE g_id='.intval($_POST['group_id'])) or error('Unable to update group', __FILE__, __LINE__, $db->error());

#
#---------[ 14. REPLACE WITH ]---------------------------------------------
#---------[ 14. REMPLACER PAR ]--------------------------------------------
#

		// [Modif] Mod Image Group added in query
		$db->query('UPDATE '.$db->prefix.'groups SET g_title=\''.$db->escape($title).'\', g_user_title='.$user_title.', g_promote_min_posts='.$promote_min_posts.', g_promote_next_group='.$promote_next_group.', g_moderator='.$moderator.', g_mod_edit_users='.$mod_edit_users.', g_mod_rename_users='.$mod_rename_users.', g_mod_change_passwords='.$mod_change_passwords.', g_mod_ban_users='.$mod_ban_users.', g_use_img='.$use_img.', g_read_board='.$read_board.', g_view_users='.$view_users.', g_post_replies='.$post_replies.', g_post_topics='.$post_topics.', g_edit_posts='.$edit_posts.', g_delete_posts='.$delete_posts.', g_delete_topics='.$delete_topics.', g_post_links='.$post_links.', g_set_title='.$set_title.', g_search='.$search.', g_search_users='.$search_users.', g_send_email='.$send_email.', g_post_flood='.$post_flood.', g_search_flood='.$search_flood.', g_email_flood='.$email_flood.', g_report_flood='.$report_flood.' WHERE g_id='.intval($_POST['group_id'])) or error('Unable to update group', __FILE__, __LINE__, $db->error());

#
#---------[ 15. FIND ]-----------------------------------------------------
#---------[ 15. TROUVER ]--------------------------------------------------
#

			if (isset($_POST['del_group']))
			{
				$move_to_group = intval($_POST['move_to_group']);
				$db->query('UPDATE '.$db->prefix.'users SET group_id='.$move_to_group.' WHERE group_id='.$group_id) or error('Unable to move users into group', __FILE__, __LINE__, $db->error());
			}

#
#---------[ 16. AFTER, ADD ]-----------------------------------------------
#---------[ 16. AJOUTER APRES ]--------------------------------------------
#

			// Delete the image of group - [Modif] Mod Image Group added
			@unlink($pun_config['ig_dir'].'/'.$group_id.'.jpg');
			@unlink($pun_config['ig_dir'].'/'.$group_id.'.png');
			@unlink($pun_config['ig_dir'].'/'.$group_id.'.gif');
			// [Modif] End Mod Image Group

#
#---------[ 17. FIND (last line) ]-----------------------------------------
#---------[ 17. TROUVER (dernière ligne) ]---------------------------------
#	

require PUN_ROOT.'footer.php';

#
#---------[ 18. AFTER, ADD ]-----------------------------------------------
#---------[ 18. AJOUTER APRES ]--------------------------------------------
#	

} // [Modif] Mod Image Group added

#
#---------[ 19. OPEN ]-----------------------------------------------------
#---------[ 19. OUVRIR ]---------------------------------------------------
#

viewtopic.php

#
#---------[ 20. FIND (ligne 210) ]-----------------------------------------------------
#---------[ 20. TROUVER ]--------------------------------------------------
#

// Retrieve the posts (and their respective poster/online status)
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.signature, u.email_setting, u.num_posts, u.registered, u.admin_note, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, g.g_id, g.g_user_title, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.id IN ('.implode(',', $post_ids).') ORDER BY p.id', true) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());

#
#---------[ 21. REPLACE WITH ]---------------------------------------------
#---------[ 21. REMPLACER PAR ]--------------------------------------------
#

// Retrieve the posts (and their respective poster/online status) - [Modif] Mod Image Group added in query
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.signature, u.email_setting, u.num_posts, u.registered, u.admin_note, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, g.g_id, g.g_user_title, g.g_use_img, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.id IN ('.implode(',', $post_ids).') ORDER BY p.id'/* , true */) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());

#
#---------[ 22. FIND (ligne 281) ]-----------------------------------------------------
#---------[ 22. TROUVER ]--------------------------------------------------
#

			if ($cur_post['admin_note'] != '')
				$user_info[] = '<dd><span>'.$lang_topic['Note'].' <strong>'.pun_htmlspecialchars($cur_post['admin_note']).'</strong></span></dd>';
		}

#
#---------[ 23. AFTER, ADD ]-----------------------------------------------
#---------[ 23. AJOUTER APRES ]--------------------------------------------
#

		// [Modif] Mod Image Group added
		if ($cur_post['g_use_img'] == 1)
		{
			if ($img_size = @getimagesize($pun_config['ig_dir'].'/'.$cur_post['g_id'].'.gif'))
				$group_img = '<img src="'.$pun_config['ig_dir'].'/'.$cur_post['g_id'].'.gif" '.$img_size[3].' alt="'.$cur_post['g_user_title'].'" />';
			else if ($img_size = @getimagesize($pun_config['ig_dir'].'/'.$cur_post['g_id'].'.jpg'))
				$group_img = '<img src="'.$pun_config['ig_dir'].'/'.$cur_post['g_id'].'.jpg" '.$img_size[3].' alt="'.$cur_post['g_user_title'].'" />';
			else if ($img_size = @getimagesize($pun_config['ig_dir'].'/'.$cur_post['g_id'].'.png'))
				$group_img = '<img src="'.$pun_config['ig_dir'].'/'.$cur_post['g_id'].'.png" '.$img_size[3].' alt="'.$cur_post['g_user_title'].'" />';
		}
		else
			$group_img = '';
		// [Modif] End Mod Image Group

#
#---------[ 24. FIND ]-----------------------------------------------------
#---------[ 24. TROUVER ]--------------------------------------------------
#
			
						<dd class="usertitle"><strong><?php echo $user_title ?></strong></dd>
<?php if ($user_avatar != '') echo "\t\t\t\t\t\t".'<dd class="postavatar">'.$user_avatar.'</dd>'."\n"; ?>			

#
#---------[ 25. REPLACE WITH ]---------------------------------------------
#---------[ 25. REMPLACER PAR ]--------------------------------------------
#

						<?php // [Modif] Mod Image Group added
							if($pun_config['ig_pos']==1)
								if ($cur_post['g_use_img'] == 1)
									echo '<dd class="usertitle">'.$group_img.'</dd>'."\n";
								else
									echo '<dd class="usertitle"><strong>'.$user_title.'</strong></dd>'."\n";
						?>
<?php if ($user_avatar != '') echo "\t\t\t\t\t\t".'<dd class="postavatar">'.$user_avatar.'</dd>'."\n"; ?>
		<?php
			if($pun_config['ig_pos']==2)
				if ($cur_post['g_use_img'] == 1)
					echo '<dd class="usertitle">'.$group_img.'</dd>'."\n";
				else
					echo '<dd class="usertitle"><strong>'.$user_title.'</strong></dd>'."\n";
// [Modif] End Mod Image Group ?>

#
#---------[ 26. SAVE/UPLOAD ]----------------------------------------------
#---------[ 26. ENREGISTRER / ENVOYER SUR LE SERVEUR ]---------------------
#

admin_groups.php
viewtopic.php

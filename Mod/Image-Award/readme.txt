##
##
##        Mod title:  Image Award
##
##      Mod version:  1.0.4
##  Works on FluxBB:  1.5.1
##
##           Update:  2012-11-15 for FluxBB 1.5.1
##                    2012-05-17 for FluxBB 1.5.0
##			    	  2012-05-17 for FluxBB 1.4.8 / 1.4.9
##					  2011-09-16 for FluxBB 1.4.6 / 1.4.7
##
##     Release date:  2010-09-06 for FluxBB 1.4.2
##
##           Author:  Spiky aka lesurfeur (lesurfeur72@gmail.com) - Version 1.0.1 : adaptation for FluxBB 1.4
##                    Frank Hagstrom (frank.hagstrom+punbb@gmail.com) - Version 1.0.0 for PunBB 1.2 - Written on 2005-05-16
##
##      Description:  This mod will add the ability to assign image award for
##                    users. That is shown below their avatar.
##                    A plugin allows to add or remove the reward to members.
##                        
##					  French: Cette mod permet d'attribuer une récompense en image pour les membres. 
##                    Cette récompense sera visible au-dessous de leur avatar.
##                    Un plugin permet d'ajouter ou enlever la récompense aux membres
##
##   Affected files:  viewtopic.php
##
##       Affects DB:  Yes
##
##            Notes:  Well, having these things might be handy, a good way to
##                    show that a user has been warned for his actions, or just
##                    that the user is in some way special. The addition of new
##                    awards is very easy. Just create an image, save it with
##                    correct naming, upload, and then assign to the user.
##                    Description on how to name the files is in the admin
##                    plugin interface.
##                    The plugin part might be improved later, but typing in id
##                    I think is better than generating a list of users. As the
##                    userlist might grow to several thousands, and that's a
##                    hard list to find names in...
##                    
##                    Written by Spiky
##                    on: 2010-09-06 08:25
##
##       DISCLAIMER:  Please note that "mods" are not officially supported by
##                    FluxBB. Installation of this modification is done at your
##                    own risk. Backup your forum database and any and all
##                    applicable files before proceeding.
##
##

#
#---------[ 1. UPLOAD ]-------------------------------------------------------
#

install_mod.php              to 	/ your fluxbb folder
AP_Image_Award.php           to     /plugins/
admin_image_award.php        to 	/lang/English
admin_image_award.php        to 	/lang/French
Gold_Member_100x20.png       to 	/img/awards/
Silver_Member_100x20.png     to 	/img/awards/
Bronze_Member_100x20.png     to 	/img/awards/

#
#---------[ 2. RUN ]---------------------------------------------------------
#

install_mod.php


#
#---------[ 3. DELETE ]------------------------------------------------------
#

install_mod.php


#
#---------[ 4. OPEN ]--------------------------------------------------------
#

viewtopic.php

#
#---------[ 5. FIND (ligne 211) ]--------------------------------------------
#

// Retrieve the posts (and their respective poster/online status)
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.signature, u.email_setting, u.num_posts, u.registered, u.admin_note, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, g.g_id, g.g_user_title, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.id IN ('.implode(',', $post_ids).') ORDER BY p.id', true) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());


#
#---------[ 6. REPLACE WITH ]------------------------------------------------
#	

// Retrieve the posts (and their respective poster/online status) - [Mod] Image Award added
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.signature, u.email_setting, u.num_posts, u.registered, u.admin_note, u.imgaward, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, g.g_id, g.g_user_title, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.id IN ('.implode(',', $post_ids).') ORDER BY p.id', true) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error()); // Image Award Mod altered this (added one more column to fetch)


#
#---------[ 7. FIND ]--------------------------------------------------------
#

	$signature = '';


#
#---------[ 8. AFTER, ADD ]--------------------------------------------------
#

	$user_image_award = ''; // [Mod] Image Award added


#
#---------[ 9. FIND ]--------------------------------------------
#

	// If the poster is a registered user
	if ($cur_post['poster_id'] > 1)
	{

#
#---------[ 10. AFTER, ADD ]-------------------------------------------------
#

		// [Mod] Image Award
		if (file_exists(PUN_ROOT.'/lang/'.$pun_user['language'].'/admin_image_award.php'))
			require PUN_ROOT.'/lang/'.$pun_user['language'].'/admin_image_award.php';
        else
			require PUN_ROOT.'/lang/English/admin_image_award.php';

		if(strlen($cur_post['imgaward']) > 0){	// if we have something there, figure out what to output...
			//figure out the size of the award (Name of award should be in teh form:  Test_Award_100x20.png ... where png is format, 100x20 is dimensions and Test_Award is name of award (seen in admin interface)
			$awardmod_filename=$cur_post['imgaward'];
			$awardmod_temp=substr($awardmod_filename,strrpos($awardmod_filename,'_')+1); //we still have the file extentsion
			$awardmod_temp=substr($awardmod_temp,0,strpos($awardmod_temp,'.'));
			$awardmod_dimensions = explode('x',$awardmod_temp);	// there ... now the array will hold 100 and 20 in [0] and [1] respecively ... :)
			$awardmod_name=str_replace('_',' ',substr($awardmod_filename,0,strrpos($awardmod_filename,'_')));
			if($pun_config['o_avatars'] == '1' && $pun_user['show_avatars'] != '0')
				$user_image_award = "\t\t\t\t\t\t".'<dd class="award"><img src="img/awards/'.$awardmod_filename.'" width="'.$awardmod_dimensions[0].'" height="'.$awardmod_dimensions[1].'" alt="'.$lang_admin_image_award['award'].' '.pun_htmlspecialchars($awardmod_name).'" /></dd>'."\n";
			else
				$user_image_award = "\t\t\t\t\t\t".'<dd class="award">'.$lang_admin_image_award['award'].' "'.pun_htmlspecialchars($awardmod_name).'"</dd>';
		}
		// [Mod] End Image Award

#
#---------[ 11. FIND ]-------------------------------------------
#


<?php if ($user_avatar != '') echo "\t\t\t\t\t\t".'<dd class="postavatar">'.$user_avatar.'</dd>'."\n"; ?>


#
#---------[ 12. AFTER, ADD ]-------------------------------------------------
#

<?php if (strlen($user_image_award)>0) echo $user_image_award; // [Mod] Image Award added ?>


#
#---------[ 13. SAVE, UPLOAD viewtopic.php ]-----------------------------------------------
#

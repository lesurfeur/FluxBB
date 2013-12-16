##
##
##         Mod title:  Horizontal Layout of the style
##
##       Mod version:  1.4.1
##   Works on FluxBB:  1.5.4
##
## 			  Update:  2013-10-09 for FluxBB 1.5.4
##					   2012-11-15 for FluxBB 1.5.1
##                     2012-05-17 for FluxBB 1.5.0
##                     2011-09-05 for FluxBB 1.4.6
##			       	   2011-02-15 for FluxBB 1.4.4
##					   2010-10-03 for FluxBB 1.4.0
##
##      Release date:  November 1st, 2007 for FluxBB 1.2
##
##            Author:  Spiky aka lesurfeur (lesurfeur72@gmail.com)
##
##       Description:  This mod allows to show the information of the user horizontally in viewtopic.
##		               Select the style_horizontal.css to apply him.
##                   
##                     French: Ce mod permet de montrer les informations de l'utilisateur horizontalement dans viewtopic.
##                     Choisissez le style_horizontal.css pour l'appliquer.
##		              
## 
##    Affected files:  viewtopic.php
##					   (+ all modified styles included in the archive )
##
##        Affects DB:  No
##
##    Repository URL:  http://fluxbb.org/resources/mods/
##
##
##        DISCLAIMER:  Please note that "mods" are not officially supported by
##                     FluxBB. Installation of this modification is done at your
##                     own risk. Backup your forum database and any and all
##                     applicable files before proceeding.
##
##

#
#---------[ 1. UPLOAD ]----------------------------------------------------
#---------[ 1. TELECHARGER LES FICHIERS ]----------------------------------
#

files/style/Air_horizontal.css            to  /your_forum_folder/style/
files/style/Cobalt_horizontal.css         to  /your_forum_folder/style/
files/style/Earth_horizontal.css          to  /your_forum_folder/style/
files/style/Fire_horizontal.css           to  /your_forum_folder/style/
files/style/Lithium_horizontal.css        to  /your_forum_folder/style/
files/style/Mercury_horizontal.css        to  /your_forum_folder/style/
files/style/Oxygen_horizontal.css         to  /your_forum_folder/style/
files/style/Radium_horizontal.css         to  /your_forum_folder/style/
files/style/Sulfur_horizontal.css         to  /your_forum_folder/style/
files/style/Technetium_horizontal.css     to  /your_forum_folder/style/

#
#---------[ 2. OPEN ]---------------------------------------------------------
#---------[ 2. OUVRIR ]-------------------------------------------------------
#

viewtopic.php

#
#---------[ 3. FIND (line: 341) ]---------------------------------------
#---------[ 3. TROUVER ]------------------------------------------------------
#

?>
<div id="p<?php echo $cur_post['id'] ?>" class="blockpost<?php echo ($post_count % 2 == 0) ? ' roweven' : ' rowodd' ?><?php if ($cur_post['id'] == $cur_topic['first_post_id']) echo ' firstpost'; ?><?php if ($post_count == 1) echo ' blockpost1'; ?>">
	<h2><span><span class="conr">#<?php echo ($start_from + $post_count) ?></span> <a href="viewtopic.php?pid=<?php echo $cur_post['id'].'#p'.$cur_post['id'] ?>"><?php echo format_time($cur_post['posted']) ?></a></span></h2>
	<div class="box">
		<div class="inbox">
			<div class="postbody">
				<div class="postleft">
					<dl>
						<dt><strong><?php echo $username ?></strong></dt>
						<dd class="usertitle"><strong><?php echo $user_title ?></strong></dd>
<?php if ($user_avatar != '') echo "\t\t\t\t\t\t".'<dd class="postavatar">'.$user_avatar.'</dd>'."\n"; ?>
<?php if (count($user_info)) echo "\t\t\t\t\t\t".implode("\n\t\t\t\t\t\t", $user_info)."\n"; ?>
<?php if (count($user_contacts)) echo "\t\t\t\t\t\t".'<dd class="usercontacts">'.implode(' ', $user_contacts).'</dd>'."\n"; ?>
					</dl>
				</div>
				<div class="postright">
					<h3><?php if ($cur_post['id'] != $cur_topic['first_post_id']) echo $lang_topic['Re'].' '; ?><?php echo pun_htmlspecialchars($cur_topic['subject']) ?></h3>
					<div class="postmsg">
						<?php echo $cur_post['message']."\n" ?>
<?php if ($cur_post['edited'] != '') echo "\t\t\t\t\t\t".'<p class="postedit"><em>'.$lang_topic['Last edit'].' '.pun_htmlspecialchars($cur_post['edited_by']).' ('.format_time($cur_post['edited']).')</em></p>'."\n"; ?>
					</div>
<?php if ($signature != '') echo "\t\t\t\t\t".'<div class="postsignature postmsg"><hr />'.$signature.'</div>'."\n"; ?>
				</div>
			</div>
		</div>
		<div class="inbox">
			<div class="postfoot clearb">
				<div class="postfootleft"><?php if ($cur_post['poster_id'] > 1) echo '<p>'.$is_online.'</p>'; ?></div>
<?php if (count($post_actions)) echo "\t\t\t\t".'<div class="postfootright">'."\n\t\t\t\t\t".'<ul>'."\n\t\t\t\t\t\t".implode("\n\t\t\t\t\t\t", $post_actions)."\n\t\t\t\t\t".'</ul>'."\n\t\t\t\t".'</div>'."\n" ?>
			</div>
		</div>
	</div>
</div>

<?php

#
#---------[ 4. REPLACE WITH ]-------------------------------------------------
#---------[ 4. REMPLACER PAR ]------------------------------------------------
#

	// [Mod] Horizontal Layout of the style
	if(preg_match('/[A-Za-z]_horizontal/',$pun_user['style']))
	{
	?><div id="p<?php echo $cur_post['id'] ?>" class="blockpost<?php echo ($post_count % 2 == 0) ? ' roweven' : ' rowodd' ?><?php if ($cur_post['id'] == $cur_topic['first_post_id']) echo ' firstpost'; ?><?php if ($post_count == 1) echo ' blockpost1'; ?>">
	<h2><span><span class="conr">#<?php echo ($start_from + $post_count) ?></span> <a href="viewtopic.php?pid=<?php echo $cur_post['id'].'#p'.$cur_post['id'] ?>"><?php echo format_time($cur_post['posted']) ?></a></span></h2>
	<div class="box">
		<div class="inbox">
			<div class="userinfo">
				<table cellpadding="0" cellspacing="10" border="0" width="100%">
					<tr>
						<td class="username"><dl><dt><strong><?php echo $username ?></strong></dt>
						<?php echo '<dd class="usertitle"><strong>'.$user_title.'</strong></dd></dl>'; ?>
						<?php echo $user_avatar ?>
						<?php if ($cur_post['poster_id'] > 1) echo '<p>'.$is_online.'</p>'; ?>
						</td>
						<td class="spacer">&nbsp;</td>
						<td>
							<div class="usermisc">
								<dl>
<?php if (count($user_info)) echo "\t\t\t\t\t\t".implode("\n\t\t\t\t\t\t", $user_info)."\n"; ?>
<?php if (count($user_contacts)) echo "\t\t\t\t\t\t".'<dd class="usercontacts">'.implode(' ', $user_contacts).'</dd>'."\n"; ?>
								</dl>
							</div>
						</td>
					</tr>
				</table>
			</div>
				<div class="postright">
					<h3><?php if ($cur_post['id'] != $cur_topic['first_post_id']) echo $lang_topic['Re'].' '; ?><?php echo pun_htmlspecialchars($cur_topic['subject']) ?></h3>
					<div class="postmsg">
						<?php echo $cur_post['message']."\n" ?>
<?php if ($cur_post['edited'] != '') echo "\t\t\t\t\t\t".'<p class="postedit"><em>'.$lang_topic['Last edit'].' '.pun_htmlspecialchars($cur_post['edited_by']).' ('.format_time($cur_post['edited']).')</em></p>'."\n"; ?>
					</div>
<?php if ($signature != '') echo "\t\t\t\t\t".'<div class="postsignature postmsg"><hr />'.$signature.'</div>'."\n"; ?>
				</div>
<?php if (count($post_actions)) echo "\t\t\t\t".'<div class="postfootright">'."\n\t\t\t\t\t".'<ul>'."\n\t\t\t\t\t\t".implode("\n\t\t\t\t\t\t", $post_actions)."\n\t\t\t\t\t".'</ul>'."\n\t\t\t\t".'</div>'."\n" ?>
		</div>
	</div>
</div>
<?php
	}
	else
	{
?>
<div id="p<?php echo $cur_post['id'] ?>" class="blockpost<?php echo ($post_count % 2 == 0) ? ' roweven' : ' rowodd' ?><?php if ($cur_post['id'] == $cur_topic['first_post_id']) echo ' firstpost'; ?><?php if ($post_count == 1) echo ' blockpost1'; ?>">
	<h2><span><span class="conr">#<?php echo ($start_from + $post_count) ?></span> <a href="viewtopic.php?pid=<?php echo $cur_post['id'].'#p'.$cur_post['id'] ?>"><?php echo format_time($cur_post['posted']) ?></a></span></h2>
	<div class="box">
		<div class="inbox">
			<div class="postbody">
				<div class="postleft">
					<dl>
						<dt><strong><?php echo $username ?></strong></dt>
						<dd class="usertitle"><strong><?php echo $user_title ?></strong></dd>
<?php if ($user_avatar != '') echo "\t\t\t\t\t\t".'<dd class="postavatar">'.$user_avatar.'</dd>'."\n"; ?>
<?php if (count($user_info)) echo "\t\t\t\t\t\t".implode("\n\t\t\t\t\t\t", $user_info)."\n"; ?>
<?php if (count($user_contacts)) echo "\t\t\t\t\t\t".'<dd class="usercontacts">'.implode(' ', $user_contacts).'</dd>'."\n"; ?>
					</dl>
				</div>
				<div class="postright">
					<h3><?php if ($cur_post['id'] != $cur_topic['first_post_id']) echo $lang_topic['Re'].' '; ?><?php echo pun_htmlspecialchars($cur_topic['subject']) ?></h3>
					<div class="postmsg">
						<?php echo $cur_post['message']."\n" ?>
<?php if ($cur_post['edited'] != '') echo "\t\t\t\t\t\t".'<p class="postedit"><em>'.$lang_topic['Last edit'].' '.pun_htmlspecialchars($cur_post['edited_by']).' ('.format_time($cur_post['edited']).')</em></p>'."\n"; ?>
					</div>
<?php if ($signature != '') echo "\t\t\t\t\t".'<div class="postsignature postmsg"><hr />'.$signature.'</div>'."\n"; ?>
				</div>
			</div>
		</div>
		<div class="inbox">
			<div class="postfoot clearb">
				<div class="postfootleft"><?php if ($cur_post['poster_id'] > 1) echo '<p>'.$is_online.'</p>'; ?></div>
<?php if (count($post_actions)) echo "\t\t\t\t".'<div class="postfootright">'."\n\t\t\t\t\t".'<ul>'."\n\t\t\t\t\t\t".implode("\n\t\t\t\t\t\t", $post_actions)."\n\t\t\t\t\t".'</ul>'."\n\t\t\t\t".'</div>'."\n" ?>
			</div>
		</div>
	</div>
</div>

<?php
	}
// [Mod] End Horizontal Layout of the style

#
#---------[ 5. SAVE / UPLOAD ]----------------------------------------------
#---------[ 5. ENREGISTRER / ENVOYER SUR LE SERVEUR ]-----------------------
#

viewtopic.php

##
##        Mod title:  Tag [hide]
##
##      Mod version:  1.2.2
##  Works on FluxBB:  1.5.4
##
##      Review date:  2013-10-09 for FluxBB 1.5.4
##					  2012-11-14 for FluxBB 1.5.1
##					  2012-05-19 for FluxBB 1.5.0
##					  2011-07-10 for FluxBB 1.4.5
##
##     Release date:  2006-04-11 for PunBB 1.2.11
##
##           Author:  Spiky aka lesurfeur
##                        
##     Description :  Allows to hide from the text (or link) to all the members (excepted for the administrators and the moderators)
##                    with beacons [hide][/hide].
##                    The members will have to write an answer to see the hidden message.
##
##                    French:
##                    Permet de cacher du texte (ou lien) à tous les membres (Excepté pour les administrateurs et les modérateurs)
##                    avec les balises [hide][/hide].
##                    Les membres devront écrire une réponse pour voir le message caché.
##
##
##   Affected files:  include/parser.php
##                    viewtopic.php 
##                    lang/English/common.php (P.S: Make him for all the languages installed in your forum)
##                    lang/French/common.php  (P.S: le faire pour toutes les langues installées dans votre forum)
##                  
##       Affects DB:  No
##
##       DISCLAIMER:  Please note that "mods" are not officially supported by
##                    FluxBB. Installation of this modification is done at
##                    your own risk. Backup your forum database and any and
##                    all applicable files before proceeding.
##


#
#---------[ 1. OPEN ]------------------------------------------------------
#---------[ 1. OUVRIR ]----------------------------------------------------
#

include/parser.php

#
#---------[ 2. FIND ]-----------------------------------------------------
#---------[ 2. TROUVER ]--------------------------------------------------
#

	$pattern[] = '%\[b\](.*?)\[/b\]%ms';
    
#
#---------[ 3. ADD BEFORE ]-----------------------------------------------
#---------[ 3. AJOUTER AVANT ]--------------------------------------------
#

// MOD HIDE TAG begin
    if (strpos($text, 'hide') !== false)
    {
        if ($pun_user['is_guest'])
        {
			$text = preg_replace("#\[hide\](.+?)\[/hide\]#is", '<strong>['.$lang_common['Hidden text guest'].']</strong>', $text);
        }
        else if(($pun_user['g_id'] == PUN_ADMIN) || ($pun_user['g_id'] == PUN_MOD) || ($pun_user['num_post_in_topic'] > 0))
        {
            $text = str_replace('[hide]', '</p><p><strong>'.$lang_common['Hidden text'].':</strong><br /><em>', $text);
            $text = preg_replace('#\[\/hide\]\s*#', '</em></p><p>', $text);
        }
        else 
        {
            $text = preg_replace("#\[hide\](.+?)\[/hide\]#is", '<strong>['.$lang_common['Hidden text num_post'].']</strong>', $text);
        }
    }    
// MOD HIDE TAG end

#
#---------[ 4. FIND ]-----------------------------------------------------
#---------[ 4. TROUVER ]--------------------------------------------------
#

	// List of all the tags
	$tags = array('quote', 'code', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'img', 'list', '*', 'h', 'topic', 'post', 'forum', 'user');

#
#---------[ 5. REPLACE WITH ]---------------------------------------------
#---------[ 5. REMPLACER PAR ]--------------------------------------------
#

	// List of all the tags
	$tags = array('quote', 'code', 'hide', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'img', 'list', '*', 'h', 'topic', 'post', 'forum', 'user'); // MOD HIDE TAG added

#
#---------[ 6. OPEN ]-----------------------------------------------------
#---------[ 6. OUVRIR ]---------------------------------------------------
#

viewtopic.php

#
#---------[ 7. FIND ]-----------------------------------------------------
#---------[ 7. TROUVER ]--------------------------------------------------
#

	$cur_post['message'] = parse_message($cur_post['message'], $cur_post['hide_smilies']);

#
#---------[ 8. ADD BEFORE ]-----------------------------------------------
#---------[ 8. AJOUTER AVANT ]--------------------------------------------
#

	// MOD HIDE TAG begin
	$result_num_post = $db->query('SELECT count(message) as num_message FROM '.$db->prefix.'posts WHERE poster_id='.$pun_user['id'].' AND topic_id='.$id, true) or error('Cannot found the amount of message of the user', __FILE__, __LINE__, $db->error());
	$num_post_user_in_topic = $db->fetch_assoc($result_num_post);
	$pun_user['num_post_in_topic'] = $num_post_user_in_topic['num_message'];
	// MOD HIDE TAG end

#
#---------[ 9. OPEN ]-----------------------------------------------------
#---------[ 9. OUVRIR ]---------------------------------------------------
#

lang/English/common.php

#
#---------[ 10. FIND ]-----------------------------------------------------
#---------[ 10. TROUVER ]--------------------------------------------------
#

'BBCode list size error'			=>	'Your list was too long to parse, please make it smaller!',

#
#---------[ 11. ADD AFTER ]------------------------------------------------
#---------[ 11. AJOUTER APRES ]--------------------------------------------
#

// MOD HIDE TAG begin
'Hidden text guest'    				=>  'You must login to view hidden text.',
'Hidden text'         			    =>  'Hidden Text',
'Hidden text num_post' 				=>  'You need to reply to this post to see the hidden text.',
// MOD HIDE TAG end

#
#---------[ 9b. OPEN (FOR FRENCH) ]-----------------------------------------------------
#---------[ 9b. OUVRIR ]---------------------------------------------------
#

lang/French/common.php

#
#---------[ 10b. FIND ]-----------------------------------------------------
#---------[ 10b. TROUVER ]--------------------------------------------------
#

'BBCode list size error'		=>	'Votre liste étant trop longue pour être analysée, veuillez la réduire s\'il vous plaît&#160;!',

#
#---------[ 11b. ADD AFTER ]------------------------------------------------
#---------[ 11b. AJOUTER APRES ]--------------------------------------------
#

// MOD HIDE TAG begin
'Hidden text guest'    =>  'Vous devez être enregistré pour voir le message caché.',
'Hidden text'          =>  'Message caché&#160;',
'Hidden text num_post' =>  'Vous devez poster un message pour voir le texte caché.',
// MOD HIDE TAG end

#
#---------[ 12. SAVE & UPLOAD ]--------------------------------------------
#---------[ 12. ENREGISTRER & ENVOYER SUR LE SERVEUR ]---------------------
#

include/parser.php
viewtopic.php
lang/English/common.php
lang/French/common.php

#
#---------[ 13. NOTES (English) ]------------------------------------------
#

For those who have FluxToolBar.
You can add a new image of button in the file img/fluxtoolbar/smooth and to create the new button with the plugin (that is the new bbcode [hide]).
Note that during the addition of the button, you will have to modify include/parser.php and message(s) bt_[name](_msg_n) in your files of language (fluxtoolbar.php).

#
#---------[ 13b. NOTES (Français) ]--------------------------------------
#

Pour ceux qui ont la FluxToolBar.
Vous pouvez ajouter une nouvelle image de bouton dans le dossier img/fluxtoolbar/smooth et créer le nouveau bouton via le plugin (c'est à dire le nouveau bbcode [hide]).
Notez que lors de l'ajout du bouton, vous devrez modifier include/parser.php et le(s) messages(s) bt_[nom](_msg_n) dans vos fichiers de langue (fluxtoolbar.php).

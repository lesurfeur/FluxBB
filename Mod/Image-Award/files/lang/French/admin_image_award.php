<?php

// Language definitions used in Image Award Plugin
$lang_admin_image_award = array(

'plugin_desc'              		=>	'Ce plugin attribue des images de récompense. On permet à chaque utilisateur un maximum d\'une récompense.<br />Pour ajouter vos propres récompenses, il suffit simplement de mettre les fichiers dans le dossier "img/awards/" et les nommer de la fa&ccedil;on suivante :<br />&bull; Le nom de la récompense avec un tiret bas (_) au lieu de l\'espace,<br />&bull; Les dimensions de l\'image séparée par un petit x,<br />&bull; L\'extension de fichier.<br />Exemple : une récompense appelée "Test Récompense" avec la taille 100 pixels horizontaux et 20 pixels de haut, dans le format .png devrait &ecirc;tre nommée : "Test_Recompense_100x20.png" dans le dossier. Ne pas suivre ce schéma fera échouer l\'affichage de la récompense! Ce schéma de désignation est aussi utilisé pour l\'affichage "pas d\'image" (quand les personnes ont désactivées l\'option d\'affichage des avatars, ils verront alors la récompense sous forme de texte à la place) donc il faut bien formater l\'étiquette img correctement.',
'options'                       =>  'Options',
'give_award'                    =>  'Donnez une récompense à un utilisateur',
'user_id'                       =>  'Id Utilisateur&#160;:',
'user_id_desc'                  =>  'Id de l\'utilisateur a qui vous voulez donner ou enlever une récompense.',
'award'                         =>  'Récompense&#160;:',
'validate'                      =>  'Valider',
'validate_desc'                 =>  'Choisir la récompense que vous voulez assigner à l\'utilisateur puis validez (ou sélectionnez **Enlever la Récompense** pour supprimer la récompense à l\'utilisateur)',
'remove'                        =>  '**Enlever la Récompense**',
'award_added'	                =>	'Récompense ajoutée ou supprimée avec succès',
'award_user_id'	                =>  'Vous devez assigner la récompense à un utilisateur !'

);

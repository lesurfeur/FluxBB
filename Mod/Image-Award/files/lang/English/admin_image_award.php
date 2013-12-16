<?php

// Language definitions used in Image Award Plugin
$lang_admin_image_award = array(

'plugin_desc'              		=>	'This plugin handles the image award. Each user is allowed a maximum of one award.<br />To add your own awards, just put the files in the "img/awards/" folder and name them in the following way:<br />&bull; Name of award with underscore (_) instead of space.<br />&bull; Dimensions of award separaded by a small x<br />&bull; File extension<br />Example: An award called "Test Award" with size 100 pixels horizontal and 20 pixels high, in png format should be named: "Test_Award_100x20.png" in the directory. Failing to follow this standard will most probably make the awards to fail! This naming scheme is used to display the "no image" version of the award (when people don\'t want to see avatars, they get the award as text instead), And also to format the img tag correctly.',
'options'                       =>  'Options',
'give_award'                    =>  'Give a user an award',
'user_id'                       =>  'User id:',
'user_id_desc'                  =>  'The user id of the user you want to assign or remove an award on.',
'award'                         =>  'Award:',
'validate'                      =>  'Submit',
'validate_desc'                 =>  'The award the user is to be assigned (or select **Remove Award** to clear award on user)',
'remove'                        =>  '**Remove Award**',
'award_added'	                =>	'Award added or deleted successfully',
'award_user_id'	                =>	'You must assign the award to an user!'

);

<?php
/**
*
* @package phpBB Extension - Cecilius Stream List
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'VIEW_TWITCH_CHANNEL'	=> 'View Twitch Channel',
	'STREAMLIST_PAGE'	=> 'Streams',
	'STREAMLIST_NO_ONLINE_STREAM'	=> 'I cannot find any online streams!',
	'STREAMLIST_NO_OFFLINE_STREAM'	=> 'I cannot find any offline streams!',
	'STREAMLIST_ONLINE_STREAM'	=> 'Online streams',
	'STREAMLIST_OFFLINE_STREAM'	=> 'Offline streams',
	
	'ACP_STREAMLIST_TITLE'	=> 'Streamlist module',
	'ACP_STREAMLIST'	=> 'Settings',
	'ACP_STREAMLIST_SHOW_OFFLINE'	=> 'Show offline streams?',
	'ACP_STREAMLIST_SHOW_OFFLINE_PLAYERS'	=> 'Show video players for offline streams?',
	'ACP_STREAMLIST_SETTING_SAVED'	=> 'Settings have been saved successfully!',
));

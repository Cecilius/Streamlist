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
	'STREAMLIST_PAGE'			=> 'Streams',
	'STREAMLIST_NO_STREAM'		=> 'I cannot find any streams!',
));

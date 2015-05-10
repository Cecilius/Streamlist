<?php
/**
*
* @package phpBB Extension - Cecilius Stream List
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace cecilius\streamlist\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\cecilius\streamlist\acp\main_module',
			'title'		=> 'ACP_STREAMLIST_TITLE',
			'version'	=> '0.2.0',
			'modes'		=> array(
				'settings'	=> array('title' => 'ACP_STREAMLIST', 'auth' => 'ext_cecilius/streamlist && acl_a_board', 'cat' => array('ACP_STREAMLIST_TITLE')),
			),
		);
	}
}

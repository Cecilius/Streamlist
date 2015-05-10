<?php
/**
*
* @package phpBB Extension - Acme Demo
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace cecilius\streamlist\migrations;

class release_0_2_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['cecilius_streamlist_show_offline']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\alpha2');
	}

	public function update_data()
	{
		return array(
			array('config.add', array(
				'cecilius_streamlist_show_offline' => 0
				'cecilius_streamlist_show_offline_players' => 0
				)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_STREAMLIST_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_STREAMLIST_TITLE',
				array(
					'module_basename'	=> '\cecilius\streamlist\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}

<?php
/**
*
* @package phpBB Extension - Cecilius Stream List
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace cecilius\streamlist\acp;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('acp/common');
		$this->tpl_name = 'streamlist_body';
		$this->page_title = $user->lang('ACP_STREAMLIST_TITLE');
		add_form_key('cecilius/streamlist');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('cecilius/streamlist'))
			{
				trigger_error('FORM_INVALID');
			}

			$config->set('cecilius_streamlist_show_offline', $request->variable('cecilius_streamlist_show_offline', 0));

			$config->set('cecilius_streamlist_show_offline_players', $request->variable('cecilius_streamlist_show_offline_players', 0));

			trigger_error($user->lang('ACP_STREAMLIST_SETTING_SAVED') . adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'U_ACTION'								=> $this->u_action,
			'CECILIUS_STREAMLIST_SHOW_OFFLINE'		=> $config['cecilius_streamlist_show_offline'],
			'CECILIUS_STREAMLIST_SHOW_OFFLINE_PLAYERS'		=> $config['cecilius_streamlist_show_offline_players'],
		));
	}
}

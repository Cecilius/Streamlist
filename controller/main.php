<?php
/**
*
* @package phpBB Extension - Cecilius streamlist
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace cecilius\streamlist\controller;

class main
{
	/* @var \phpbb\db\driver\driver_interface */
	protected $db;
 
	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db 
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->db = $db;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* Controller for route /streams
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle()
	{
		$stream_array = array();
		
		$stream_array = $this->get_streams();

		if(empty($stream_array))
		{
			$l_message = 'STREAMLIST_NO_STREAM';
		}
		else
		{
			$l_message = 'STREAMLIST_PAGE';

			foreach($stream_array as $stream)
			{
				$this->template->assign_block_vars('streams', array(
					'STREAM_OWNER'	=> $stream['user_id'],
					'STREAM_LINK'	=> $stream['stream_address']
				));
			}
		}

		$this->template->assign_var('STREAMLIST_MESSAGE', $this->user->lang($l_message));

		return $this->helper->render('streamlist_body.html');
	}
	
	/**
	*
	* Obtain list of streams
	*
	*/
	private function get_streams()
	{
		$sql = 'SELECT user_id, pf_streamlist
			FROM ' . PROFILE_FIELDS_DATA_TABLE . '
			WHERE pf_streamlist <> \'\'';
		$result = $this->db->sql_query($sql);

		$streams_array = array();
		
		while ($row = $this->db->sql_fetchrow($result))
		{
			$streams_array[] = array( 
				'user_id'			=> $row['user_id'], 
				'stream_address'	=> $row['pf_streamlist']
			);
		}
		$this->db->sql_freeresult($result);

		return $streams_array;
	}
	
}

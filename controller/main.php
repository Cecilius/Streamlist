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

		$stream_array = $this-> check_if_live($stream_array);

		$l_message = 'STREAMLIST_PAGE';

		if(!empty($stream_array))
		{
			
			$online_array = array();
			$offline_array = array();
			
			foreach($stream_array as $stream)
			{
				if($stream['live'] === true)
				{
					$online_array[] = $stream;
				}
				else
				{
					$offline_array[] = $stream;
				}
			}
			
			foreach($online_array as $stream)
			{
				$this->template->assign_block_vars('online_streams', array(
					'STREAM_OWNER'	=> $stream['user_name'],
					'STREAM_LINK'	=> $stream['stream_address']
				));
			}
			foreach($offline_array as $stream)
			{
				$this->template->assign_block_vars('offline_streams', array(
					'STREAM_OWNER'	=> $stream['user_name'],
					'STREAM_LINK'	=> $stream['stream_address']
				));
			}
		}

		$this->template->assign_vars(array(
			'STREAMLIST_MESSAGE' => $this->user->lang($l_message),
			'STREAMLIST_ONLINE' => $this->user->lang('STREAMLIST_ONLINE_STREAM'),
			'STREAMLIST_OFFLINE' => $this->user->lang('STREAMLIST_OFFLINE_STREAM'),
			'NO_ONLINE_STREAM' => $this->user->lang('STREAMLIST_NO_ONLINE_STREAM'),
			'NO_OFFLINE_STREAM' => $this->user->lang('STREAMLIST_NO_OFFLINE_STREAM')
		));

		return $this->helper->render('streamlist_body.html');
	}
	
	/**
	*
	* Obtain list of streams
	*
	*/
	private function get_streams()
	{
		$streams_array = array();

		$sql = 'SELECT p.user_id, p.pf_phpbb_youtube, p.pf_phpbb_twitch, u.username, u.user_id
			FROM ' . PROFILE_FIELDS_DATA_TABLE . ' p, ' . USERS_TABLE . ' u 
			WHERE (p.pf_phpbb_youtube <> \'\' OR p.pf_phpbb_twitch <> \'\') 
			AND p.user_id = u.user_id 
			ORDER BY u.username';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			if($row[pf_phpbb_youtube] !== '')
			$streams_array[] = array( 
				'user_id'			=> $row['user_id'], 
				'user_name'			=> $row['username'], 
				'stream_address'	=> '', 
				'provider'			=> 'youtube', 
				'channel_name'		=> $row['pf_phpbb_youtube'], 
				'live'				=> false
			);

			if($row[pf_phpbb_twitch] !== '')
			$streams_array[] = array( 
				'user_id'			=> $row['user_id'], 
				'user_name'			=> $row['username'], 
				'stream_address'	=> '', 
				'provider'			=> 'twitch', 
				'channel_name'		=> $row['pf_phpbb_twitch'], 
				'live'				=> false
			);
		}

		$this->db->sql_freeresult($result);

		return $streams_array;
	}

	private function check_if_live($streams)
	{
		foreach($streams as &$stream)
		{
			switch($stream['provider'])
			{
				case 'youtube':
					$youtube_api_key = '';
					$result = $this->curl_get('https://www.googleapis.com/youtube/v3/channels?part=id&forUsername=' . $stream['channel_name'] . '&key=' . $youtube_api_key);

					if($result !== FALSE && $result !== '' && $this->is_json($result) !== false)
					{
						$json_array = json_decode($result, true);

						if(!empty($json_array['items']))
						{
							$channel_id = $json_array['items'][0]['id'];

							$result = $this->curl_get('https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channel_id . '&eventType=live&type=video&key=' . $youtube_api_key);
							
							if($result !== FALSE && $result !== '' && $this->is_json($result) !== false)
							{
								$json_array = json_decode($result, true);

								if(!empty($json_array['items']))
								{
									$stream['stream_address'] = '<iframe width="640" height="390" src="http://www.youtube.com/embed/' . $json_array['items'][0]['id']['videoId'] . '"></iframe>';
									$stream['live'] = true;
								}
							}
						}
					}
					if($stream['live'] === false)
					{
						$stream['stream_address'] = '<iframe width="640" height="390" src="http://www.youtube.com/embed?listType=user_uploads&list=' . $stream['channel_name'] . '"></iframe>';
					}
				break;

				case 'twitch':
					$result = $this->curl_get('https://api.twitch.tv/kraken/streams/' . $stream['channel_name']);

					if($result !== FALSE && $result !== '' && $this->is_json($result) !== false)
					{
						$json_array = json_decode($result, true);

						if(!empty($json_array['stream']))
						{
							$stream['stream_address'] = '<iframe id="player" type="text/html" width="640" height="390" src="' . $json_array['stream']['channel']['url'] . '/embed" frameborder="0"></iframe>';
							$stream['live'] = true;
						}
					}
					if($stream['live'] === false)
					{
						$stream['stream_address'] = '<iframe id="player" type="text/html" width="640" height="390" src="http://www.twitch.tv/' . $stream['channel_name'] . '/embed" frameborder="0"></iframe>';
					}
				break;

				default:

				break;
			}
		}
		return $streams;
	}

    private function is_json($string){
        return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

	private function curl_get($json_url)
	{
		// Initializing curl
		$ch = curl_init($json_url);

		// Configuring curl options
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $json_url,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => array('Accept: application/vnd.twitchtv.v3+json')
		);

		// Setting curl options
		curl_setopt_array($ch, $options);

		// Getting results
		$result = curl_exec($ch); // Getting jSON result string 

		curl_close($ch);
		
		return $result;
	}
}

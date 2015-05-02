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
	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* Demo controller for route /streams
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle()
	{
		$l_message = 'STREAMLIST_NO_STREAM';
		$this->template->assign_var('STREAMLIST_MESSAGE', $this->user->lang($l_message));

		return $this->helper->render('streamlist_body.html');
	}
}

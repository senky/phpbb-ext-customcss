<?php
/**
 *
 * Custom CSS. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\customcss\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.page_header'	=> 'add_custom_css',
		);
	}

	protected $helper;
	protected $template;
	protected $user;
	protected $config;
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\config\config $config)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
	}

	public function add_custom_css()
	{
		$this->template->assign_vars(array(
			'U_SENKY_CUSTOM_CSS'	=> $this->helper->route('senky_customcss_controller', [
				'style_id'			=> $this->user->style['style_id'],
				'assets_version'	=> $this->config['assets_version'],
			]),
		));
	}
}

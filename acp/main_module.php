<?php
/**
 *
 * Custom CSS. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\customcss\acp;

class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;
		$acp_controller = $phpbb_container->get('senky.customcss.controller.acp');
		$language = $phpbb_container->get('language');

		$this->tpl_name = 'customcss';
		$this->page_title = $language->lang('ACP_CUSTOMCSS_TITLE');

		$acp_controller->set_page_url($this->u_action);
		$acp_controller->display_options();
	}
}

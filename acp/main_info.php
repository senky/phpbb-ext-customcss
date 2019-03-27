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

class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\senky\customcss\acp\main_module',
			'title'		=> 'ACP_CUSTOMCSS_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_CUSTOMCSS',
					'auth'	=> 'ext_senky/customcss && acl_a_board',
					'cat'	=> ['ACP_CUSTOMCSS_TITLE']
				],
			],
		];
	}
}

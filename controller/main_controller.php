<?php
/**
 *
 * Custom CSS. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\customcss\controller;

use Symfony\Component\HttpFoundation\Response;

class main_controller
{
	protected $db;
	protected $customcss_table;
	public function __construct(\phpbb\db\driver\driver_interface $db, $customcss_table)
	{
		$this->db = $db;
		$this->customcss_table = $customcss_table;
	}

	public function handle($style_id)
	{
		// Select asset_version for requested style and 'all' style.
		// Order prioritises user style, but if such
		// doesn't exist, 'all' style will kick in.
		$sql = 'SELECT css
			FROM ' . $this->customcss_table . '
			WHERE style_id = ' . (int) $style_id . '
				OR style_id = 0';
		$result = $this->db->sql_query($sql, 3600);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			$content = '';
			$code = 404;
		}
		else
		{
			$content = $row['css'];
			$code = 200;
		}

		$response = new Response($content, $code, ['Content-Type' => 'text/css']);
		$response->setMaxAge(31556926); // 1 year; assets_version will take care of updates
		return $response;
	}
}

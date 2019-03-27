<?php
/**
 *
 * Custom CSS. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\customcss\migrations;

class m2_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
			FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND module_langname = 'ACP_CUSTOMCSS_TITLE'";
		$result = $this->db->sql_query($sql);
		$module_id = (int) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);
		return $module_id;
	}

	public static function depends_on()
	{
		return ['\senky\customcss\migrations\m1_schema'];
	}

	public function update_data()
	{
		return [
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CUSTOMCSS_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_CUSTOMCSS_TITLE',
				[
					'module_basename'	=> '\senky\customcss\acp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}

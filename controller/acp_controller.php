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

class acp_controller
{
	const ALL_STYLENAME = 'all'; // not hardcoded lang - this is a style name
	protected $u_action;

	protected $config;
	protected $language;
	protected $request;
	protected $template;
	protected $db;
	protected $cache;
	protected $styles_table;
	protected $customcss_table;
	public function __construct(\phpbb\config\config $config, \phpbb\language\language $language, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\db\driver\driver_interface $db, \phpbb\cache\driver\driver_interface $cache, $styles_table, $customcss_table)
	{
		$this->config = $config;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->db = $db;
		$this->cache = $cache;
		$this->styles_table = $styles_table;
		$this->customcss_table = $customcss_table;
	}

	public function display_options()
	{
		$this->language->add_lang('acp', 'senky/customcss');
		add_form_key('senky_customcss_acp');

		switch ($this->request->variable('action', ''))
		{
			case 'edit':

				if ($this->request->is_set_post('cancel'))
				{
					redirect($this->u_action);
				}

				$style_id = $this->request->variable('style', 0);

				if ($style_id === 0)
				{
					$sql = 'SELECT style_id, css
						FROM ' . $this->customcss_table . '
						WHERE style_id = 0';
				}
				else
				{
					$sql = 'SELECT s.style_id, s.style_name, sc.css
						FROM ' . $this->styles_table . ' s
						LEFT JOIN ' . $this->customcss_table . ' sc
							ON (sc.style_id = s.style_id)
						WHERE s.style_id = ' . (int) $style_id;
				}
				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if ($this->request->is_set_post('submit'))
				{
					$css = $this->request->variable('customcss', '', true);

					if (!check_form_key('senky_customcss_acp'))
					{
						$errors[] = $this->language->lang('FORM_INVALID');
						$row['css'] = $css;
					}

					if (empty($errors))
					{
						// insert
						if ($row === false)
						{
							$sql_ary = [
								'style_id'		=> (int) $style_id,
								'css'			=> $css,
							];
							$sql = 'INSERT INTO ' . $this->customcss_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
						}
						// update
						else if (strlen($css) > 0)
						{
							$sql = 'UPDATE ' . $this->customcss_table . "
								SET css = '" . $this->db->sql_escape($css) . "'
								WHERE style_id = " . (int) $style_id;
						}
						// delete if CSS is empty
						else {
							$sql = 'DELETE FROM ' . $this->customcss_table . '
								WHERE style_id = ' . (int) $style_id;
						}
						$this->db->sql_query($sql);
						$this->config->increment('assets_version', 1);
						$this->cache->destroy('sql', $this->customcss_table);

						trigger_error($this->language->lang('CUSTOMCSS_SAVED') . adm_back_link($this->u_action));
					}
				}
				else if ($row === false && $style_id !== 0)
				{
					trigger_error($this->language->lang('CUSTOMCSS_STYLE_NOT_EXIST') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$this->template->assign_vars([
					'S_EDIT'		=> true,
					'STYLE_ID'		=> $row['style_id'],
					'STYLE_NAME'	=> $style_id !== 0 ? $row['style_name'] : self::ALL_STYLENAME,
					'STYLE_CSS'		=> $row['css'],
				]);

			break;

			case '':

				// display generic "all" styles
				$sql = 'SELECT ' . $this->sql_length_function() . '(css) as css_length
					FROM ' . $this->customcss_table . '
					WHERE style_id = 0';
				$result = $this->db->sql_query($sql);
				$css_length = $this->db->sql_fetchfield('css_length');
				$this->db->sql_freeresult($result);

				$this->template->assign_block_vars('styles', [
					'STYLE_NAME'	=> self::ALL_STYLENAME,
					'HAS_CUSTOMCSS'	=> $css_length > 0,
					'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;style=0',
				]);

				// display other styles
				$sql = 'SELECT s.style_id, s.style_name, ' . $this->sql_length_function() . '(sc.css) as css_length
					FROM ' . $this->styles_table . ' s
					LEFT JOIN ' . $this->customcss_table . ' sc
						ON (sc.style_id = s.style_id)
					WHERE s.style_active = 1';
				$result = $this->db->sql_query($sql);
				while ($row = $this->db->sql_fetchrow($result))
				{
					$this->template->assign_block_vars('styles', [
						'STYLE_NAME'	=> $row['style_name'],
						'HAS_CUSTOMCSS'	=> $row['css_length'] > 0,
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;style=' . $row['style_id'],
					]);
				}
				$this->db->sql_freeresult($result);

			break;
		}

		$s_errors = !empty($errors);
		$this->template->assign_vars(array(
			'U_ACTION'		=> $this->u_action,
			'S_ERROR'		=> $s_errors,
			'ERROR_MSG'		=> $s_errors ? implode('<br />', $errors) : '',
		));
	}

	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

	protected function sql_length_function()
	{
		switch($this->db->get_sql_layer())
		{
			case 'mssql_odbc':
				return 'CHAR_LENGTH';
			break;

			case 'mssqlnative':
				return 'LEN';
			break;
		}
		return 'LENGTH';
	}
}

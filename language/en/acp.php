<?php
/**
 *
 * Custom CSS. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'STYLE_NAME'		=> 'Style name',
	'STYLE_CUSTOM_CSS'	=> 'Has custom CSS',

	'CUSTOM_CSS'				=> 'Custom CSS',
	'CUSTOM_CSS_EXPLAIN'		=> 'Here you can insert custom CSS that will be loaded on all pages for users using this style.',
	'VALIDATE_CSS'				=> 'Validate CSS',
	'CSS_VALIDATION_SUCCESS'	=> '<br />✓ Everything is alright.',
	'CUSTOMCSS_SAVED'			=> 'Custom CSS has been updated.',
	'CUSTOMCSS_STYLE_NOT_EXIST'	=> 'Style was not found.',
));

<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(

	'STORAGE_ADAPTER_LOCAL_SUBFOLDERS_NAME'							=> 'Local subfolders',
	'STORAGE_ADAPTER_LOCAL_SUBFOLDERS_OPTION_PATH'					=> 'Path',
	'STORAGE_ADAPTER_LOCAL_SUBFOLDERS_OPTION_SUBFOLDERS'			=> 'Organize in subfolders',
	'STORAGE_ADAPTER_LOCAL_SUBFOLDERS_OPTION_SUBFOLDERS_EXPLAIN'	=> 'Some web servers may have problems storing large number of files in a single directory. Enable this option to distribute files in different directories.',

));

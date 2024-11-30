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
	'STORAGE_ADAPTER_LOCAL_SUBFOLDERS_OPTION_PATH_EXPLAIN'			=> 'Storage path for folders and files.<br>For example: <samp>files</samp>, <samp>images/avatars/upload</samp> or <samp>store</samp>.',

));

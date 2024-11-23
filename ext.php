<?php

namespace rubencm\storage_subfolders;

class ext extends \phpbb\extension\base
{
	/**
	 * Check whether or not the extension can be enabled.
	 *
	 * @return bool
	 */
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '4.0.0-a1@dev', '>=');
	}
}

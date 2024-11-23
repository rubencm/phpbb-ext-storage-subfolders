<?php

namespace rubencm\storage_subfolders\provider;

use phpbb\storage\provider\provider_interface;

class local_subfolders implements provider_interface
{
	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string
	{
		return 'local_subfolders';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_adapter_class(): string
	{
		return \rubencm\storage_subfolders\adapter\local_subfolders::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options(): array
	{
		return [
			'path' => [
				'tag' => 'input',
				'type' => 'text',
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_available(): bool
	{
		return true;
	}
}

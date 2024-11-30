<?php

namespace rubencm\storage_subfolders\provider;

use phpbb\language\language;
use phpbb\storage\provider\provider_interface;

class local_subfolders implements provider_interface
{
	/**
	 * @var language
	 */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param language $language
	 */
	public function __construct(language $language)
	{
		$this->language = $language;
	}

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
	public function get_title(): string
	{
		return $this->language->lang('STORAGE_ADAPTER_LOCAL_SUBFOLDERS_NAME');
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
				'title' => $this->language->lang('STORAGE_ADAPTER_LOCAL_SUBFOLDERS_OPTION_PATH'),
				'description' => $this->language->lang('STORAGE_ADAPTER_LOCAL_SUBFOLDERS_OPTION_PATH_EXPLAIN'),
				'form_macro' => [
					'tag' => 'input',
					'type' => 'text',
				],
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

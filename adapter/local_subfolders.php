<?php

namespace rubencm\storage_subfolders\adapter;

use phpbb\storage\adapter\adapter_interface;
use phpbb\storage\exception\storage_exception;
use phpbb\filesystem\exception\filesystem_exception;
use phpbb\filesystem\filesystem;
use phpbb\filesystem\helper as filesystem_helper;


class local_subfolders implements adapter_interface
{
	/**
	 * Filesystem component
	 *
	 * @var filesystem
	 */
	protected $filesystem;

	/**
	 * @var string path
	 */
	protected $phpbb_root_path;

	/**
	 * Absolute path to the storage folder
	 * Always finish with DIRECTORY_SEPARATOR
	 * Example:
	 * - /var/www/phpBB/images/avatar/upload/
	 * - C:\phpBB\images\avatars\upload\
	 *
	 * @var string path
	 */
	protected $root_path;

	/*
	 * Subdirectories depth
	 *
	 * Instead of storing all folders in the same directory, they can be divided
	 * into smaller directories. The variable describes the number of subdirectories
	 * to be used for storing the files. For example:
	 * depth = 0 -> /images/avatars/upload/my_avatar.jpg
	 * depth = 2 -> /images/avatars/upload/d9/8c/my_avatar.jpg
	 * This is for those who have problems storing a large number of files in
	 * a single directory.
	 */

	/**
	 * @var int dir_depth
	 */
	protected $dir_depth = 2;

	/**
	 * Constructor
	 *
	 * @param filesystem $filesystem
	 * @param string $phpbb_root_path
	 */
	public function __construct(filesystem $filesystem, string $phpbb_root_path)
	{
		$this->filesystem = $filesystem;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configure(array $options): void
	{
		$this->root_path = filesystem_helper::realpath($this->phpbb_root_path . $options['path']) . DIRECTORY_SEPARATOR;
	}

	/**
	 * {@inheritdoc}
	 */
	public function read(string $path)
	{
		$stream = @fopen($this->root_path . $this->get_path($path) . $this->get_filename($path), 'rb');

		if (!$stream)
		{
			throw new storage_exception('STORAGE_CANNOT_OPEN_FILE', $path);
		}

		return $stream;
	}

	/**
	 * {@inheritdoc}
	 */
	public function write(string $path, $resource): int
	{
		$this->ensure_directory_exists($path);

		$stream = @fopen($this->root_path . $this->get_path($path) . $this->get_filename($path), 'w+b');

		if (!$stream)
		{
			throw new storage_exception('STORAGE_CANNOT_CREATE_FILE', $path);
		}

		if (($size = stream_copy_to_stream($resource, $stream)) === false)
		{
			fclose($stream);
			throw new storage_exception('STORAGE_CANNOT_COPY_RESOURCE');
		}

		fclose($stream);

		return $size;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(string $path): void
	{
		try
		{
			$this->filesystem->remove($this->root_path . $this->get_path($path) . $this->get_filename($path));
		}
		catch (filesystem_exception $e)
		{
			throw new storage_exception('STORAGE_CANNOT_DELETE', $path, array(), $e);
		}

		$this->remove_empty_dirs($path);
	}

	/**
	 * {@inheritdoc}
	 */
	public function free_space(): float
	{
		if (!function_exists('disk_free_space') || ($free_space = @disk_free_space($this->root_path)) === false)
		{
			throw new storage_exception('STORAGE_CANNOT_GET_FREE_SPACE');
		}

		return $free_space;
	}

	/**
	 * Ensures that the directory of a file exists.
	 *
	 * @param string	$path	The file path
	 *
	 * @throws storage_exception	On any directory creation failure
	 */
	protected function ensure_directory_exists(string $path): void
	{
		$directory_path = dirname($this->root_path . $this->get_path($path) . $this->get_filename($path));

		if (!$this->filesystem->exists($directory_path))
		{
			try
			{
				$this->filesystem->mkdir($directory_path);
			}
			catch (filesystem_exception $e)
			{
				throw new storage_exception('STORAGE_CANNOT_CREATE_DIR', $directory_path, array(), $e);
			}
		}
	}

	/**
	 * Removes the directory tree ascending until it finds a non-empty directory.
	 *
	 * @param string	$path	The file path
	 */
	protected function remove_empty_dirs(string $path): void
	{
		$dirpath = dirname($this->root_path . $path);
		$filepath = dirname($this->root_path . $this->get_path($path) . $this->get_filename($path));
		$path = filesystem_helper::make_path_relative($filepath, $dirpath);

		do
		{
			$parts = explode('/', $path);
			$parts = array_slice($parts, 0, -1);
			$path = implode('/', $parts);
		}
		while ($path && @rmdir($dirpath . '/' . $path));
	}

	/**
	 * Get the path to the file, appending subdirectories for directory depth
	 * if $dir_depth > 0.
	 *
	 * @param string $path The file path
	 * @return string
	 */
	protected function get_path(string $path): string
	{
		$dirname = dirname($path);
		$dirname = ($dirname !== '.') ? $dirname . DIRECTORY_SEPARATOR : '';

		$hash = md5($this->get_filename($path));

		$parts = str_split($hash, 2);
		$parts = array_slice($parts, 0, $this->dir_depth);

		if (!empty($parts))
		{
			$dirname .= implode(DIRECTORY_SEPARATOR, $parts) . DIRECTORY_SEPARATOR;
		}

		return $dirname;
	}

	/**
	 * Filename
	 *
	 * @param string $path The file path
	 * @return string
	 */
	protected function get_filename(string $path): string
	{
		return basename($path);
	}

}

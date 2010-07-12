<?php

class Cache_FilePersistent implements Cache
{
	
	private $base_path;
	
	function __construct($base_path = 'data/Cache_FilePersistent')
	{
		$this -> base_path = $base_path;
	}

	function store ($namespace, $key, $value)
	{
		$filepath = $this->filePath ($namespace, $key);
		
		return File_System::file_put_contents ($filepath, serialize ($value));
	}

	function fetch ($namespace, $key)
	{
		$filepath = $this->filePath ($namespace, $key);
		
		if (!file_exists ($filepath))
		{
			return null;
		}
		
		return unserialize (file_get_contents ($filepath));
	}

	function delete ($namespace, $key = null)
	{
		return File_System::remove ($this->filePath ($namespace, $key));
	}

	private function filePath ($namespace, $key)
	{
		if ($key === null)
		{
			return "$this->base_path/$namespace";
		}
		else
		{
			$md5 = md5 ($key);
			return "$this->base_path/$namespace/" . substr($md5, 0, 2).'/'.substr($md5, 2);
		}
	}

}

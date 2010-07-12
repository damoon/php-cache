<?php

class Cache_File implements Cache
{
	
	private $base_path;
	
	function __construct($base_path = 'Cache_File')
	{
		$this -> base_path = $base_path.'/'.PROJECT_ID;
		$this -> maybeRemoveOld($base_path);
	}
	
	private function maybeRemoveOld ($base_path)
	{
		if (rand(0,1000) == 42)
		{
			foreach(glob("$base_path/*") as $maybeOldPath)
			{
				if (basename($maybeOldPath) != PROJECT_ID)
				{
					File_System::remove($maybeOldPath);
				}
			}
		}	
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

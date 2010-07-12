<?php

class Cache_FastAndPersistent implements Cache
{
	
	private $fileCache;
	
	private $apcCache;

	function __construct(Cache_FilePersistent $fileCache, Cache_Apc $apcCache)
	{
		$this -> fileCache = $fileCache;
		$this -> apcCache = $apcCache;
	}
	
	function store ($namespace, $key, $value)
	{
		$apc = $this->apcCache->store($namespace, $key, $value);
		$file = $this->fileCache->store($namespace, $key, $value);
		return $apc && $file;
	}

	function fetch ($namespace, $key)
	{
		$value = $this->apcCache->fetch($namespace, $key);
		
		if ($value)
		{
			return $value;
		}
				
		$value = $this->fileCache->fetch($namespace, $key);
			
		if ($value)
		{
			$this->apcCache->store($namespace, $key, $value);
			return $value;
		}
		
		return null;
		
	}

	function delete ($namespace, $key = null)
	{
		$apc = $this->apcCache->delete($namespace, $key);
		$file = $this->fileCache->delete($namespace, $key);
		return $apc && $file;
	}
	
}

<?php

class Cache_Dynamic implements Cache
{

	private $apcCache;

	private $memcacheCache;

	private $fileCache;

	function __construct (Cache_Apc $apcCache, Cache_Memcache $memcacheCache, Cache_File $fileCache)
	{
		$this->apcCache = $apcCache;
		$this->memcacheCache = $memcacheCache;
		$this->fileCache = $fileCache;
	}

	function store ($namespace, $key, $value)
	{
		$this->storeInEveryStorage($namespace, $key, $value);
	}

	function fetch ($namespace, $key)
	{
		$value = $this->apcCache->fetch($namespace, $key);
		
		if ($value)
		{
			return $value;
		}
		
		$value = $this->memcacheCache->fetch($namespace, $key);
		
		if ($value)
		{
			$this->storeInFastStorage($namespace, $key, $value);
			return $value;
		}
		
		$value = $this->memcacheCache->fetch($namespace, $key);
		
		if ($value)
		{
			$this->storeInModerateAndFastStorage($namespace, $key, $value);
			return $value;
		}
		
		return null;
	
	}

	function delete ($namespace, $key = null)
	{
		$this->apcCache->delete($namespace, $key);
		$this->memcacheCache->delete($namespace, $key);
		$this->fileCache->delete($namespace, $key);
	}

	private function storeInFastStorage ($namespace, $key, $value)
	{
		if (is_scalar($value) && !isset($value{10240}))
		{
			$this->apcCache->store($namespace, $key, $value, 60);
		}
	}

	private function storeInModerateAndFastStorage ($namespace, $key, $value)
	{
		$this->memcacheCache->store($namespace, $key, $value);
		$this->storeInFastStorage($namespace, $key, $value);
	}

	private function storeInEveryStorage ($namespace, $key, $value)
	{
		$this->fileCache->store($namespace, $key, $value);
		$this->storeInModerateAndFastStorage($namespace, $key, $value);
	}

}

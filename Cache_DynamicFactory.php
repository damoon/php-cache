<?php

class Cache_FastAndPersistentFactory
{
	
	static function buildDefault ()
	{
		$fileCache = new Cache_FilePersistent();
		$memCache = new Cache_Memcache();
		$apcCache = new Cache_Apc();
		$cache = new Cache_FastAndPersistent($apcCache, $memCache, $fileCache);
		return $cache;
	}
	
}

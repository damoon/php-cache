<?php

class Cache_FastAndPersistentFactory
{
	
	static function buildDefault ()
	{
		$fileCache = new Cache_FilePersistent();
		$apcCache = new Cache_Apc();
		$cache = new Cache_FastAndPersistent($fileCache, $apcCache);
		return $cache;
	}
	
}

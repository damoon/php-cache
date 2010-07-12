<?php

class Cache_Apc implements Cache
{

	function lock ($namespace, $lock)
	{
		return apc_add (self::prefix ($namespace) . '_lock_' . $lock, '1');
	}

	function unlock ($namespace, $lock)
	{
		return apc_delete (self::prefix ($namespace) . '_lock_' . $lock);
	}

	function store ($namespace, $key, $value)
	{
		return apc_store (self::prefix ($namespace) . '_storeage_' . $key, $value);
	}

	function fetch ($namespace, $key)
	{
		$success = false;
		$value = apc_fetch (self::prefix ($namespace) . '_storeage_' . $key, $success);
		if (!$success)
		{
			return null;
		}
		return $value;
	}

	function delete ($namespace, $key = null)
	{
		if ($key == null)
		{
			$namespaceKey = 'KF_' . PROJECT_ID . '_namespace_' . $namespace;
			$namespaceId = self::getNewNamespaceId ();
			return apc_store ($namespaceKey, $namespaceId);
		}
		else
		{
			return apc_delete (self::prefix ($namespace) . '_storeage_' . $key);
		}
	}

	private static function prefix ($namespace)
	{
		$namespaceKey = 'KF_' . PROJECT_ID . '_namespace_' . $namespace;
		
		$namespaceId = apc_fetch ($namespaceKey);
		
		if (!$namespaceId)
		{
			$namespaceId = self::getNewNamespaceId ();
			apc_store ($namespaceKey, $namespaceId);
		}
		
		return 'KF_' . PROJECT_ID . '_' . $namespaceId;
	}

	private static function getNewNamespaceId ()
	{
		return (isset ($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time ()) . rand (0, 99999);
	}

}

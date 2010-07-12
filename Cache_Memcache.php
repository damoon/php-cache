<?php

class Cache_Memcache implements Cache
{
	
	private $host;
	
	private $port;

	private $memcache;
	
	private $prefixCache;
	
	function __construct($host = 'localhost', $port = 11211)
	{
		$this -> host = $host;
		$this -> port = $port;
	}
	
	function store ($namespace, $key, $value)
	{
		$memcache = $this->getConnection ();
		
		$primary_key = $this->prefix ($namespace) . '_storeage_' . $key;
				
		return $memcache->set ($primary_key, $value, MEMCACHE_COMPRESSED, 0);
	}
	
	function fetch ($namespace, $key)
	{	
		$memcache = $this->getConnection ();
		
		$primary_key = $this->prefix ($namespace) . '_storeage_' . $key;	
		
		$return = $memcache->get ($primary_key, MEMCACHE_COMPRESSED);
		
		return $return ? $return : null;
	}

	function delete ($namespace, $key = null)
	{
		$memcache = $this->getConnection ();
		
		if ($key == null)
		{
			$namespaceId = $this->getNewNamespaceId ();
			$namespaceKey = 'KF_' . PROJECT_ID . '_namespace_' . $namespace;
			unset($this->prefixCache[$namespaceKey]);
			return $memcache->delete ($namespaceKey);
		}
		else
		{
			$primary_key = $this->prefix ($namespace) . '_storeage_' . $key;
			
			return $memcache->delete ($primary_key);
		}
	}

	private function prefix ($namespace)
	{
		$memcache = $this->getConnection ();
		
		$namespaceKey = 'KF_' . PROJECT_ID . '_namespace_' . $namespace;
		
		if (isset($this->prefixCache[$namespaceKey]))
		{
			return $this->prefixCache[$namespaceKey];
		}
		
		$namespaceId = $memcache->get ($namespaceKey, MEMCACHE_COMPRESSED);
		
		if (!$namespaceId)
		{
			$namespaceId = $this->getNewNamespaceId ();
			
			$memcache->set ($namespaceKey, $namespaceId, MEMCACHE_COMPRESSED, 0);
		}
		
		return $this->prefixCache[$namespaceKey] = 'KF_' . PROJECT_ID . '_' . $namespaceId;
	}

	private function getNewNamespaceId ()
	{
		return (isset ($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time ()) . rand (0, 99999);
	}

	private function getConnection ()
	{
		if (!isset ($this->memcache))
		{
			$this->memcache = new Memcache ();
			$this->memcache->pconnect ($this->host, $this->port);
		}
		return $this->memcache;
	}

}

<?php

class Cache_Mock implements Cache
{
	private $cache = array ();
	
	public function store ($namespace, $key, $value)
	{
		if (!isset($this->cache[$namespace]))
		{
			$this->cache[$namespace] = array ();
		}
		$this->cache[$namespace][$key] = $value;
		return true;
	}

	public function fetch ($namespace, $key)
	{
		if (!isset($this->cache[$namespace]) || !isset($this->cache[$namespace][$key]))
		{
			return null;
		}
		return $this->cache[$namespace][$key];
	}

	public function delete ($namespace, $key = null)
	{
		if ($key == null)
		{
			if (isset($this->cache[$namespace]))
			{
				unset($this->cache[$namespace]);
			}
		}
		else
		{
			if (isset($this->cache[$namespace]) && isset($this->cache[$namespace][$key]))
			{
				unset($this->cache[$namespace][$key]);
			}
		}
	}

	
}

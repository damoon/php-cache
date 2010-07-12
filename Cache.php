<?php

interface Cache
{
	
	function store ($namespace, $key, $value);
	
	function fetch ($namespace, $key);

	function delete ($namespace, $key = null);
	
}

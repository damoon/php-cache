<?php

class File_System
{

	static function remove ($path)
	{
		if (!file_exists($path))
		{
			return true;
		}
		
		if (is_link($path))
		{
			unlink ($path);
		}
		
		if (is_file ($path))
		{
			return unlink ($path);
		}
		
		if (is_dir ($path))
		{
			self::truncate($path);			
			return rmdir ($path);
		}
		
		return false;
	}

	static function truncate ($path)
	{
                if (is_file ($path))
                {
                        $fp = fopen ($path, 'w');
                        return fclose ($fp);
                }

                if (is_dir ($path))
                {
                        if (($dir = dir ($path)) !== false)
                        {
                                while (($sub = $dir -> read ()) !== false)
                                {
                                        if ($sub != "." && $sub != "..")
                                        {
                                                if (!self :: remove ($path . "/" . $sub))
                                                {
                                                        return false;
                                                }
                                        }
                                }
                                return $dir -> close ();
                        }
                }

                return false;
        }

	
	static function file_put_contents ($path, $contents, $flags = 0)
	{
		return File_System :: createDirectory (dirname ($path)) && file_put_contents ($path, $contents, $flags);
	}
	
	static function createDirectory ($path)
	{
		if (file_exists ($path) && is_dir($path))
		{
			return true;
		}
		
		if (!file_exists ($path))
		{
			if (self :: createDirectory (dirname ($path)))
			{
				return mkdir ($path);
			}
		}
		
		return false;
	}
	
}

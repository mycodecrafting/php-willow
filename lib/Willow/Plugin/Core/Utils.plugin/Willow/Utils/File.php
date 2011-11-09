<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * File utilities
 */
class Willow_Utils_File
{

    protected $_file;

    public function __construct($file)
    {
        $this->_file = $file;
    }

    public function read()
    {
        return file_get_contents($this->_file);
    }

    public function write($contents, $mode = 0644)
    {
        if (file_put_contents($this->_file, $contents) !== false)
        {
            @chmod($this->_file, $mode);
            return true;
        }

        return false;
    }

    public function append($contents, $mode = 0644)
    {
        if (file_put_contents($this->_file, $contents, FILE_APPEND) !== false)
        {
            @chmod($this->_file, $mode);
            return true;
        }

        return false;
    }

    public function remove()
    {
        if (file_exists($this->_file) === true)
        {
            return @unlink($this->_file);
        }

        return true;
    }

    public function createLink($link)
    {
        return @symlink($this->_file, $link);
    }

}

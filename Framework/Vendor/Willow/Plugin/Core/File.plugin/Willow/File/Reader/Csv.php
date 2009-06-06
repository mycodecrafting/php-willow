<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_File_Reader_Csv extends Willow_File_Reader_Abstract
{

    /**
     * ...
     */
    const ROW_SIZE = 4096;

    /**
     * ...
     */
    protected $_filePointer = null;

    /**
     * ...
     */
    protected $_rowCounter = 0;

    /**
     * ...
     */
    protected $_current = null;

    /**
     * ...
     */
    protected $_delimiter = ',';

    /**
     * ...
     */
    protected $_headers = array();

    /**
     * ...
     */
    public function __construct($file, $delimiter = ',')
    {
        if (($this->_filePointer = @fopen($file, 'r')) === false)
        {
            throw new Willow_File_Exception(sprintf(
                'Unable to open %s for reading', $file
            ));
        }

        $this->_delimiter = $delimiter;

        /**
         * setup row headers
         */
        $this->_headers = fgetcsv($this->_filePointer, self::ROW_SIZE, $this->_delimiter);
    }

    /**
     * ...
     */
    public function getHeader($i)
    {
        if (isset($this->_headers[$i]))
        {
            return $this->_headers[$i];
        }

        return $i;
    }

    /**
     * ...
     */
    public function rewind()
    {
        $this->_rowCounter = 0;
        rewind($this->_filePointer);
    }

    /**
     * ...
     */
    public function current()
    {
        $row = fgetcsv($this->_filePointer, self::ROW_SIZE, $this->_delimiter);

        $this->_current = new Willow_Data_Object();

        foreach ($data as $i => $value)
        {
            $this->_current->set($this->getHeader($i), $value);
        }

        ++$this->_rowCounter;

        return $this->_current;
    }

    /**
     * ...
     */
    public function key()
    {
        return $this->_rowCounter();
    }

    /**
     * ...
     */
    public function next()
    {
        return (feof($this->_filePointer) === false);
    }

    /**
     * ...
     */
    public function valid()
    {
        if ($this->next() === false)
        {
            fclose($this->_filePointer);
            return false;
        }

        return true;
    }

}

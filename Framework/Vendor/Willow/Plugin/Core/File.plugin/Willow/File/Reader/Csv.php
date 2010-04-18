<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Auto-detect line endings when reading CSV
 */
ini_set('auto_detect_line_endings', true);

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
    public function __construct($file, $delimiter = ',', $firstRowIsHeader = true)
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
        if ($firstRowIsHeader === true)
        {
            $this->_headers = fgetcsv($this->_filePointer, self::ROW_SIZE, $this->_delimiter);
        }
    }

    /**
     * ...
     */
    public function getRows($limit = 200)
    {
        $collection = new Willow_Data_Collection();

        $count = ($limit > 0 ? 0 : -1);

        while ($count < $limit && ($row = fgetcsv($this->_filePointer, self::ROW_SIZE, $this->_delimiter)) !== false)
        {
            if ((count($row) === 1) && ($row[0] === null))
            {
                continue;
            }

            $data = $collection->addNew();

            foreach ($row as $i => $column)
            {
                $data->set($this->getHeader($i), $column);
            }

            if ($limit > 0)
            {
                ++$count;
            }
        }

        return $collection;
    }

    /**
     * ...
     */
    public function getHeader($i)
    {
        if (isset($this->_headers[$i]) && ($this->_headers[$i] !== ''))
        {
            return $this->_headers[$i];
        }

        return $i;
    }

    /**
     * ...
     */
    public function hasColumn($name)
    {
        return in_array($name, $this->_headers);
    }

    /**
     * ...
     */
    public function rewind()
    {
        $this->_rowCounter = 0;
        rewind($this->_filePointer);

        /**
         * Pull first row if headers
         */
        if (count($this->_headers))
        {
            fgetcsv($this->_filePointer, self::ROW_SIZE, $this->_delimiter);
        }
    }

    /**
     * ...
     */
    public function current()
    {
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
        ++$this->_rowCounter;
    }

    /**
     * ...
     */
    public function valid()
    {
        if (feof($this->_filePointer) === true)
        {
            fclose($this->_filePointer);
            return false;
        }

        $row = fgetcsv($this->_filePointer, self::ROW_SIZE, $this->_delimiter);

        if ((count($row) === 1) && ($row[0] === null))
        {
            fclose($this->_filePointer);
            return false;
        }

        $this->_current = new Willow_Data_Object();

        foreach ($row as $i => $column)
        {
            $this->_current->set($this->getHeader($i), $column);
        }

        return true;
    }

}

<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Data_Collection implements Countable, IteratorAggregate
{

    /**
     * ...
     */
    protected $_data = array();

    /**
     * ...
     */
    public function add(Willow_Data_Object $object)
    {
        $this->_data[] = $object;
        return $this;
    }

    /**
     * ...
     */
    public function addNew()
    {
        $object = new Willow_Data_Object();
        $this->add($object);
        return $object;
    }

    /**
     * ...
     */
    public function merge(Willow_Data_Collection $collection)
    {
        foreach ($collection as $data)
        {
            $this->add($data);
        }

        return $this;
    }

    /**
     * For IteratorAggregate
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_data);
    }

    /**
     * ...
     */
    public function count()
    {
        return count($this->_data);
    }

}

<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


interface Willow_Registerable_Transient_Interface
{

    /**
     * Register a class under an alias for this instance
     *
     * @param string $alias
     * @param mixed $class
     * @return self
     */
    public function registerTransient($alias, $class);

}

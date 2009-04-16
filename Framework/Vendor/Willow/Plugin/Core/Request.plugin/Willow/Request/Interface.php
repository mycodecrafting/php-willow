<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


interface Willow_Request_Interface
{

    public function getMethod();

    public function getModule();

    public function setModule($module);

    /**
     * Get the current module's section
     */
    public function getSection();

    public function setSection($section);

    public function getAction();

    public function setAction($action);

    /**
     * Get a segment from the URI path
     *
     * @param integer $index Segment index to get
     * @return string
     */
    public function segment($index);

    public function getProtocol();

    public function setProtocol($protocol);

}

<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * 
 */
interface Willow_Actions_Interface
{

    public function run();
    public function doAction();
    public function attachView(Willow_View_Interface $view);
    public function getView();
    public function authenticate();

}

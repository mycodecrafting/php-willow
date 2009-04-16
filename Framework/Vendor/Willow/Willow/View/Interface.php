<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


interface Willow_View_Interface
{

    public function getRequest();
    public function getTemplate();
    public function render();
    public function setValidationError($field, $message);
    public function preGenerate();
    public function generate();

}

<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Load Motif library from Vendors
 */
Willow_Loader::loadFile('Vendor:Motif:Motif', $once = true);

/**
 * Register the Motif engine for use with HTTP (html) protocol requests
 */
Willow_Template::register('http', 'Willow_Template_Adapter_Motif');

/**
 * Register custom <motif:willow:...> tags for use in Willow
 */
Motif_Engine::register('willow:wrapper', 'Willow_Motif_Tag_Compiler_Wrapper');

/**
 * Set the directory to cache compiled templates
 */
Motif_Template::setCompilationDir(
    Willow_Loader::getRealPath('Tmp:Compiled:Motif', $overridable = true, $ext = false)
);

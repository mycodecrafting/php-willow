<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Include Swift
 */
Willow_Loader::loadFile('Vendor:Swift:swift_required');

/**
 * Register Mailer adapter
 */
Willow_Email_Mailer::register('swift', 'Willow_Email_Mailer_Adapter_Swift');

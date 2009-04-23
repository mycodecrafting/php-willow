<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Append include_path to include Doctrine & models
 */
Willow_Loader::appendIncludePath(
    'Vendor:Doctrine', 'App:Models', 'App:Models:generated'
);

/**
 * Register Doctrine autoloader
 */
Willow_Autoloader::register(new Willow_Doctrine_Autoloader());

/**
 * Setup Doctrine attributes
 */
Doctrine_Manager::getInstance()->setAttribute('auto_free_query_objects', true);

/**
 * Get the config registered to the blackboard
 */
$config = Willow_Blackboard::get('config');

/**
 * Setup Doctrine connection
 */
Doctrine_Manager::connection($config->db->connection, 'default');

/**
 * Setup Doctrine collation & character set
 */
Doctrine_Manager::getInstance()->setCollate($config->db->collation);
Doctrine_Manager::getInstance()->setCharset($config->db->charset);

/**
 * Configure Doctrine
 */
Willow_Whiteboard::register(
    'doctrine_config',
    array(
        'data_fixtures_path' => Willow_Loader::getRealPath('App:Doctrine:Data:Fixtures', false, false),
        'models_path'        => Willow_Loader::getRealPath('App:Models', false, false),
        'migrations_path'    => Willow_Loader::getRealPath('App:Doctrine:Migrations', false, false),
        'sql_path'           => Willow_Loader::getRealPath('App:Doctrine:Data:Sql', false, false),
        'yaml_schema_path'   => Willow_Loader::getRealPath('App:Doctrine:Schema', false, false),
    )
);

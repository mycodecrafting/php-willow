<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Doctrine_Session extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('willow_doctrine_session');
        $this->hasColumn('id', 'string', 32, array('primary' => true, 'type' => 'string', 'length' => '32'));
        $this->hasColumn('data', 'string', 3000, array('type' => 'string', 'length' => '3000'));
    }

    public function setUp()
    {
        $timestampable0 = new Doctrine_Template_Timestampable(array('created' => array('name' => 'created'), 'updated' => array('name' => 'updated')));
        $this->actAs($timestampable0);
    }

    public static function fetch($id)
    {
        $query = Doctrine_Query::create()
            ->from('willow_doctrine_session')
            ->where('id = ?', $id);
        return $query->fetchOne();
    }

}

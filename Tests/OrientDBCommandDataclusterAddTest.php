<?php

/**
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @copyright Copyright Anton Terekhov, NetMonsters LLC, 2011
 * @license https://github.com/AntonTerekhov/OrientDB-PHP/blob/master/LICENSE
 * @link https://github.com/AntonTerekhov/OrientDB-PHP
 * @package OrientDB-PHP
 */

require_once 'OrientDB.php';
require_once 'OrientDB_TestCase.php';

/**
 * dataclusterAdd() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBDataclusterAddTest extends OrientDB_TestCase
{

    protected $clusterName;

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
        $this->clusterName = 'testdataclusteradd_' . rand(10, 99);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testDataclusterAddOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $result = $this->db->dataclusterAdd('name', \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);
    }

    public function testDataclusterAddOnConnectedDB()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $result = $this->db->dataclusterAdd($this->clusterName, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);
    }

    public function testDataclusterAddOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $result = $this->db->dataclusterAdd($this->clusterName, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);
    }

    public function testDataclusterAddOnOpenDBAdmin()
    {
        $clusters = $this->db->DBOpen('demo', 'admin', 'admin');
        foreach ($clusters['clusters'] as $cluster) {
            if ($cluster->name === $this->clusterName) {
                $this->db->dataclusterRemove($cluster->id);
            }
        }
        $result = $this->db->dataclusterAdd($this->clusterName, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);
        $this->assertInternalType('integer', $result);
        $this->db->dataclusterRemove($result);
        $result = $this->db->dataclusterAdd($this->clusterName, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_MEMORY);
        $this->assertInternalType('integer', $result);
        $this->db->dataclusterRemove($result);
        $this->assertInternalType('integer', $result);
    }

    public function testDataclusterAddOnOpenDBWriter()
    {
        $clusters = $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $result = $this->db->dataclusterAdd($this->clusterName, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);
    }

    public function testDataclusterAddWithWrongParamCount()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $result = $this->db->dataclusterAdd('name');
    }

    public function testDataclusterAddWithWrongParamType()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $result = $this->db->dataclusterAdd('name', 'INVALID');
    }
}
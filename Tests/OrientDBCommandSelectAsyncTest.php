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
 * selectAsync() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBSelectAsyncTest extends OrientDB_TestCase
{

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testSelectAsyncOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->selectAsync('');
    }

    public function testSelectAsyncOnConnectedDB()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->selectAsync('');
    }

    public function testSelectAsyncOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->selectAsync('');
    }

    public function testSelectAsyncOnOpenDB()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->selectAsync('select * from city limit 7');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
    }

    public function testSelectAsyncWithWrongOptionCount()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $record = $this->db->selectAsync();
    }

    public function testSelectAsyncWithFetchPlan()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->selectAsync('select from city limit 20', '*:-1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
    }

    public function testSelectAsyncWithNoRecords()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->selectAsync('select from 11:4 where any() traverse(0,10) (address.city = "Rome")');
        $this->assertFalse($records);
    }
}
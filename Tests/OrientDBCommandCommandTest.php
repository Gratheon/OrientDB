<?php

/**
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @copyright Copyright Anton Terekhov, NetMonsters LLC, 2011
 * @license https://github.com/AntonTerekhov/OrientDB-PHP/blob/master/LICENSE
 * @link https://github.com/AntonTerekhov/OrientDB-PHP
 * @package OrientDB-PHP
 */

//require_once 'OrientDB.php';
require_once 'OrientDB_TestCase.php';

/**
 * command() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBCommandTest extends OrientDB_TestCase
{

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testCommandOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->command('');
    }

    public function testCommandOnConnectedDB()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->command('');
    }

    public function testCommandOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->command('');
    }



	public function testCommandInsert()
	 {
		 $this->db->DBOpen('demo', 'writer', 'writer');
		 $result = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'insert into city (name, country) values ("Moscow", #14:1)');
		 $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', $result);
	 }


	/**
	 * @depends testCommandInsert
	 */
	public function testCommandOnOpenDB()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select * from city limit 7');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
    }

    public function testCommandWithWrongOptionCountOne()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $record = $this->db->command();
    }

    public function testCommandWithWrongOptionCountTwo()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $record = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY);
    }

    public function testCommandWithWrongMode()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $record = $this->db->command('INVALID', '');
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsync()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 20');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanAnyDepthUnlimited()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 20', '*:-1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->assertGreaterThan(0, count($this->db->cachedRecords));
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanAnyDepthOne()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 20', '*:1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->assertGreaterThan(0, count($this->db->cachedRecords));
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanFieldDepthUnlimited()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 20', 'country:-1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->assertGreaterThan(0, count($this->db->cachedRecords));
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanFieldDepthOne()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 20', 'country:1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->assertGreaterThan(0, count($this->db->cachedRecords));
    }

    public function testCommandWithModeSync()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_SYNC, 'select * from [18:1]');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithNoRecordsAsync()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from 11:4 where any() traverse(0,10) (address.city = "Rome")');
        $this->assertFalse($records);
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithNoRecordsSync()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_SYNC, 'select from 11:4 where any() traverse(0,10) (address.city = "Rome")');
        $this->assertFalse($records);
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanEmpty()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->assertEmpty($this->db->cachedRecords);
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 1', '*:0');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->assertEmpty($this->db->cachedRecords);
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanOneItem()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->assertEmpty($this->db->cachedRecords);
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 1', '*:1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->AssertSame(1, count($this->db->cachedRecords));
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($this->db->cachedRecords));
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 1');
        $this->assertEmpty($this->db->cachedRecords);
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeAsyncAndFetchPlanManyItems()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->assertEmpty($this->db->cachedRecords);
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city', '*:1');
        $this->assertInternalType('array', $records);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($records));
        $this->assertGreaterThan(1, count($this->db->cachedRecords));
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($this->db->cachedRecords));
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from city limit 1');
        $this->assertEmpty($this->db->cachedRecords);
    }

    public function testCommandWithModeAsyncAndFetchPlanIncorrect()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $record = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_ASYNC, 'select from 13:0', 'INVALID');
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandWithModeSyncAndFetchPlan()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->assertEmpty($this->db->cachedRecords);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_SELECT_SYNC, 'select from city limit 1', '*:1');
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandUpdate()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $record = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'update city set name = "Moscow_" where name = "Moscow"');
        $this->assertInternalType('string', $record);
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandUpdateZero()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $record = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'update city set name = "_" where name = "' . microtime(true) . '"');
        $this->assertInternalType('string', $record);
    }

    public function testCommandFindReference()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $links = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'find references 14:1');
        $this->assertInternalType('array', $links);
        $this->assertInstanceOf('\Gratheon\OrientDB\OrientDBRecord', array_pop($links));
    }

    /**
     * Test 'n' type answer
     */
    public function testCommandCreateIndex()
    {
        $className = 'Foo_' . rand(1000, 9999);
        $propertyName = 'Bar';
        $clusterName = 'testclusterindex_' . rand(10, 99);
        $this->db->DBOpen('demo', 'admin', 'admin');
        $clusterID = $this->db->dataclusterAdd($clusterName, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);

        $classID = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'CREATE CLASS ' . $className . ' ' . $clusterID);
        $this->assertInternalType('string', $classID);
        $propertyResult = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'CREATE PROPERTY ' . $className . '.' . $propertyName . ' INTEGER');
        $this->assertSame('1', $propertyResult);
        $indexResult = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'CREATE INDEX ' . $className . '.' . $propertyName . ' UNIQUE');
        $this->assertSame('0l', $indexResult); //seems to be 0 Long
        $dropResult = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'DROP CLASS ' . $className);
        $this->assertSame('true', $dropResult);

        $this->db->dataclusterRemove($clusterID);
    }


	/**
	 * @depends testCommandInsert
	 */
    public function testCommandDelete()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $record = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'delete from city where name = "moscow_"');
        $this->assertInternalType('string', $record);
    }

	/**
	 * @depends testCommandInsert
	 */
    public function testCommandDeleteZero()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $record = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, 'delete from city where name = "' . microtime(true) . '"');
        $this->assertInternalType('string', $record);
    }

    public function testCommandWithModeQueryAndFetchPlan()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $records = $this->db->command(\Gratheon\OrientDB\OrientDB::COMMAND_QUERY, '', '*:-1');
    }
}
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
 * shutdown() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBShutdownTest extends OrientDB_TestCase
{

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testShutdownOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $value = $this->db->shutdown('', '');
    }

    public function testshutdownOnConnectedDB()
    {
        $this->markTestSkipped('Skipping shutdown');
        $this->db->connect('root', $this->root_password);
        $this->db->shutdown('root', $this->root_password);
    }

    public function testshutdownOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $value = $this->db->shutdown('', '');
    }

    public function testshutdownOnOpenDB()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $value = $this->db->shutdown('', '');
    }

    public function testshutdownWithWrongPermissions()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $value = $this->db->shutdown('root', '');
    }

    public function testshutdownWithWrongOptionCount()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $value = $this->db->shutdown();
    }
}
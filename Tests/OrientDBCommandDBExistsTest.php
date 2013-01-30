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
 * DBExists() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBDBExistsTest extends OrientDB_TestCase
{

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testDBExistsOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $result = $this->db->DBExists('demo');
    }

    public function testDBExistsOnConnectedDB()
    {
        $this->db->connect('root', $this->root_password);
        $result = $this->db->DBExists('demo');

        $this->assertTrue($result);

        $result = $this->db->DBExists('INVALID');
        $this->assertFalse($result);
    }

    public function testDBExistsOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $result = $this->db->DBExists('demo');
    }

    public function testDBExistsOnOpenDB()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $result = $this->db->DBExists('demo');
    }

    public function testDBExistsWithWrongOptionCount()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $result = $this->db->DBExists();
    }
}
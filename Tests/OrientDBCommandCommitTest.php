<?php

/**
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @copyright Copyright Anton Terekhov, NetMonsters LLC, 2011
 * @license https://github.com/AntonTerekhov/OrientDB-PHP/blob/master/LICENSE
 * @link https://github.com/AntonTerekhov/OrientDB-PHP
 * @package OrientDB-PHP
 */

require_once 'OrientDB_TestCase.php';

/**
 * commit() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBCommitTest extends OrientDB_TestCase
{

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testCommitOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->commit();
    }

    public function testCommitOnConnectedDB()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->commit();
    }

    public function testCommitOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->commit();
    }

    public function testCommitOnOpenDB()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException', 'Not implemented');
        $recordPos = $this->db->commit();
    }

}
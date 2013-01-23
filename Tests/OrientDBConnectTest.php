<?php

/**
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @copyright Copyright Anton Terekhov, NetMonsters LLC, 2011
 * @license https://github.com/AntonTerekhov/OrientDB-PHP/blob/master/LICENSE
 * @link https://github.com/AntonTerekhov/OrientDB-PHP
 * @package OrientDB-PHP
 */

require_once 'OrientDB.php';

/**
 * Socket test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBConnectTest extends PHPUnit_Framework_TestCase
{

    public function testNewFailedConnection()
    {
        $this->setExpectedException('OrientDBConnectException');
        $db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2000);
    }

    public function testNewSucsessfullConnection()
    {
        $db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
        $this->assertInstanceOf('OrientDB', $db);
    }
}


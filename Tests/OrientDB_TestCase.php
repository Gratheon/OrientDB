<?php
/**
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @copyright Copyright Anton Terekhov, NetMonsters LLC, 2011
 * @license https://github.com/AntonTerekhov/OrientDB-PHP/blob/master/LICENSE
 * @link https://github.com/AntonTerekhov/OrientDB-PHP
 * @package OrientDB-PHP
 */

/**
 * Main class in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 *
 * @property \Gratheon\OrientDB\OrientDB $db
 */
abstract class OrientDB_TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * Correct password for root can be found at
     * config/orientdb-server-config.xml in your OrientDB installation
     * @var string
     */
    protected $root_password = ORIENTDB_ROOTPASSWORD;

    /**
     * Instance of OrientDB-PHP
     * @var OrientDB
     */
    protected $db;
}
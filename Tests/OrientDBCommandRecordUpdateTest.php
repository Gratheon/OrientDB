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
 * recordUpdate() test in OrientDB tests
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Tests
 */
class OrientDBRecordUpdateTest extends OrientDB_TestCase
{

    protected $clusterID = 2;

    protected $recordContent = 'testrecord:0';

    protected $recordContentUpd = 'testrecord:1';

    protected function setUp()
    {
        $this->db = new \Gratheon\OrientDB\OrientDB(ORIENTDB_SERVER, 2424);
    }

    protected function tearDown()
    {
        $this->db = null;
    }

    public function testRecordUpdateOnNotConnectedDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->recordUpdate();
    }

    public function testRecordUpdateOnConnectedDB()
    {
        $this->db->connect('root', $this->root_password);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->recordUpdate();
    }

    public function testRecordUpdateOnNotOpenDB()
    {
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongCommandException');
        $list = $this->db->recordUpdate();
    }

    public function testRecordUpdateOnOpenDB()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);
        $record1 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd);
        $this->AssertSame($record1->version + 1, $version);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record2->version);
        $this->AssertSame($this->recordContentUpd, $record2->content);
        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithWrongOptionCount()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $record = $this->db->recordUpdate($this->clusterID);
    }

    public function testRecordUpdateWithWrongRecordIDOne()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $record = $this->db->recordUpdate('INVALID', $this->recordContent);
    }

    public function testRecordUpdateWithWrongRecordIDTwo()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $record = $this->db->recordUpdate(':INVALID', $this->recordContent);
    }

    public function testRecordUpdateWithWrongRecordIDThree()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $record = $this->db->recordUpdate('INVALID:', $this->recordContent);
    }

    public function testRecordUpdateWithWrongRecordIDFour()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $record = $this->db->recordUpdate('1:INVALID', $this->recordContent);
    }

    public function testRecordUpdateWithRecordPosZero()
    {
        $cluster_name = 'recordposzero_' . rand(10, 99);
        $content = 'value:"testrecord"';
        $recordPos = 0;
        $this->db->DBOpen('demo', 'admin', 'admin');
        $cluster_id = $this->db->dataclusterAdd($cluster_name, \Gratheon\OrientDB\OrientDB::DATACLUSTER_TYPE_PHYSICAL);
        $this->assertInternalType('integer', $cluster_id);
        $pos = $this->db->recordCreate($cluster_id, $content);
        $this->assertSame(0, $pos);
        $record = $this->db->recordLoad($cluster_id . ':' . $pos);
        $version = $this->db->recordUpdate($cluster_id . ':' . $pos, $content);
        $record2 = $this->db->recordLoad($cluster_id . ':' . $pos);
        $this->AssertSame($version, $record2->version);
        $this->assertGreaterThan(0, $version);
        $this->db->dataclusterRemove($cluster_id);
    }

    public function testRecordUpdateWithRecordNotExist()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);
        $result = $this->db->recordDelete($this->clusterID . ':' . $recordPos);
        $this->assertTrue($result);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd);
    }

    public function testRecordUpdateWithSameType()
    {
        $this->db->DBOpen('demo', 'admin', 'admin');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_BYTES);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd, -1, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_BYTES);
        $this->assertInternalType('integer', $version);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record2->version);
        $this->AssertSame($this->recordContentUpd, $record2->content);
        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithTypeBytes()
    {
        $this->db->DBOpen('demo', 'admin', 'admin');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_DOCUMENT);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd, -1, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_BYTES);
        $this->assertInternalType('integer', $version);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record2->version);
        $this->AssertSame($this->recordContentUpd, $record2->content);
        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithTypeDocument()
    {
        $this->db->DBOpen('demo', 'admin', 'admin');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_DOCUMENT);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd, -1, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_DOCUMENT);
        $this->assertInternalType('integer', $version);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record2->version);
        $this->AssertSame($this->recordContentUpd, $record2->content);
        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithTypeFlat()
    {
        $this->db->DBOpen('demo', 'admin', 'admin');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_DOCUMENT);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd, -1, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_FLAT);
        $this->assertInternalType('integer', $version);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record2->version);
        $this->AssertSame($this->recordContentUpd, $record2->content);
        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithWrongType()
    {
        $this->db->DBOpen('demo', 'admin', 'admin');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent, \Gratheon\OrientDB\OrientDB::RECORD_TYPE_DOCUMENT);
        $this->assertInternalType('integer', $recordPos);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBWrongParamsException');
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd, 0, '!');
        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithPessimisticVersion()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd);
        $record = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record->version);
        $this->AssertSame(1, $version);
        $this->AssertSame($this->recordContentUpd, $record->content);
        $version2 = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContent, -1);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version2, $record2->version);
        $this->AssertSame(2, $version2);
        $this->AssertSame($this->recordContent, $record2->content);
        $result = $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithCorrectVersion()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd);
        $record = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record->version);
        $this->AssertSame($this->recordContentUpd, $record->content);
        $version2 = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContent, $version);
        $record2 = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version2, $record2->version);
        $this->AssertSame($this->recordContent, $record2->content);
        $result = $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }

    public function testRecordUpdateWithIncorrectVersionIsGreater()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd);
        $record = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record->version);
        $this->AssertSame($this->recordContentUpd, $record->content);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $version2 = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContent, $version + 1);
    }

    public function testRecordUpdateWithIncorrectVersionIsLesser()
    {
        $this->db->DBOpen('demo', 'writer', 'writer');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);
        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContentUpd);
        $record = $this->db->recordLoad($this->clusterID . ':' . $recordPos, '');
        $this->AssertSame($version, $record->version);
        $this->AssertSame(1, $version);
        $this->AssertSame($this->recordContentUpd, $record->content);
        $this->setExpectedException('\Gratheon\OrientDB\OrientDBException');
        $version2 = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $this->recordContent, $version - 1);
    }

    public function testRecordUpdateWithOrientDBRecordType()
    {
        $this->db->DBOpen('demo', 'admin', 'admin');
        $recordPos = $this->db->recordCreate($this->clusterID, $this->recordContent);
        $this->assertInternalType('integer', $recordPos);

        $record = new \Gratheon\OrientDB\OrientDBRecord();
        $record->data->field = 'value';
        $this->assertNull($record->recordPos);
        $this->assertNull($record->clusterID);
        $this->assertNull($record->recordID);
        $this->assertNull($record->version);

        $version = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $record);

        $this->assertInternalType('integer', $version);
        $this->assertSame($recordPos, $record->recordPos);
        $this->assertSame($this->clusterID, $record->clusterID);
        $this->assertSame($this->clusterID . ':' . $recordPos, $record->recordID);
        $this->assertSame(1, $version);
        $this->assertSame($version, $record->version);

        $record->content = $this->recordContent;

        $version2 = $this->db->recordUpdate($this->clusterID . ':' . $recordPos, $record);
        $this->assertSame(2, $record->version);

        $this->db->recordDelete($this->clusterID . ':' . $recordPos);
    }
}
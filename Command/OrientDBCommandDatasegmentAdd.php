<?php
namespace Gratheon\OrientDB\Command;

/**
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @copyright Copyright Anton Terekhov, NetMonsters LLC, 2012
 * @license https://github.com/AntonTerekhov/OrientDB-PHP/blob/master/LICENSE
 * @link https://github.com/AntonTerekhov/OrientDB-PHP
 * @package OrientDB-PHP
 */

/**
 * datasegmentAdd() command for OrientDB-PHP
 *
 * @author Anton Terekhov <anton@netmonsters.ru>
 * @package OrientDB-PHP
 * @subpackage Command
 */
class OrientDBCommandDatasegmentAdd extends OrientDBCommandAbstract
{

    public function __construct($parent)
    {
        parent::__construct($parent);
        $this->opType = OrientDBCommandAbstract::DATASEGMENT_ADD;
    }

    public function prepare()
    {
        parent::prepare();
        throw new \Gratheon\OrientDB\OrientDBException('Not implemented');
    }

    protected function parseResponse()
    {

    }
}
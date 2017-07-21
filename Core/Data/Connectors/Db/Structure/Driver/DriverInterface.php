<?php
namespace Core\Data\Connectors\Db\Structure\Driver;

use Core\Data\Connectors\Db\Db;
use Core\Data\Connectors\Db\Structure\Table\Column\ColumnlistInterface;
use Core\Data\Connectors\Db\Structure\Table\Index\IndexlistInterface;


/**
 * DriverInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface DriverInterface
{

    /**
     * Constructor
     *
     * @param Db $db
     */
    public function __construct(Db $db);

    /**
     * Sets the name of table to query
     *
     * @param string $table
     */
    public function setTable(string $table);

    /**
     * Loads and returns a columnlist
     *
     * @param string $table
     *
     * @return ColumnlistInterface
     */
    public function loadColumns(): ColumnlistInterface;

    /**
     * loads and returns tables indexlist
     */
    public function loadIndexes(): IndexlistInterface;
}



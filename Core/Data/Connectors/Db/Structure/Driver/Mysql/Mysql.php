<?php
namespace Core\Data\Connectors\Db\Structure\Driver\Mysql;

use Core\Data\Connectors\Db\Structure\Driver\DriverInterface;
use Core\Data\Connectors\Db\Db;
use Core\Data\Connectors\Db\Structure\Table\Column\ColumnlistInterface;
use Core\Data\Connectors\Db\Structure\Table\Table;
use Core\Data\Connectors\Db\Structure\Table\Index\IndexlistInterface;
use Core\Data\Connectors\Db\Structure\Table\Index\Indexlist;
use Core\Data\Connectors\Db\Structure\Table\Index\Index;
use Core\Data\Connectors\Db\Structure\Table\Column\Columnlist;
use Core\Data\Connectors\Db\Structure\Table\Column\Column;

/**
 * Mysql.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Mysql implements DriverInterface
{

    const INDEX_PRIMARY = 'primary';
    const INDEX_UNIQUE = 'unique';
    const INDEX_FULLTEXT = 'fulltext';
    const INDEX_INDEX = 'index';

    /**
     *
     * @var Db
     */
    private $db;

    /**
     *
     * @var string
     */
    private $table;

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Mysql\DriverInterface::setTable($table)
     */
    public function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Mysql\DriverInterface::__construct($db)
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Mysql\DriverInterface::loadColumns()
     *
     */
    public function loadColumns(): ColumnlistInterface
    {
        $this->db->sql('SHOW FIELDS FROM `' . $this->table . '`');

        $result = $this->db->all();

        $columnlist = new Columnlist();

        foreach ($result as $row) {

            $name = $row['Field'];
            $null = $row['Null'] != 'YES' ? false : true;
            $default = isset($row['Default']) ? $row['Default'] : null;
            $unsigned = false;

            // Is column auto increment?
            $auto = strpos($row['Extra'], 'auto_increment') !== false ? true : false;

            $matches = [];

            // Size of field?
            if (preg_match('~(.+?)\s*\((\d+)\)(?:(?:\s*)?(unsigned))?~i', $row['Type'], $matches) === 1) {

                $type = $matches[1];
                $size = $matches[2];

                if (!empty($matches[3]) && $matches[3] == 'unsigned') {
                    $unsigned = true;
                }
            }
            else {
                $type = $row['Type'];
                $size = null;
            }



            $column = new Column();
            $column->setName($name);
            $column->setType($type);
            $column->setSize($size);
            $column->setDefault($default);



            $this->addColumn($name, $null, $default, $type, $size, $auto, $unsigned);
        }

        return $columnlist;

    }



    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Db\TableInfoInterface::listTblIndexes()
     */
    public function loadIndexes($tbl): IndexlistInterface
    {
        $this->db->sql('SHOW KEYS FROM `' . $tbl . '`');
        $result = $this->db->all();

        $indexlist = new Indexlist();

        foreach ($result as $row) {

            $name = $row['Key_name'];

            // What is the type?
            if ($row['Key_name'] == 'PRIMARY') {
                $type = 'primary';
            }
            elseif (empty($row['Non_unique'])) {
                $type = 'unique';
            }
            elseif (isset($row['Index_type']) && $row['Index_type'] == 'FULLTEXT') {
                $type = 'fulltext';
            }
            else {
                $type = 'index';
            }

            $index = new Index();
            $index->setName($name);
            $index->setType($type);

            $indexlist->setIndex($index);

            // Is it a partial index?
            $column = !empty($row['Sub_part']) ? $row['Column_name'] . '(' . $row['Sub_part'] . ')' : $row['Column_name'];

            var_dump($column);

        }

        return $indexlist;
    }
}


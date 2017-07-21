<?php
namespace Core\Data\Connectors\Db\Structure\Table;

use Core\Data\Connectors\Db\Db;
use Core\Data\Connectors\Db\Structure\Table\Column\ColumnlistInterface;
use Core\Data\Connectors\Db\Structure\Table\Column\Columnlist;
use Core\Data\Connectors\Db\Structure\Table\Index\IndexlistInterface;
use Core\Data\Connectors\Db\Structure\Table\Index\Indexlist;
use Core\Data\Connectors\Db\Structure\Driver\DriverInterface;
use Core\Data\Connectors\Db\Structure\Driver\Driver;

/**
 * Table.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Table implements TableInterface
{

    /**
     *
     * @var string
     */
    private $name = 'table';

    /**
     *
     * @var string
     */
    private $type = 'innodb';

    /**
     *
     * @var string
     */
    private $comment;

    /**
     *
     * @var string
     */
    private $coalition;

    /**
     *
     * @var DriverInterface
     */
    private $driver;

    /**
     *
     * @var ColumnlistInterface
     */
    public $columns;

    /**
     *
     * @var IndexlistInterface
     */
    public $indexes;

    /**
     *
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;


        $this->name .= '_' . uniqid();


        $this->driver = Driver::load($db);
        $this->columns = new Columnlist();
        $this->indexes = new Indexlist();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\TableInterface::setName()
     */
    public function setName(string $name)
    {
        if (empty($name)) {
            Throw new TableException('An empty table name is not allowed.');
        }

        $this->name = $name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\TableInterface::getName()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\TableInterface::setType()
     */
    public function setType(string $type)
    {
        if (empty($type)) {
            Throw new TableException('An empty table type is not allowed.');
        }

        $this->type = $type;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\TableInterface::getType()
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\TableInterface::setComment()
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\TableInterface::getComment()
     */
    public function getComment(): string
    {
        return $this->comment ?? '';
    }

    public function read()
    {
        $this->columns = $this->driver->loadColumns();
        $this->indexes = $this->driver->loadIndexes();
    }

    public function update()
    {

    }

    public function create()
    {

    }

    public function delete()
    {

    }
}


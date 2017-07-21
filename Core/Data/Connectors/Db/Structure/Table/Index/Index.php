<?php
namespace Core\Data\Connectors\Db\Structure\Table\Index;

use Core\Data\Connectors\Db\Structure\Table\Column\Columnlist;
use Core\Data\Connectors\Db\Structure\Table\Column\ColumnlistInterface;

/**
 * Index.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Index implements IndexInterface
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $type;

    /**
     *
     * @var string
     */
    private $comment;

    /**
     *
     * @var ColumnlistInterface
     */
    public $columns;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name = uniqid('index_');
        $this->columns = new Columnlist();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexInterface::setName()
     */
    public function setName(string $name)
    {
        if (empty($name)) {
            Throw new IndexException('Empty indexnames are not allowed.');
        }

        $this->name = $name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexInterface::getName()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexInterface::setType()
     */
    public function setType(string $type)
    {
        if (empty($type)) {
            Throw new IndexException('Empty indextype is not allowed.');
        }

        $this->type = $type;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexInterface::getType()
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexInterface::setComment()
     *
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexInterface::getComment()
     *
     */
    public function getComment(): string
    {
        return $this->comment ?? '';
    }
}


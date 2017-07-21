<?php
namespace Core\Data\Connectors\Db\Structure\Table\Index;

/**
 * Indexlist.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Indexlist implements IndexlistInterface
{

    /**
     *
     * @var array
     */
    private $indexes = [];

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexlistInterface::setIndex()
     *
     */
    public function setIndex(IndexInterface $index)
    {
        $this->indexes[$index->getName()] = $index;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexlistInterface::getIndexs()
     *
     */
    public function getIndexes(): array
    {
        return $this->indexes;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Index\IndexlistInterface::getIndex()
     *
     */
    public function getIndex(string $name)
    {
        if (!isset($this->indexes[$name])) {
            Throw new IndexException(sprintf('A field named "%s" does not exist in this table', $name));
        }

        return $this->indexes[$name];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\IndexlistInterface::removeIndex()
     *
     */
    public function removeIndex(string $name)
    {
        if (!isset($this->indexes[$name])) {
            Throw new IndexException(sprintf('A field named "%s" does not exist in this table', $name));
        }

        unset($this->indexes['name']);
    }
}


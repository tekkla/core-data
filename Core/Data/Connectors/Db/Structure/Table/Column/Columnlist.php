<?php
namespace Core\Data\Connectors\Db\Structure\Table\Column;

/**
 * Columnlist.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Columnlist implements ColumnlistInterface
{

    /**
     *
     * @var array
     */
    private $fields = [];

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\ColumnlistInterface::setColumn()
     *
     */
    public function setColumn(ColumnInterface $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\ColumnlistInterface::getColumns()
     *
     */
    public function getColumns(): array
    {
        return $this->fields;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\ColumnlistInterface::getColumn()
     *
     */
    public function getColumn(string $name)
    {
        if (!isset($this->fields[$name])) {
            Throw new ColumnException(sprintf('A field named "%s" does not exist in this table', $name));
        }

        return $this->fields[$name];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\ColumnlistInterface::removeColumn()
     *
     */
    public function removeColumn(string $name)
    {
        if (!isset($this->fields[$name])) {
            Throw new ColumnException(sprintf('A field named "%s" does not exist in this table', $name));
        }

        unset($this->fields['name']);
    }
}


<?php
namespace Core\Data\Connectors\Db\Structure\Table\Column;

/**
 * ColumnlistInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface ColumnlistInterface
{

    /**
     * Sets a field to the fieldlist
     *
     * @param ColumnInterface $field
     */
    public function setColumn(ColumnInterface $field);

    /**
     * Removes a named field from the fieldlist
     *
     * @param string $name
     */
    public function removeColumn(string $name);

    /**
     * Returns a specific field by its name
     *
     * @param string $name
     */
    public function getColumn(string $name);

    /**
     * Returns an array of all existing fields
     *
     * @return array
     */
    public function getColumns(): array;
}


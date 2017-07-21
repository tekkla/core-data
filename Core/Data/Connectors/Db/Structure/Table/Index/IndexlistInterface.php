<?php
namespace Core\Data\Connectors\Db\Structure\Table\Index;

/**
 * ColumnlistInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface IndexlistInterface
{

    /**
     * Sets an index to the indexlist
     *
     * @param IndexInterface $index
     */
    public function setIndex(IndexInterface $index);

    /**
     * Removes a named index from the fieldlist
     *
     * @param string $name
     */
    public function removeIndex(string $name);

    /**
     * Returns a specific index by its name
     *
     * @param string $name
     */
    public function getIndex(string $name);

    /**
     * Returns an array of all existing indexes in this list
     *
     * @return array
     */
    public function getIndexes(): array;
}


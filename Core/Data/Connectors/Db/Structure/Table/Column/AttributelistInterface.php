<?php
namespace Core\Data\Connectors\Db\Structure\Table\Column;

/**
 * AttributelistInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface AttributelistInterface
{

    /**
     * Sets a table attribute
     *
     * @param string $attribute
     */
    public function setAttribute(string $attribute);

    /**
     * Removes a table attribute
     *
     * @param string $attribute
     */
    public function removeAttribute(string $attribute);

    /**
     * Returns a list of all attributes
     *
     * @return array
     */
    public function getAttributes(): array;
}


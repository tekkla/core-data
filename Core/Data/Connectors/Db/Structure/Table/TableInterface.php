<?php
namespace Core\Data\Connectors\Db\Structure\Table;

use Core\Data\Connectors\Db\Db;

/**
 * TableInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface TableInterface
{

    /**
     * Sets the tables name
     *
     * @param string $name
     */
    public function setName(string $name);

    /**
     * Returns tables name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets storagetype that may is used by the database for a table
     *
     * @param string $type
     */
    public function setType(string $type);

    /**
     * Returns set storagetype
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set table comment
     *
     * @param string $comment
     */
    public function setComment(string $comment);

    /**
     * Returns table comment
     *
     * @return string
     */
    public function getComment(): string;
}


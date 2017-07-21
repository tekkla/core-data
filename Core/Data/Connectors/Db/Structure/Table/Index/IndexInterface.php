<?php
namespace Core\Data\Connectors\Db\Structure\Table\Index;

/**
 * IndexInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface IndexInterface
{

    /**
     * Sets index name
     *
     * @param string $name
     */
    public function setName(string $name);

    /**
     * Returns index name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets index type
     *
     * @param string $type
     */
    public function setType(string $type);

    /**
     * Returns index type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Sets index comment
     *
     * @param string $comment
     */
    public function setComment(string $comment);

    /**
     * Returns set comment
     *
     * @return string
     */
    public function getComment(): string;
}

<?php
namespace Core\Data\Connectors\Db\Structure\Table\Column;

/**
 * ColumnInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
interface ColumnInterface
{
    /**
     * Sets the name of field
     *
     * @param string $name
     */
    public function setName(string $name);

    /**
     * Returns name of field
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets type of field
     *
     * @param string $type
     */
    public function setType(string $type);

    /**
     * Returns type of field
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Sets size of field
     *
     * @param int $size
     */
    public function setSize(int $size);

    /**
     * Returns size of field
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Sets field comment
     *
     * @param string $comment
     */
    public function setComment(string $comment);

    /**
     * Returns field comment
     *
     * @return string
     */
    public function getComment(): string;

    /**
     * Sets default value
     *
     * @param string $default
     */
    public function setDefault(string $default);

    /**
     * Returns default value
     *
     * @return string
     */
    public function getDefault(): string;

}


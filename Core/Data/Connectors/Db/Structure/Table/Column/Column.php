<?php
namespace Core\Data\Connectors\Db\Structure\Table\Column;

/**
 * Column.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Column implements ColumnInterface
{

    /**
     *
     * @var string
     */
    private $name = 'field_';

    /**
     *
     * @var string
     */
    private $type = 'varchar';

    /**
     *
     * @var int
     */
    private $size = 255;

    /**
     *
     * @var string
     */
    private $default;

    /**
     *
     * @var string
     */
    private $comment;

    /**
     *
     * @var AttributelistInterface
     */
    public $attributes;

    /**
     * Contructor
     *
     * Creates a unique default fieldname
     */
    public function __construct()
    {
        $this->name = $this->name . uniqid();

        $this->attributes = new Attributelist();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::setName()
     */
    public function setName(string $name)
    {
        if (empty($name)) {
            Throw new ColumnException('Empty fieldnames are not allowed.');
        }

        $this->name = $name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::getName()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::setType()
     */
    public function setType(string $type)
    {
        if (empty($type)) {
            Throw new ColumnException('Empty fieldtype is not allowed.');
        }

        $this->type = $type;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::getType()
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::setSize()
     */
    public function setSize(int $size)
    {
        if (empty($size)) {
            Throw new ColumnException('Empty fielsizes are not allowed.');
        }

        $this->size = $size;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::getSize()
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::getComment()
     */
    public function getComment(): string
    {
        return $this->comment ?? '';
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::setComment()
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::setDefault()
     */
    public function setDefault(string $default)
    {
        if (empty($default)) {
            Throw new ColumnException('Empty default value is not allowed.');
        }

        $this->default = $default;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\ColumnInterface::getDefault()
     */
    public function getDefault(): string
    {
        return $this->default ?? 'null';
    }
}


<?php
namespace Core\Data\Connectors\Db\Structure\Table\Column;

/**
 * Attributelist.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Attributelist implements AttributelistInterface
{

    /**
     *
     * @var array
     */
    private $attributes = [];

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\AttributelistInterface::setAttribute()
     *
     */
    public function setAttribute(string $attribute)
    {
        if (empty($attribute)) {
            Throw new ColumnException('An empty attribute is not allowed.');
        }

        $this->attributes[$attribute] = $attribute;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\AttributelistInterface::getAttributes()
     *
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Core\Data\Connectors\Db\Structure\Table\Column\AttributelistInterface::removeAttribute()
     *
     */
    public function removeAttribute(string $attribute)
    {
        if (empty($attribute)) {
            Throw new ColumnException('An empty attribute is not allowed.');
        }

        unset($this->attributes[$attribute]);
    }
}


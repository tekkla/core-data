<?php
namespace Core\Data;

/**
 * DataObject.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016-2017
 * @license MIT
 */
class DataObject implements DataObjectInterface, \ArrayAccess
{

    public function __get($key)
    {
        return 'DataObject::' . $key;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        if (!property_exists($this, $offset)) {
            return 'DataObject::' . $key;
        }
        
        return $this->{$offset};
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        if (property_exists($this, $offset)) {
            unset($this->{$offset});
        }
    }
}

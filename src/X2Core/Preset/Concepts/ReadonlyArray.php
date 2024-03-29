<?php

namespace X2Core\Preset;

use X2Core\Preset\Exceptions\ArrayAccessException;

/**
 * Trait ReadonlyArray
 * @package X2Core\Preset\Concepts
 *
 *  @author Oliver Valiente <oliver021val@gmail.com>
 */
trait ReadonlyArray
{
    /**
     * @var string
     */
    protected $arrName;

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
      return isset( $this->{$this->arrName}[$offset] );
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->{$this->arrName}[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @throws ArrayAccessException
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
       throw new ArrayAccessException("The array is readonly, the field you tried access is:  " . $offset);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @throws ArrayAccessException
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new ArrayAccessException("The array is readonly, the field you tried access is:  " . $offset);
    }
}
<?php
namespace Core\Data;

/**
 * CallbackHandlerInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016-2017
 * @license MIT
 */
interface CallbackHandlerInterface extends \Countable
{

    /**
     * Executes registered callbacks on given DataObject
     *
     * @param DataObjectInterface $data            
     */
    public function execute(DataObjectInterface $data);

    /**
     * Sets one or more callback functions
     *
     * @param array $callbacks
     *            Array of callbacks to add. Callbacks need at least following index structure:
     *            0 => Closure or [object, method] to call
     *            1 => (optional) Args to pass to call additionally to always provided data.
     * @param bool $clear_callbacks_stack
     *            (optional) Clears existing callback stack. Default: true
     */
    public function addCallbacks(array $callbacks = [], bool $clear_callbacks_stack = true);

    /**
     * Adds one callback function
     *
     * @param mixed $call
     *            The closure or array with object and method to call.
     * @param array $args
     *            (optional) Arguments to pass additionally to always added data.
     * @param bool $clear_callbacks_stack
     *            (optional) Clears existing callback stack. Default: true
     */
    public function addCallback($call, array $args = [], bool $clear_callbacks_stack = true);

    /**
     * Removes all callback functions
     */
    public function clearCallbacks();
}

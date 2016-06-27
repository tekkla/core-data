<?php
namespace Core\Data\Connectors;

use Core\Data\CallbackHandlerInterface;
use Core\Data\SchemeHandlerInterface;

/**
 * ConnectorInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016
 * @license MIT
 */
interface ConnectorInterface
{

    /**
     * Injects a callback handler
     *
     * @param CallbackHandlerInterface $callback_handler
     */
    public function injectCallbackHandler(CallbackHandlerInterface $callback_handler);

    /**
     * Injects a schemehandler
     *
     * @param SchemeHandlerInterface $scheme_handler
     */
    public function injectSchemeHandler(SchemeHandlerInterface $scheme_handler);

    /**
     * Adds a callback to an injected callback handler
     *
     * @param mixed $call
     * @param array $args
     * @param bool $clear_callbacks_stack
     */
    public function addCallback($call, array $args = [], bool $clear_callbacks_stack = true);

    /**
     * Adds a stack of callbacks to an injected callback handler
     *
     * @param array $callbacks
     * @param bool $clear_callbacks_stack
     */
    public function addCallbacks(array $callbacks = [], bool $clear_callbacks_stack = true);

    /**
     * Clears all callbacks in an injected callback handler
     */
    public function clearCallbacks();

    /**
     * Executes callbacks and an injected schemehandler
     *
     * @param mixed $data
     */
    public function executeCallbackAndSchemeHandler($data);
}


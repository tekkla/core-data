<?php
namespace Core\Data\Connectors;

use Core\Data\CallbackHandlerInterface;
use Core\Data\SchemeHandlerInterface;
use Core\Data\DataObjectInterface;

/**
 * AdapterAbstract.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016
 * @license MIT
 */
abstract class AbstractConnector implements ConnectorInterface
{

    /**
     *
     * @var CallbackHandlerInterface
     */
    private $callback_handler;

    /**
     *
     * @var SchemeHandlerInterface
     */
    private $scheme_handler;

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\ConnectorInterface::injectCallbackHandler()
     */
    public function injectCallbackHandler(CallbackHandlerInterface $callback_handler)
    {
        $this->callback_handler = $callback_handler;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\ConnectorInterface::injectSchemeHandler()
     */
    public function injectSchemeHandler(SchemeHandlerInterface $scheme_handler)
    {
        $this->scheme_handler = $scheme_handler;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\ConnectorInterface::addCallback()
     */
    public function addCallback($call, array $args = [], bool $clear_callbacks_stack = true)
    {
        if (!isset($this->callback_handler)) {
            Throw new ConnectorException('Adding callbacks to a connector that has no set CallbackHandler is not possible.');
        }

        $this->callback_handler->addCallback($call, $args, $clear_callbacks_stack);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\ConnectorInterface::addCallbacks()
     */
    public function addCallbacks(array $callbacks = [], bool $clear_callbacks_stack = true)
    {
        if (!isset($this->callback_handler)) {
            Throw new ConnectorException('Adding callbacks to a connector that has no set CallbackHandler is not possible.');
        }

        $this->callback_handler->addCallbacks($callbacks, $clear_callbacks_stack);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\ConnectorInterface::clearCallbacks()
     */
    public function clearCallbacks()
    {
        if (!isset($this->callback_handler)) {
            Throw new ConnectorException('Clearing callbacks of a connector that has no set CallbackHandler is not possible.');
        }

        $this->callback_handler->clearCallbacks();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Core\Data\Connectors\ConnectorInterface::executeCallbackAndSchemeHandler()
     */
    public function executeCallbackAndSchemeHandler($data)
    {

        // Do nothing when there is no handler at all!
        if (empty(count($this->callback_handler)) && !isset($this->scheme_handler)) {
            return $data;
        }

        if ($data instanceof DataObjectInterface) {

            $result = false;

            if (isset($this->callback_handler)) {
                $result = $this->callback_handler->execute($data);
            }

            if ($result instanceof DataObjectInterface && isset($this->scheme_handler)) {
                $this->scheme_handler->excecute($data);
            }

            $result = $data;
        }
        elseif (is_array($data)) {

            $result = [];

            foreach ($data as $data_object) {

                if (isset($this->callback_handler)) {
                    $callback_result = $this->callback_handler->execute($data_object);
                }

                if ($callback_result === false) {
                    continue;
                }

                if (isset($this->scheme_handler)) {
                    $this->scheme_handler->excecute($data_object);
                    $primary = $this->scheme_handler->getPrimary();
                }

                // Use the existing primary field name from scheme when it's available in data
                if (!empty($primary) && !empty($data_object->{$primary})) {
                    $result[$data_object->{$primary}] = $data_object;
                }
                else {
                    $result[] = $data_object;
                }
            }
        }

        return $result;
    }
}

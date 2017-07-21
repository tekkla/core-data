<?php
namespace Core\Data;

/**
 * SchemeHandlerInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016
 * @license MIT
 */
interface SchemeHandlerInterface
{

    /**
     * Sets a scheme array
     *
     * @param array $scheme
     */
    public function setScheme(array $scheme);

    /**
     * Analyzes object against the scheme and takes care of default values and/or serialized data
     *
     * @param DataObjectInterface $data
     */
    public function excecute(DataObjectInterface $data);

    /**
     * Get name of the set primary field in scheme
     *
     * Will be an empty string when primary is not set in scheme
     *
     * @return string
     */
    public function getPrimary(): string;
}

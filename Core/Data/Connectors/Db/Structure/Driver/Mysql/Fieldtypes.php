<?php
namespace Core\Data\Connectors\Db\Structure\Table\Types;

/**
 * Fieldtypes.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Fieldtypes
{

    /* Strings */
    const CHAR = 'CHAR';
    const VARCHAR = 'VARCHAR';
    const TINYTEXT = 'TINYTEXT';
    const TEXT = 'TEXT';
    const BLOB = 'BLOB';
    const MEDIUMTEXT = 'MEDIUMTEXT';
    const MEDIUMBLOB = 'MEDIUMBLOB';
    const LONGTEXT = 'LONGTEXT';
    const LONGBLOB = 'LONGBLOB';

    /* Numbers */
    const TINYINT = 'TINYINT';
    const SMALLINT = 'SMALLINT';
    const MEDIUMINT = 'MEDIUMINT';
    const INT = 'INT';
    const BIGINT = 'BIGINT';
    const FLOAT = 'FLOAT';
    const DOUBLE = 'DOUBLE';
    const DECIMAL = 'DECIMAL';

    /* Date & Time */
    const DATE = 'DATE';
    const TIME = 'TIME';
    const DATETIME = 'DATETIME';
    const YEAR = 'YEAR';
    const TIMESTAMP = 'TIMESTAMP';

    /* Other */
    const ENUM = 'ENUM';
    const SET = 'SET';
}


<?php
namespace Core\Data\Connectors\Db\Structure\Driver;

use Core\Data\Connectors\Db\Db;

/**
 * Driver.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2017
 * @license MIT
 */
class Driver
{

    public static function load(Db $db): DriverInterface
    {
        $prefix = ucfirst($db->dbh->getPrefix());
        $driverclass = '\Core\Data\Connectors\Db\Structue\'' . $prefix . '\'' . $prefix;

        return new $driverclass($db);
    }
}


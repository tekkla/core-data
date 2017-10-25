<?php
namespace Core\Data\Connectors\Db;

use Core\Data\Connectors\AbstractConnector;
use Core\Data\Connectors\Db\QueryBuilder\QueryBuilder;
use Core\Data\CallbackHandler;
use Core\Data\SchemeHandler;
use Core\Data\DataObject;

/**
 * Database connector
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016
 * @license MIT
 */
class Db extends AbstractConnector
{

    /**
     *
     * @var Connection
     */
    public $dbh = false;

    /**
     *
     * @var \PDOStatement
     */
    public $stmt = '';

    /**
     *
     * @var string
     */
    private $sql = '';

    /**
     *
     * @var array
     */
    private $params = [];

    /**
     *
     * @var string
     */
    private $prefix;

    /**
     *
     * @var array
     */
    private static $queries = [];

    /**
     * Constructor
     *
     * @param \PDO $pdo
     * @param string $prefix
     */
    public function __construct(\PDO $pdo, string $prefix)
    {
        $this->prefix = $prefix;
        
        // Connect to db
        $this->dbh = $pdo;
        
        // Inject an empty DataAdapter object
        $this->injectCallbackHandler(new CallbackHandler());
    }

    /**
     * Returns set table prefix
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix ?? '';
    }

    /**
     * Sets the tabel prefix
     *
     * @param string $prefix
     *            Table prefix
     */
    public function setPrefix(string $prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * Run QueryBuilder as definition parser to create a sql query
     *
     * @param array $definition
     *            QueryBuilder definition array
     * @param bool $autoexec
     *            Optional flag to autoexecute the created query
     *            
     * @return \PDOStatement:bool
     */
    public function qb(array $definition, bool $autoexec = false)
    {
        $builder = new QueryBuilder($definition);
        
        // Build sql string
        $this->sql = $builder->build();
        
        // Get params
        $this->params = $builder->getParams();
        
        return $this->query($autoexec);
    }

    /**
     * Run query by sql string.
     *
     * @param string $sql
     *            The sql string
     * @param array $params
     *            Optional parameter array
     * @param boolean $autoexec
     *            Optional flag to autoexecute query
     *            
     * @return \PDOStatement|bool
     */
    public function sql(string $sql, array $params = [], bool $autoexec = false)
    {
        // Store Sql / definition and parameter
        $this->sql = $sql;
        $this->params = $params;
        
        return $this->query($autoexec);
    }

    /**
     * This is the db query method
     *
     * @param boolean $exec
     *            Optional autoexec flag
     *            
     * @return \PDOStatement | bool
     */
    private function query(bool $autoexec = false)
    {
        // Reset PDO statement
        $this->stmt = null;
        
        $this->stmt = $this->dbh->prepare(str_replace('{db_prefix}', $this->prefix, $this->sql));
        
        if (! empty($this->params)) {
            foreach ($this->params as $parameter => $value) {
                $this->stmt->bindValue($parameter, $value);
            }
        }
        
        if ($autoexec === true) {
            return $this->execute();
        } else {
            return $this->stmt;
        }
    }

    /**
     * Bind parameter by value to PDO statement
     *
     * @param string $param
     *            Prameter name
     * @param mixed $value
     *            Parameter value
     * @param string $type
     *            Optional parameter datatype
     */
    public function bindValue(string $param, $value, string $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Bind parameter by param to PDO statement
     *
     * @param string $param
     *            Parameter name
     * @param mixed $value
     *            Parameter value
     * @param string $type
     *            Optional parameter datatype
     */
    public function bindParam(string $param, &$value, string $type = null)
    {
        if (is_null($type)) {
            
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindParam($param, $value, $type);
    }

    /**
     * Executes prepared statement
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    /**
     * Executes statement and returns result as an array of DataObjects
     *
     * @return array|bool
     */
    public function all()
    {
        $this->execute();
        
        $data = $this->stmt->fetchAll(\PDO::FETCH_CLASS, '\Core\Data\DataObject');
        
        if (! empty($data)) {
            $data = $this->executeCallbackAndSchemeHandler($data);
        }
        
        return $data;
    }

    /**
     * Executes statement and returns result of PDO fetchAll()
     *
     * @param int $fetch_style
     * @param mixed $fetch_argument
     * @param array $ctor_args
     *
     * @return array|bool
     */
    public function fetchAll(int $fetch_style = \PDO::FETCH_ASSOC, $fetch_argument = null, array $ctor_args = null)
    {
        $this->execute();
        
        if (empty($fetch_argument)) {
            $data = $this->stmt->fetchAll($fetch_style);
        } else {
            if (empty($ctor_args)) {
                $data = $this->stmt->fetchAll($fetch_style, $fetch_argument);
            } else {
                $data = $this->stmt->fetchAll($fetch_style, $fetch_argument, $ctor_args);
            }
        }
        
        return $data;
    }

    /**
     * Executes statement and returns result as DataObject
     *
     * @return DataObject|bool
     */
    public function single()
    {
        $this->execute();
        
        $data = $this->stmt->fetchObject('\Core\Data\DataObject');
        
        if (! empty($data)) {
            $data = $this->executeCallbackAndSchemeHandler($data);
        }
        
        return $data;
    }

    /**
     * PDO fetch
     *
     * @param int $fetch_style
     * @param mixed $fetch_argument
     * @param array $ctor_args
     *
     * @return mixed
     */
    public function fetch(int $fetch_style = \PDO::FETCH_ASSOC, $fetch_argument = null, array $ctor_args = null)
    {
        $this->execute();
        
        if (empty($fetch_argument)) {
            $data = $this->stmt->fetch($fetch_style);
        } else {
            if (empty($ctor_args)) {
                $data = $this->stmt->fetch($fetch_style, $fetch_argument);
            } else {
                $data = $this->stmt->fetch($fetch_style, $fetch_argument, $ctor_args);
            }
        }
        
        return $data;
    }

    /**
     * Executes statement and returns all rows of specific column
     *
     * @param int $column
     *            Number of column to return (Default: 0)
     *            
     * @return array|bool
     */
    public function column(int $column = 0)
    {
        $this->execute();
        
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN, $column);
    }

    /**
     * Executes the statement and returns value of first column in first row
     *
     * @return mixed
     */
    public function value()
    {
        $this->execute();
        
        return $this->stmt->fetchColumn();
    }

    /**
     * Shorthand method to perform Count(*) query
     *
     * @param string $table
     *            Table to delete from
     * @param string $filter
     *            Optional filterstatement (Default: empty)
     * @param array $params
     *            Optional parameter array to be used in filter (Default: empty)
     *            
     * @return int
     */
    public function count(string $table, string $filter = '', array $params = []): int
    {
        $query = [
            'table' => $table,
            'fields' => 'COUNT(*)'
        ];
        
        if ($filter) {
            
            $query['filter'] = $filter;
            
            if ($params) {
                $query['params'] = $params;
            }
        }
        
        $this->qb($query);
        
        return (int) $this->value();
    }

    /**
     * Finds one row by using one field and a value as filter
     *
     * @param string $table
     *            Name of table
     * @param string $key_field
     *            Name of the field to filter
     * @param mixed $value
     *            Filter value
     *            
     * @return DataObject|bool
     */
    public function find(string $table, string $key_field, $value)
    {
        $this->qb([
            'table' => $table,
            'filter' => $key_field . '=:' . $key_field,
            'params' => [
                ':' . $key_field => $value
            ]
        ]);
        
        return $this->single();
    }

    /**
     * Shorthand delete method.
     *
     * @param string $table
     *            Table to delete from
     * @param string $filter
     *            Optional: Filterstatement (Default='')
     * @param array $params
     *            Optional: Parameter array to be used in filter
     *            
     * @return bool
     */
    public function delete(string $table, string $filter = '', array $params = []): bool
    {
        $query = [
            'table' => $table,
            'method' => 'DELETE'
        ];
        
        if ($filter) {
            $query['filter'] = $filter;
            
            if ($params) {
                $query['params'] = $params;
            }
        }
        
        $this->qb($query, true);
        
        return true;
    }

    /**
     * Returns number or rows in resultset.
     *
     * @return int
     */
    public function rowCount(): int
    {
        $this->execute();
        
        return $this->stmt->rowCount();
    }

    /**
     * Returns columncount of resultset
     *
     * @return int
     */
    public function columnCount(): int
    {
        $this->execute();
        
        return $this->stmt->columnCount();
    }

    /**
     * Returns id of last inserted record
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Begins a PDO transaction
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * Commits PDO transaction.
     *
     * @return bool
     */
    public function endTransaction(): bool
    {
        return $this->dbh->commit();
    }

    /**
     * Cancels and rolls back PDO transaction
     *
     * @return bool
     */
    public function cancelTransaction(): bool
    {
        return $this->dbh->rollBack();
    }

    /**
     * Returns params used by statement
     *
     * @return array
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    /**
     * Nullifies stmt and dbh properties to close connection.
     *
     * @return bool
     */
    public function close(): bool
    {
        $this->stmt = null;
        $this->dbh = null;
        
        return true;
    }

    /**
     * Creates a string of named parameters and an array of named parameters => values.
     *
     * Use this for sql statements like mySQLs IN().
     *
     * @param string $param
     *            Params prefix (Default: 'prm')
     * @param array $values
     *            Values array
     *            
     * @return array
     */
    public function prepareArrayQuery(string $param_prefix = 'prm', array $values = []): array
    {
        $params_names = [];
        $params_val = [];
        
        foreach ($values as $key => $val) {
            $name = ':' . $param_prefix . $key;
            $params_names[] = $name;
            $params_val[$name] = $val;
        }
        
        return [
            'sql' => implode(', ', $params_names),
            'values' => $params_val
        ];
    }

    /**
     * Returns interpolated sql string with parameters
     *
     * @param string $sql
     * @param array $params
     *
     * @return string
     */
    public function debugSql(string $sql, array $params = []): string
    {
        if ($params) {
            
            $indexed = $params == array_values($params);
            
            foreach ($params as $k => $v) {
                
                if (is_object($v)) {
                    
                    if ($v instanceof \DateTime) {
                        $v = $v->format('Y-m-d H:i:s');
                    } else {
                        continue;
                    }
                } elseif (is_string($v)) {
                    $v = "'$v'";
                } elseif ($v === null) {
                    $v = 'NULL';
                } elseif (is_array($v)) {
                    $v = implode(',', $v);
                }
                
                if ($indexed) {
                    $sql = preg_replace('/\?/', $v, $sql, 1);
                } else {
                    $sql = str_replace($k, $v, $sql);
                }
            }
        }
        
        $sql = str_replace('{db_prefix}', $this->prefix, $sql);
        
        return $sql;
    }

    /**
     * Creates and returns an QueryBuilder instance
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder();
    }

    /**
     * Returns current sql string and parameter array
     *
     * @return array
     */
    public function getSqlAndParams(): array
    {
        return [
            'sql' => $this->sql,
            'params' => $this->params
        ];
    }

    /**
     * Adds a scheme array based SchemeHandler ojbect to the connector
     *
     * @param array $scheme
     *            The scheme array
     */
    public function setScheme(array $scheme)
    {
        $scheme_handler = new SchemeHandler();
        $scheme_handler->setScheme($scheme);
        
        $this->injectSchemeHandler($scheme_handler);
    }

    /**
     * Returns all queries done so far
     */
    public function getQueries(): array
    {
        return self::$queries;
    }
}

<?php
namespace Core\Data\Connectors\Db\QueryBuilder;

use Core\Toolbox\Arrays\IsAssoc;

/**
 * QueryBuilder.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016-2017
 * @license MIT
 */
class QueryBuilder implements QueryBuilderInterface
{

    /**
     *
     * @var string
     */
    private $method = 'SELECT';

    /**
     *
     * @var array
     */
    private $definition = [];

    /**
     *
     * @var array
     */
    private $scheme = [];

    /**
     *
     * @var string
     */
    private $table = '';

    /**
     *
     * @var string
     */
    private $prefix = '';

    /**
     *
     * @var string
     */
    private $alias = '';

    /**
     *
     * @var array
     */
    private $fields = [];

    /**
     *
     * @var array
     */
    private $values = [];

    /**
     *
     * @var array
     */
    private $join = [];

    /**
     *
     * @var string
     */
    private $filter = '';

    /**
     *
     * @var array
     */
    private $params = [];

    /**
     *
     * @var string
     */
    private $order = '';

    /**
     *
     * @var string
     */
    private $group_by = '';

    /**
     *
     * @var string
     */
    private $having = '';

    /**
     *
     * @var string
     */
    private $sql = '';

    /**
     *
     * @var array
     */
    private $counter = [];

    /**
     *
     * @var array
     */
    private $limit = [];

    /**
     * Constructor
     *
     * @param array $definition
     *            Optional QueryDefinition
     */
    public function __construct(array $definition = [])
    {
        if ($definition) {
            $this->processQueryDefinition($definition);
        }
    }

    /**
     * SELECT statement
     *
     * @param array|string $fields
     *            Fieldlist as comma seperated string or value array.
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Select($fields = ''): QueryBuilder
    {
        $this->method = 'SELECT';
        
        $this->Columns($fields);
        
        return $this;
    }

    /**
     * SELECT DISTINCT statement
     *
     * @param array|string $fields
     *            Fieldlist as comma seperated string or value array.
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function SelectDistinct($fields = ''): QueryBuilder
    {
        $this->method = 'SELECT DISTINCT';
        
        $this->Columns($fields);
        
        return $this;
    }

    /**
     * INSERT INTO statement
     *
     * @param
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function InsertInto(string $table): QueryBuilder
    {
        $this->method = 'INSERT INTO';
        $this->From($table);
        
        return $this;
    }

    /**
     * INTO statement
     *
     * @param string $table
     *            Name of table
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Into(string $table): QueryBuilder
    {
        $this->From($table);
        
        return $this;
    }

    /**
     * DELETE statement
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Delete(): QueryBuilder
    {
        $this->method = 'DELETE';
        
        return $this;
    }

    /**
     * Colums to use in query
     *
     * @param array|string $fields
     *            Fieldlist as comma seperated string or value array.
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Columns($columns = ''): QueryBuilder
    {
        if (empty($columns)) {
            return;
        }
        
        if (! is_array($columns)) {
            $this->fields = explode(',', $columns);
        }
        
        $this->fields = array_map('trim', $this->fields);
        
        return $this;
    }

    /**
     * From statement
     *
     * @param string $table
     *            Table name
     * @param string $alias
     *            Optional: Table alias
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function From(string $table, string $alias = ''): QueryBuilder
    {
        $this->table = $table;
        
        if (! empty($alias)) {
            $this->alias = $alias;
        }
        
        return $this;
    }

    /**
     * Filter statement
     *
     * @param string $filter
     *            Filterstring
     * @param array $params
     *            Optional: Paramenter list
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Where(string $filter, array $params = []): QueryBuilder
    {
        $this->filter;
        $this->params = $params;
        
        return $this;
    }

    /**
     * Order statement
     *
     * @param string $order
     *            Orderstring
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Order(string $order): QueryBuilder
    {
        $this->order = $order;
        
        return $this;
    }

    /**
     * Join statement
     *
     * @param string $table
     *            Table name of table to join
     * @param string $as
     *            Alias of join table
     * @param string $by
     *            How to join
     * @param string $condition
     *            Join condition
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Join(string $table, string $as, string $by, string $condition): QueryBuilder
    {
        $this->join[] = [
            'table' => $table,
            'as' => $as,
            'by' => $by,
            'cond' => $condition
        ];
        
        return $this;
    }

    /**
     * GroupBy statement.
     *
     * @param array|string $fields
     *            string|array field name or list of field names as array
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function GroupBy($fields): QueryBuilder
    {
        if (is_array($fields)) {
            $fields = implode(', ', $field);
        }
        
        $this->group_by = $fields;
        
        return $this;
    }

    /**
     * Dummy - still no funtion
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Having(): QueryBuilder
    {
        return $this;
    }

    /**
     * Limit statement.
     *
     * @param int $lower
     *            Lower limit
     * @param int $upper
     *            Optional: Upper limit
     *            
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Limit(int $lower, int $upper = null): QueryBuilder
    {
        $this->limit['lower'] = (int) $lower;
        
        if (isset($upper)) {
            $this->limit['upper'] = (int) $upper;
        }
        
        return $this;
    }

    /**
     * Adds one parameter in form of key and value or a list of parameters as assoc array
     *
     * Setting an array as $arg1 and leaving $arg2 empty means an assoc array of paramteres is going to be set.
     * Setting $arg1 and $arg2 means to set one parameter by name and value.
     *
     * @param string|array $arg1
     *            String with parametername or list of parameters in an assoc array
     * @param string $arg2
     *            Needs only to be set when $arg1 is the name and $arg2 the value of one parameter
     *            
     * @throws QueryBuilderException
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function setParameter($arg1, $arg2 = null): QueryBuilder
    {
        if ($arg2 === null) {
            
            $array = new IsAssoc($arg1);
            
            if (! is_array($arg1) || ! $array->isAssoc()) {
                Throw new QueryBuilderException('Setting one QueryBuilder argument means you have to set an assoc array as argument.');
            }
            
            $this->params = array_merge($this->params, $arg1);
        } else {
            $this->params[$arg1] = $arg2;
        }
        
        return $this;
    }

    /**
     * Returns all parameters
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Buildss the sql string for select queries
     *
     * @return string
     */
    public function build(): string
    {
        $join = '';
        $filter = '';
        $order = '';
        $limit = '';
        
        // Biuld joins
        if ($this->join) {
            $tmp = [];
            
            foreach ($this->join as $def) {
                $tmp[] = ' ' . $def['by'] . ' JOIN ' . '{db_prefix}' . (isset($def['as']) ? $def['table'] . ' AS ' . $def['as'] : $def['join']) . ' ON (' . $def['cond'] . ')';
            }
        }
        
        $join = isset($tmp) ? implode(' ', $tmp) : '';
        
        // Add counter
        if ($this->counter) {
            
            $tmp = '(@' . $this->counter['name'] . ':=' . '@' . $this->counter['name'] . '+' . $this->counter['step'] . ') AS ' . $this->counter['as'];
            
            array_unshift($this->fields, $tmp);
        }
        
        $fieldlist = $this->buildFieldlist();
        
        // Create filterstatement
        $filter = $this->filter ? 'WHERE ' . $this->filter : '';
        
        // Create group by statement
        $group_by = '';
        
        if ($this->group_by) {
            
            if (is_array($this->group_by)) {
                $this->group_by = implode(', ', $this->group_by);
            }
            
            $group_by = 'GROUP BY ' . $this->group_by;
        }
        
        // Create having statement
        $having = $this->having ? 'HAVING ' . $this->having : '';
        
        // Create order statement
        $order = $this->order ? 'ORDER BY ' . $this->order : '';
        
        // Create limit statement
        if ($this->limit) {
            
            $limit = ' LIMIT ';
            
            if (isset($this->limit['lower'])) {
                $limit .= $this->limit['lower'];
            }
            
            if (isset($this->limit['lower']) && isset($this->limit['upper'])) {
                $limit .= ',' . $this->limit['upper'];
            }
        }
        
        // Get complete tablename with alias while respecting called mathod
        $table = $this->buildTableName();
        
        switch ($this->method) {
            
            case 'UPDATE':
                
                // we have to build our own fieldlist
                $fields = [];
                
                foreach ($this->fields as $field) {
                    $fields[] = $field . '=:' . $field;
                }
                
                $pieces = [
                    $this->method,
                    $table,
                    'SET',
                    implode(',', $fields),
                    $filter
                ];
                
                break;
            
            case 'INSERT':
            case 'REPLACE':
                
                // Build values
                $values = [];
                
                foreach ($this->fields as $field) {
                    $values[] = ':' . $field;
                }
                
                if (empty($values)) {
                    Throw new QueryBuilderException('No values for INSERT or REPLACE found.');
                }
                
                $pieces = [
                    $this->method . ' INTO',
                    $table,
                    empty($fieldlist) || $fieldlist == '*' ? '' : '(' . $fieldlist . ')',
                    'VALUES (' . implode(', ', $values) . ')'
                ];
                
                break;
            
            case 'DELETE':
                
                $pieces = [
                    $this->method,
                    'FROM ' . $table,
                    $filter,
                    $order,
                    $limit
                ];
                
                break;
            
            default:
                
                $pieces = [
                    $this->method,
                    $fieldlist,
                    'FROM ' . $table,
                    $join,
                    $filter,
                    $group_by,
                    $having,
                    $order,
                    $limit
                ];
                
                break;
        }
        
        array_walk_recursive($pieces, function (&$data, $index) {
            $data = trim($data);
        });
        
        // Build sql string from pieces
        $this->sql = trim(implode(' ', $pieces));
        
        // Finally cleanup parameters by removing parameter not needed in query and parse array parameter into sql
        // string.
        foreach ($this->params as $key => $val) {
            
            // Do cleanup
            if (strpos($this->sql, $key) === false) {
                unset($this->params[$key]);
                continue;
            }
            
            // Replace array parameter against sql valid part
            if (is_array($val)) {
                $prepared = $this->prepareArrayQuery($key, $val);
                
                array_push($this->params, $prepared['params']);
                
                $this->sql = str_replace('prep:' . $key, $prepared['sql'], $this->sql);
            }
        }
        
        return $this->sql;
    }

    private function buildFieldlist(): string
    {
        // Create fieldlist
        if (! empty($this->fields)) {
            
            if (! is_array($this->fields)) {
                $this->fields = (array) $this->fields;
            }
            
            foreach ($this->fields as $key_field => $field) {
                $this->fields[$key_field] = $this->checkSubquery($field);
            }
            
            return implode(', ', $this->fields);
        }
        
        // Return complete all fields
        return ($this->alias ? $this->alias : $this->table) . '.*';
    }

    /**
     * Builds table name with alias while taking care of the method of the sql create
     *
     * @return string
     */
    private function buildTableName(): string
    {
        $table = '{db_prefix}' . $this->table;
        
        $no_alias_methods = [
            'REPLACE',
            'INSERT'
        ];
        
        if (in_array($this->method, $no_alias_methods)) {
            return $table;
        }
        
        if (! empty($this->alias)) {
            $table .= ' AS ' . $this->alias;
        }
        
        return $table;
    }

    /**
     * Processes the content of an array containing a query definition
     *
     * @param array $query_definition
     */
    private function processQueryDefinition(array $query_definition)
    {
        // Store defintion
        $this->definition = $query_definition;
        
        if (! empty($this->definition['scheme'])) {
            $this->scheme = $this->definition['scheme'];
        }
        
        // Use set query type or use 'row' as default
        if (isset($this->definition['method'])) {
            $this->method = strtoupper($this->definition['method']);
            unset($this->definition['method']);
        }
        
        // All methods need the table defintion
        $this->processTableDefinition();
        
        // Process data definition?
        if (isset($this->definition['data'])) {
            $this->processDataDefinition();
        }
        
        switch ($this->method) {
            
            case 'INSERT':
            case 'REPLACE':
                $this->processInsert();
                break;
            
            case 'UPDATE':
                $this->processUpdate();
                break;
            
            case 'DELETE':
                $this->processDelete();
                break;
            
            default:
                $this->processSelect();
                break;
        }
        
        return $this;
    }

    /**
     * Processes SELECT defintion
     */
    private function processSelect()
    {
        $this->processTableDefinition();
        $this->processFieldDefinition();
        $this->processFilterDefinition();
        $this->processParamsDefinition();
        $this->processGroupByDefinition();
        $this->processHavingDefinition();
        $this->processJoinDefinition();
        $this->processOrderDefinition();
        $this->processLimitDefinition();
    }

    /**
     * Processes INSERT definition
     *
     * @throws QueryBuilderException
     */
    private function processInsert()
    {
        if (empty($this->definition['fields']) && empty($this->definition['field'])) {
            Throw new QueryBuilderException('QueryBuilder need a "field" or "fields" list element to process "INSERT" definition.');
        }
        
        if (! isset($this->definition['params'])) {
            Throw new QueryBuilderException('QueryBuilder need a assoc array param list to process "INSERT" definition.');
        }
        
        $this->processFieldDefinition();
        $this->processParamsDefinition();
    }

    /**
     * Processes UPDATE process
     *
     * @throws QueryBuilderException
     */
    private function processUpdate()
    {
        if (! isset($this->definition['fields']) && ! isset($this->definition['field'])) {
            Throw new QueryBuilderException('QueryBuilder need a "field" or "fields" list element to process "UPDATE" definition.');
        }
        if (! isset($this->definition['params'])) {
            Throw new QueryBuilderException('QueryBuilder need a assoc array param list to process "UPDATE" definition.');
        }
        
        $this->processFieldDefinition();
        $this->processFilterDefinition();
        $this->processParamsDefinition();
        $this->processLimitDefinition();
        $this->processOrderDefinition();
    }

    private function processDelete()
    {
        $this->processFieldDefinition();
        $this->processFilterDefinition();
        $this->processOrderDefinition();
        $this->processLimitDefinition();
        $this->processParamsDefinition();
    }

    /**
     * Processes TABLE defintion
     *
     * Takes also care of set alias definition.
     *
     * @throws QueryBuilderException
     */
    private function processTableDefinition()
    {
        // Table setting in scheme always (!) overrides manual set table definition
        if (! empty($this->scheme['table'])) {
            $this->table = $this->scheme['table'];
        }
        
        // Look for table name when there was none set by a scheme
        if (empty($this->table) && ! empty($this->definition['table'])) {
            $this->table = $this->definition['table'];
        }
        
        // Still no table? Time to complain about it with an exception
        if (empty($this->table)) {
            Throw new QueryBuilderException('QueryBuilder needs a table name. Provide tablename by setting "table" element in your query definition or provide a Scheme with a set table element.<br><br><small>Send QueryBuilder definition:</small><pre>' . print_r($this->definition, true) . '</pre>');
        }
        
        // Is our table an array, which means that it is a QueryBuilder definition?
        if (is_array($this->table)) {
            
            // Build this sql string
            $this->table = $this->checkSubquery($this->table);
        }
        
        // Alias setting in scheme always (!) overrides manual set alias definition
        if (! empty($this->scheme['alias'])) {
            $this->alias = $this->scheme['alias'];
        }
        
        // No alias until here but set in definition?
        if (empty($this->alias) && ! empty($this->definition['alias'])) {
            $this->alias = $this->definition['alias'];
        }
    }

    private function processCounter()
    {
        if (empty($this->definition['counter'])) {
            return;
        }
        
        // store counter definition
        $this->counter = $this->definition['counter'];
        
        // Check for values of counter and add needed values by setting default vlaues
        $defaults = [
            'name' => 'counter',
            'as' => 'counter',
            'start' => 1,
            'step' => 1
        ];
        
        foreach ($defaults as $key => $val) {
            if (empty($this->counter[$key])) {
                $this->counter[$key] = $val;
            }
        }
    }

    /**
     * Processes field sdefinition
     *
     * Uses '*' as fields when no definition is set.
     */
    private function processFieldDefinition()
    {
        if (isset($this->definition['field'])) {
            $this->fields = $this->definition['field'];
        } elseif (isset($this->definition['fields'])) {
            $this->fields = $this->definition['fields'];
        } else {
            $this->fields = '*';
        }
    }

    /**
     * Processes data defintion
     *
     * Take care of data type. Uses container field names for field list
     * and field values for paramters.
     *
     * @throws QueryBuilderException
     */
    private function processDataDefinition()
    {
        // Autodetection of method when none is set e.g. SELECT is set
        if ($this->method == 'SELECT') {
            
            // Try to get name of primary field from provided scheme. A scheme always is preferred to qb definitions
            $primary = ! empty($this->scheme['primary']) ? $this->scheme['primary'] : false;
            
            // No primary from scheme? Check for a primary set in definition.
            if ($primary == false && ! empty($this->definition['primary'])) {
                $primary = $this->definition['primary'];
            }
            
            // Set method to UPDATE when primary exists and has a value. Otherwise we INSERT a new record
            $this->method = ($primary !== false && ! empty($this->definition['data'][$primary])) ? 'UPDATE' : 'INSERT';
        }
        
        // Check for allowed querymethods
        $allowed_methods = [
            'UPDATE',
            'INSERT',
            'REPLACE'
        ];
        
        if (! in_array($this->method, $allowed_methods)) {
            Throw new QueryBuilderException('QueryBuilder can only process querymethods of type "update", "insert" or "replace".');
        }
        
        foreach ($this->definition['data'] as $field_name => $value) {
            
            // Field is flagged as serialized?
            if (! empty($this->scheme['serialize'][$field_name])) {
                $value = serialize($value);
            }
            
            // Handle fields flagged as primary
            if (isset($primary) && $field_name == $primary) {
                
                // Different modes need different handlings
                switch ($this->method) {
                    
                    // Ignore field when mode is insert
                    case 'INSERT':
                        continue;
                        break;
                    
                    // Use field as filter on update
                    case 'UPDATE':
                        $this->definition['filter'] = $field_name . ' = :__primary_' . $field_name;
                        $this->definition['params'][':__primary_' . $field_name] = $value;
                        break;
                    
                    // Add field to fieldlist on replace
                    case 'REPLACE':
                    default:
                        $this->definition['fields'][] = $field_name;
                        $this->definition['params'][':' . $field_name] = $value;
                        break;
                }
            } else {
                
                // Simple field and value
                $this->definition['fields'][] = $field_name;
                $this->definition['params'][':' . $field_name] = $value;
            }
        }
    }

    /**
     * Processes JOIN defintion.
     * Such definition can be an assoc or indexed array.
     */
    private function processJoinDefinition()
    {
        if (isset($this->definition['join']) && is_array($this->definition['join'])) {
            
            foreach ($this->definition['join'] as $join) {
                
                $array = new IsAssoc($join);
                
                if ($array->isAssoc()) {
                    $this->join[] = [
                        'table' => $join['table'],
                        'as' => $join['as'],
                        'by' => $join['by'],
                        'cond' => $join['condition']
                    ];
                } else {
                    $this->join[] = [
                        'table' => $join[0],
                        'as' => $join[1],
                        'by' => $join[2],
                        'cond' => $join[3]
                    ];
                }
            }
        }
    }

    /**
     * Processes GROUP BY defintion
     */
    private function processGroupByDefinition()
    {
        if (isset($this->definition['group'])) {
            $this->group_by = $this->definition['group'];
        }
    }

    /**
     * Processes HAVING defintion
     */
    private function processHavingDefinition()
    {
        if (isset($this->definition['having'])) {
            $this->having = $this->definition['having'];
        }
    }

    /**
     * Processes FILTER defintion.
     *
     * @throws QueryBuilderException
     */
    private function processFilterDefinition()
    {
        if (isset($this->definition['filter'])) {
            
            if (is_array($this->definition['filter'])) {
                
                if (! count($this->definition['filter']) == 2) {
                    Throw new QueryBuilderException('Querybuilder needs two elements when filter is set as array. The first element has to be the filter statement. The second one an assoc array with filter parameters');
                }
                
                $array = new IsAssoc($this->definition['filter'][1]);
                
                if (! $array->isAssoc()) {
                    Throw new QueryBuilderException('Querybuilder needs an assoc array as filter parameter list.');
                }
                
                $this->filter = $this->definition['filter'][0];
                
                // Set params
                $this->setParameter($this->definition['filter'][1]);
            } else {
                $this->filter = $this->definition['filter'];
            }
        }
    }

    /**
     * Processes limit defintion
     */
    private function processLimitDefinition()
    {
        if (isset($this->definition['limit'])) {
            
            // Upper and lower limit? Or a single value?
            if (is_array($this->definition['limit'])) {
                
                switch (count($this->definition['limit'])) {
                    
                    case 1:
                        $this->limit['lower'] = (int) $this->definition['limit'][0];
                        break;
                    
                    case 2:
                    default:
                        $this->limit['lower'] = (int) $this->definition['limit'][0];
                        $this->limit['upper'] = (int) $this->definition['limit'][1];
                        break;
                }
            } else {
                $this->limit['lower'] = $this->definition['limit'];
            }
        }
    }

    /**
     * Processes order defintion
     */
    private function processOrderDefinition()
    {
        if (isset($this->definition['order'])) {
            $this->order = $this->definition['order'];
        }
    }

    /**
     * Processes parameter definition
     */
    private function processParamsDefinition()
    {
        $extend = isset($this->definition['params']) ? 's' : '';
        
        if (isset($this->definition['param' . $extend])) {
            $this->setParameter($this->definition['param' . $extend]);
        }
    }

    /**
     * Creates a string of named parameters and an array of named parameters => values.
     *
     * @param string $param
     * @param array $values
     *
     * @return array
     */
    private function prepareArrayQuery(string $param_name, array $params): array
    {
        $params_name = [];
        $params_val = [];
        
        foreach ($params as $key => $val) {
            $name = ':' . 'arr_' . $key;
            $params_name[] = $name;
            $params_val[$name] = $val;
        }
        
        $sql = implode(', ', $params_name);
        
        str_replace($param_name, $sql, $this->sql);
        array_merge($this->params, $params_val);
        
        return [
            'sql' => $sql,
            'values' => $params_val
        ];
    }

    /**
     * Checks for an expression for a QB defintion
     *
     * When $expression is an array than it is treated like a QB defintion. A new QB instance will be created and the
     * expression will be parsed. Parameters used in the expression will be added to the parents parameterlist.
     * When the expression is no array the expression will be returned unchanged.
     *
     * @param string $expression
     *            The expression to check for an array with a QB definition
     *            
     * @return string
     */
    private function checkSubquery($expression): string
    {
        if (is_array($expression)) {
            $qb = new QueryBuilder($expression);
            
            // Build sql string and replace the existing expression with it
            $expression = '(' . $qb->build() . ')';
            
            // Add possible params to parents params array
            $this->params += $qb->getParams();
        }
        
        return $expression;
    }
}

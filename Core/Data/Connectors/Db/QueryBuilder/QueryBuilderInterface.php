<?php
namespace Core\Data\Connectors\Db\QueryBuilder;

/**
 * QueryBuilderInterface.php
 *
 * @author Michael "Tekkla" Zorn <tekkla@tekkla.de>
 * @copyright 2016-2017
 * @license MIT
 */
interface QueryBuilderInterface
{
    /**
     * Constructor
     *
     * @param array $definition
     *            Optional QueryDefinition
     */
    public function __construct(array $definition = []);
    
    /**
     * SELECT statement
     *
     * @param array|string $fields
     *            Fieldlist as comma seperated string or value array.
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Select($fields = ''): QueryBuilder;
    
    /**
     * SELECT DISTINCT statement
     *
     * @param array|string $fields
     *            Fieldlist as comma seperated string or value array.
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function SelectDistinct($fields = ''): QueryBuilder;
    
    /**
     * INSERT INTO statement
     *
     * @param
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function InsertInto(string $table): QueryBuilder;
    
    /**
     * INTO statement
     *
     * @param string $table
     *            Name of table
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Into(string $table): QueryBuilder;
    
    /**
     * DELETE statement
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Delete(): QueryBuilder;
    
    /**
     * Colums to use in query
     *
     * @param array|string $fields
     *            Fieldlist as comma seperated string or value array.
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Columns($columns = ''): QueryBuilder;
    
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
    public function From(string $table, string $alias = ''): QueryBuilder;
    
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
    public function Where(string $filter, array $params = []): QueryBuilder;
    
    /**
     * Order statement
     *
     * @param string $order
     *            Orderstring
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Order(string $order): QueryBuilder;
    
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
    public function Join(string $table, string $as, string $by, string $condition): QueryBuilder;
    
    /**
     * GroupBy statement.
     *
     * @param array|string $fields
     *            string|array field name or list of field names as array
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function GroupBy($fields): QueryBuilder;
    
    /**
     * Dummy - still no funtion
     *
     * @return \Core\Data\Connectors\Db\QueryBuilder\QueryBuilder
     */
    public function Having(): QueryBuilder;
    
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
    public function Limit(int $lower, int $upper = null): QueryBuilder;
    
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
    public function setParameter($arg1, $arg2 = null): QueryBuilder;
    
    /**
     * Returns all parameters
     *
     * @return array
     */
    public function getParams(): array;
    
    /**
     * Buildss the sql string for select queries
     *
     * @return string
     */
    public function build(): string;
    
}




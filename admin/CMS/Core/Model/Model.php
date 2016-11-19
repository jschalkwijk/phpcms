<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
use PDO;
use PDOException;

/*
 * Values array na elke query resetten? voorkomt mischien problemen die ik had met het updated van een model
 * en het voorbereiden van statements.
 * Het opnieuw instellen van de model variables n een apparte functie? of alleen in patch? of alleen in update?
 * */

/**
 * Class Model
 * @package CMS\Core\Model
 */
abstract class Model
{
    protected $database;

    protected $connection;

    protected $sqlError;
    /**
     * Holds the Primary Key of the related table
     * Default set to 'id'. Change in extending class if needed.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set table name of the related table
     * @var string
     */
    protected $table = '';

    /**
     * If the table has relations, specify them in an associative array as foreign table => foreign key
     * @var array
     */
    protected $relations = [];

    /**
     * If you want to join columns from related tables specify them in an associative array.
     * @var array
     */
    protected $joins = [];

    /**
     * Columns that are specified in the allowed array are allowed to be filled with data
     * in the related table. Columns that are not in this array can't be filled by the user.
     * @var array
     */
    protected $allowed = [];

    /**
     * Columns that are specified in the hidden array are allowed to be filled with data
     * in the related table. Columns that are in this array can't be filled by the user.
     * These can only be specified inside the extending class object or the controller.
     * For example, the current user_id.
     * @var array
     */
    protected $hidden = [];

    /**
     * @var string
     */
    protected $statement = '';

    /**
     * The query which is generated for the model.
     *
     * @var array
     */
    protected $query = "";

    /**
     * @var array
     */
    protected $values = [];

    /**
     * Holds the request values which are set to attributes when a new object class is created.
     * @var array
     */
    protected $request = [];

    /**
     * Model constructor.
     * Takes an optional associative array, preferably a $_POST request. This
     * will set the model attributes.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        // Create attributes for every request value
        // The request will be validated when trying to perform the save method for example.
        // But we might need some of the request values for returning values to the user etc.
        //
        if(!empty($attributes)) {
            $this->request = $attributes;
            foreach ($attributes as $key => $value) {
                $this->$key = $value;
            }
        }
        // Set the database up for use.
        $this->database = new DBC;
    }

    /**
     * @param $property
     * @return mixed
     */
    function __get($property) {
        $method = "get_$property";
        if(method_exists($this, $method)) echo $this->$method;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Returns all class objects linked to the called model.
     *
     * @param int $trashed
     * @param array $joins
     * @return Object Array
     *
     */
    public static function all($trashed = 0,$joins = null)
    {
        $model = new static;
        $model->connection = $model->database->connect();
        $joins = $model->join($joins);
        $query = $model->select().$joins['as'].$model->from().$joins['on'].$model->where(['trashed' => $trashed]).$model->orderBy($model->primaryKey,'DESC');
        //echo $query;
        return $model->newQuery($query);
    }
    /**
     * Returns all class objects linked to the called model.
     *
     * @param int $id
     * @param array $joins
     * @return Object Array
     *
     */
    public static function single($id,$joins = null)
    {
        $model = new static;
        $model->connection = $model->database->connect();
        $joins = $model->join($joins);

        $query = $model->select().$joins['as'].$model->from().$joins['on'].$model->where([$model->primaryKey => $id]);
        //echo $query;
        return $model->newQuery($query);
    }

    /**
     * Executes mysql Query
     * Returns an array with the rows as a class object if statement is select.
     * Else it will perform a query which returns true if successful.
     * @param string $query
     * @return Object Array
     *
     */
    public function newQuery($query)
    {
        $this->query = $query;
        echo 'MODEL LINE 168: Query = '.$query.'<br>';
        echo 'Values Array: ';
        print_r($this->values);
        try {
            // the connection has to  be made elsewhere in the child class
            // we need the connection to be tied to the class function
            // so we can use database functions for example $lastID = $dbc->lastInsertId();
            // If we don't need that functionality we vcan create the connection here.
            if(empty($this->connection)){
                $this->connection = $this->database->connect();
            }

            $dbc = $this->connection;

            if(!empty($this->values)) {
                $query = $dbc->prepare($query);
                $query->execute($this->values);
                //$this->database->close();
            } else {
                $query = $dbc->query($query);
                //$this->database->close();
            }
            if($this->statement == "SELECT") {
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $results = $query->fetchAll();
                $this->database->close();
                return $this->fetch($results);
            } else {
                $this->database->close();
                return true;
            }
        } catch(\PDOException $e){
            $this->sqlError = $e->getMessage();
            return false;
        }
    }

    /**
     * Returns an array with the rows as objects
     * For every ro of data a new class object is created for the current model.
     * @param array $results
     * @return Object Array
     *
     */
    public function fetch($results)
    {
        $data = [];
        foreach($results as $row) {
            $model = new static();
            foreach ($row as $k => $v){
                $model->$k = $v;
            }
            $data[] = $model;
        }
        return $data;
    }

    /**
     * Returns SELECT part of an sql statement.
     *
     * @param array $columns
     * @return string
     *
     */
    public function select($columns = ['*'])
    {
        $this->statement = "SELECT";
        return "SELECT ".$this->table.".".implode($columns);
    }

    /**
     * Returns FROM part of an sql statement.
     *
     * @return string
     *
     */
    public function from()
    {
        return " FROM ".$this->table." ";
    }

    /**
     * Returns WHERE part of an sql statement.
     *
     * @param array $columns
     * @return String
     *
     */
    public function where($columns = [])
    {
        $string = array();
        foreach($columns as $column => $value){
            // Set column to ? for prepared statement
            $string[] = $this->table.".".$column." = ?";
            // Set values array for PDO prepared statement
            $this->values[] = $value;
        }
        $where = implode(' AND ', $string);
        return " WHERE ".$where;
    }
    /**
     * Returns Order By part of an sql statement.
     *
     * @param string $column
     * @param string $order
     * @return string
     *
     */
    public function orderBy($column = '',$order = '')
    {
        return " ORDER BY ".$column." ".$order;
    }
    /**
     * Returns array with the joined relations from the Model.
     *
     * @param array $joins
     * @return String Array
     *
     */
    public function join($joins = null)
    {
        if($joins == null && !empty($this->joins)){
           $joins = $this->joins;
        } else if($joins == null && empty($this->joins)){
            return [
                "as" => "",
                "on" => "",
            ];
        }
        $as = "";
        $on = "";
        // Creating a $as and $on string for every relation
        foreach($this->relations as $table => $foreignKey){
            // If relation is defined and is added as a join, create the needed string
            if(array_key_exists($table,$joins)){
                $columns = $joins[$table];
                foreach($columns as $name) {
                    $as .= ", $table.$name AS $table" . "_" . "$name";
                }
                $on .= " JOIN $table ON " . $this->table . ".$foreignKey  = $table.$foreignKey ";
            }
        }
        return [
            "as" => $as,
            "on" => $on,
        ];
    }

    /**
     * @param $prep
     * @return string
     */
    public function insert($prep)
    {
        return "INSERT INTO $this->table (".implode(',',$prep['columns']).",date) VALUES(".implode(',',$prep['placeholders']).",NOW())";

    }

    /**
     * Returns update part of a sql statement
     * @param $prep
     * @return string
     */
    public function update($attributes = [])
    {
        // If controller provided array
        if(!empty($attributes)) {
            $this->request = $attributes;
            // reset Values.
            $this->values = [];
        }
        // Reset Current Object values with new values;
        if(!empty($this->request)) {
            //$this->request = $attributes;
            foreach ($this->request as $key => $value) {
                $this->$key = $value;
            }
        }

        $prep = $this->prepareQuery();
        $string = array();
        foreach($prep['columns'] as $column){
            // Set column to ? for prepared statement
            $string[] = $column." = ?";
        }
        $set = implode(',', $string);
        return "UPDATE $this->table SET ".$set;
    }

    /**
     * Saves the object to the database
     * @return bool
     */
    public function save()
    {
        $query = $this->insert($this->prepareQuery());

        return $this->newQuery($query);
    }

    /**
     * Changes a existing data row or set
     * @param array $attributes;
     * @return Object
     */
    public function patch($attributes = [])
    {
        // If controller provided array
        if(!empty($attributes)) {
            $this->request = $attributes;
            // reset Values.
            $this->values = [];
        }
//        print_r($this->request);
        // Reset Current Object values with new values;
        if(!empty($this->request)) {
            //$this->request = $attributes;
            foreach ($this->request as $key => $value) {
                $this->$key = $value;
            }
        }

        $query = $this->update().$this->where([$this->primaryKey => $this->get_id()]);
        return $this->newQuery($query);
    }

    /**
     * Returns array with placeholders,column names that must be filled and the values belonging to them.
     * if no values are given query is prepared with the request values.
     * @param $values
     * @return array
     *
     */
    public function prepareQuery($attributes = null)
    {
        $placeholders = [];
        $columns = [];

        if($attributes!= null){
            $this->request = $attributes;
            $this->values = [];
        }
        // Voor nu reset ik de array. Bij het aanmaken van de Model worden de waardes
        // gcreerd als er input is. Ik moet even goed kijken of dit goed gaat
        // als ik de request waardes daar instel. Nu doe ik dat nog even hier.
        //$this->values = [];
        // Checks if request value is allowed to be inserted by user and then inserts it.
        foreach($this->request as $column => $value){
            if(in_array($column,$this->allowed)){
                $placeholders[] = '?';
                $columns[] = $column;
                $this->values[] = $value;
            }
        }
        // Checks if the hidden properties are allowed. Example: current user_id etc.
        foreach($this->hidden as $column => $value){
                $placeholders[] = '?';
                $columns[] = $column;
                $this->values[] = $value;
        }
        return ['placeholders' => $placeholders,'columns' => $columns];
    }

    /**
     * Remembers values in forms
     * @param $value
     * @return mixed
     */
    public function keep($value)
    {
        if(!empty($value)) {
           return $value;
        } else {
            return '';
        }
    }
}
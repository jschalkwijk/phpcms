<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
use PDO;
use PDOException;

/**
 * Class Model
 * @package CMS\Core\Model
 */
abstract class Model
{

    protected $primaryKey = 'id';

    protected $table = '';

    protected $relations = [];

    protected $joins = [];

    protected $allowed = [];

    protected $hidden = [];

    /**
     * The query which is generated for the model.
     *
     * @var array
     */
    protected $statement = '';

    protected $query = "";

    protected $values = [];

    protected $request = [];

    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->request = $attributes;
        // Create attributes for every request value
        // The request will be validated when trying to perform the save method for example.
        // But we might need some of the request values for returning values to the user etc.
        //
        if(!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param $property
     * @return mixed
     */
    function __get($property) {
        $method = "get_$property";
        if(method_exists($this, $method)) return $this->$method;
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
     * Returns all objects linked to the called model.
     *
     * @param int $trashed
     * @param array $joins
     * @return Object Array
     *
     */
    public static function all($trashed = 0,$joins = null)
    {
        $model = new static;
        $joins = $model->join($joins);
        $query = $model->select().$joins['as'].$model->from().$joins['on'].$model->where(['trashed' => $trashed]).$model->orderBy($model->primaryKey,'DESC');
        //echo $query;
        return $model->newQuery($query);
    }
    /**
     * Returns all objects linked to the called model.
     *
     * @param int $id
     * @param array $joins
     * @return Object Array
     *
     */
    public static function single($id,$joins = null)
    {
        $model = new static;
        $joins = $model->join($joins);

        $query = $model->select().$joins['as'].$model->from().$joins['on'].$model->where([$model->primaryKey => $id]);
        //echo $query;
        return $model->newQuery($query);
    }

    /**
     * Executes mysql Query
     * Returns an array with the rows as objects
     *
     * @param string $query
     * @return Object Array
     *
     */
    public function newQuery($query)
    {
        $this->query = $query;
        echo $query;
        print_r($this->values);
        $db = new DBC;
        try {
            $dbc = $db->connect();
            if(!empty($this->values)) {
                $query = $dbc->prepare($query);
                $query->execute($this->values);
            } else {
                $query = $dbc->query($query);
            }
            if($this->statement == "SELECT") {
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $results = $query->fetchAll();
                $db->close();
                return $this->fetch($results);
            } else {
                $db->close();
                return true;
            }
        } catch(\PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Returns an array with the rows as objects
     *
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
     * @param $prep
     * @return string
     */
    public function update($prep)
    {
        $string = array();
        foreach($prep['columns'] as $column){
            // Set column to ? for prepared statement
            $string[] = $column." = ?";
        }
        $set = implode(',', $string);
        return "UPDATE $this->table SET ".$set;
    }

    /**
     * @return bool
     */
    public function save()
    {
        $query = $this->insert($this->prepareQuery());
//        if($this->newQuery($query)) {
//            return true;
//        } else {
//            return false;
//        }
        return $this->newQuery($query);
    }

    public function change()
    {
        $query = $this->update($this->prepareQuery()).$this->where([$this->primaryKey => $this->id]);
        return $this->newQuery($query);
//        return $query;
    }


    /**
     * Returns array with placeholders,column names that must be filledm and the values belonging to them.
     *
     * @param array $hidden
     * @return array
     *
     */
    public function prepareQuery()
    {
        $placeholders = [];
        $columns = [];
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
     * @param $value
     * @return mixed
     */
    public function keep($value)
    {
        if(isset($value)) {
           return $value;
        } else {
            return false;
        }
    }
}
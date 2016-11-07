<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
use PDOException;

abstract class Model
{

    /**
     * The query which is generated for the model.
     *
     * @var array
     */
    protected $query = "";

    protected $primaryKey = 'id';

    protected $table = '';

    protected $relations = [];

    protected $joins = [];

    protected $allowed = [];

    function __get($property) {
        $method = "get$property";
        if(method_exists($this, $method)) return $this->$method;
    }
    public function __set($key,$value)
    {
        $this->$key = $value;
    }
    /**
     * Returns all objects linked to the called model.
     *
     * @param Int $trashed
     * @param Array $joins
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
     * @param Int $id
     * @param Array $joins
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

    public function add($request)
    {

    }
    /**
     * Executes mysql Query
     * Returns an array with the rows as objects
     *
     * @param String $query
     * @return Object Array
     *
     */
    public function newQuery($query)
    {
        $this->query = $query;
        echo $query;
        $db = new DBC;
        $dbc = $db->connect();
        try {
            $query = $dbc->query($query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            $results = $query->fetchAll();
        } catch(\PDOException $e){
           echo $e->getMessage();
        }
        $db->close();
        return $this->fetch($results);
    }

    /**
     * Returns an array with the rows as objects
     *
     * @param Array $results
     * @return Object Array
     *
     */
    public function fetch($results)
    {
        $data = [];
        foreach($results as $row) {
            $model = new static();
            foreach ($row as $k => $v){
                //echo $model->$k,$v;
                $model->$k = $v;
            }
            $data[] = $model;
        }
        return $data;
    }

    /**
     * Returns SELECT part of an sql statement.
     *
     * @param Array $columns
     * @return String
     *
     */
    public function select($columns = ['*'])
    {
        return "SELECT ".$this->table.".".implode($columns);
    }

    /**
     * Returns FROM part of an sql statement.
     *
     * @return String
     *
     */
    public function from()
    {
        return " FROM ".$this->table." ";
    }

    /**
     * Returns WHERE part of an sql statement.
     *
     * @param Array $columns
     * @return String
     *
     */
    public function where($columns = [])
    {
        $values = array();
        foreach($columns as $k => $v){
            $values[] = $this->table.".".$k." = ".$v;
        }
        $where = implode(' AND ', $values);
        return " WHERE ".$where;
    }
    /**
     * Returns Order By part of an sql statement.
     *
     * @param String $column
     * @param String $order
     * @return String
     *
     */
    public function orderBy($column = '',$order = '')
    {
        return " ORDER BY ".$column." ".$order;
    }
    /**
     * Returns array with the joined relations from the Model.
     *
     * @param Array $joins
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
     * Returns array with placeholders,column names that must be filledm and the values belonging to them.
     *
     * @param Array $request
     * @return Array
     *
     */
    public function insert()
    {
        $db = new DBC;
        $dbc = $db->connect();
        $post = self::placeholders($_POST);
        $query = "INSERT INTO $this->table(".implode(',',$post['columns'])." VALUES(".implode(',',$post['placeholders']).")";
        echo $query;
        try {
            $query = $dbc->prepare($query);
            $query->execute($post['values']);
        } catch(\PDOException $e){
            echo $e->getMessage();
        }
        $db->close();
    }
    public function placeholders($request)
    {
        $placeholders = [];
        $columns = [];
        $values = [];
        foreach($request as $column => $value){
            if(in_array($column,$this->allowed)){
                $placeholders[] = '?';
                $columns[] = $column;
                $values[] = $value;
            }
        }
        return ['placeholders' => $placeholders,'columns' =>$columns,'values' => $values];
    }
}
<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;

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

    function __get($property) {
        $method = "get$property";
        if(method_exists($this, $method)) return $this->$method;
    }
    /**
     * Returns all objects linked to the called model.
     *
     * @param Array $joins
     * @return Object Array
     *
     */
    public static function all($joins = null)
    {
        $model = new static;
        $joins = $model->join($joins);
        $query = $model->select().$joins['as'].$model->from().$joins['on'].$model->orderBy($model->primaryKey,'DESC');
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
        $result = mysqli_query($dbc,$query);
        return $this->fetch($result);
    }

    /**
     * Returns an array with the rows as objects
     *
     * @param \mysqli_result $result
     * @return Object Array
     *
     */
    public function fetch($result)
    {
        $data = [];
        while ($row = $result->fetch_assoc()) {
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
            $values[] = $k." = ".$v;
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
}
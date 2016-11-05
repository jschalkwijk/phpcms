<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
abstract class Model
{
    protected $select;
    protected $from;
    protected $where;
    protected $orderBy;
    protected $join;
    protected $query = "";

    function __get($property) {
        $method = "get$property";
        echo $method."<br>";
        if(method_exists($this, $method)) return $this->$method;
    }
    public static function all($columns = ['*'])
    {
        //$columns = is_array($columns) ? $columns : func_get_args();

        $model = new static;
        $db = new DBC;
        $dbc = $db->connect();
        $result = mysqli_query($dbc,"SELECT ".implode($columns)." FROM ".$model->table);
        return $model->fetch($result);
    }
//    public static function all($columns = ['*'])
//    {
//        //$columns = is_array($columns) ? $columns : func_get_args();
//
//        $model = new static;
//        print_r($model);
//        $query = $model->select()." FROM ".$model->table." ".$model->where1($columns[1]).$model->orderBy($model->id);
//        echo $query;
//        return $model->newQuery($query);
//
//    }
    public static function single($id,$relations = null)
    {
        $model = new static;
        $joins = $model->join($relations);

        $query = $model->select().$joins['which'].$model->from().$joins['join'].$model->where1([$model->primaryKey => $id]);
        //echo $query;
        return $model->newQuery($query);
    }

    public function newQuery($query)
    {
        $db = new DBC;
        $dbc = $db->connect();
        $result = mysqli_query($dbc,$query);
        return $this->fetch($result);

//        $builder = $this->newQueryWithoutScopes();
//
//        foreach ($this->getGlobalScopes() as $identifier => $scope) {
//            $builder->withGlobalScope($identifier, $scope);
//        }
//
//        return $builder;
        //return $this;
    }

    public function where($columns,$value)
    {
        $db = new DBC;
        $dbc = $db->connect();
        //$query = "SELECT ".$this->table.".".implode($columns[0])." FROM ".$this->table." WHERE ".$columns[1]." = ".$value;
        //$query = "SELECT ".$this->table.".".implode($columns[0]).", categories.title as category FROM ".$this->table." JOIN categories ON ".$this->table.".category_id = categories.category_id WHERE posts.".$columns[1]." = ".$value;
        $query = "SELECT ".$this->table.".".implode($columns[0]).", categories.title as category FROM ".$this->table." JOIN categories ON ".$this->table.".category_id = categories.category_id WHERE posts.".$columns[1]." = ".$value;
        (isset($columns[2])) ? $query .= " ".implode($columns[2]) : "";
        //$sql ="SELECT posts.*, categories.title as category FROM posts LEFT JOIN categories ON posts.category_id = categories.category_id WHERE posts.trashed = ? ORDER BY post_id DESC"."<br>";
        //echo $sql;
        echo $query;
        $result = mysqli_query($dbc,$query);
        return $this->fetch($result);
    }

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
        // print_r($data);
        return $data;
    }

    public function select($columns = ['*'])
    {
        return "SELECT ".$this->table.".".implode($columns);
    }

    public function from()
    {
        return " FROM ".$this->table." ";
    }

    public function where1($columns = ['post_id' => 111,'title' => 'Jorn'])
    {
        $values = array();
        foreach($columns as $k => $v){
            $values[] = $k." = ".$v;
        }
        $where = implode(' AND ', $values);
        return " WHERE ".$where;
    }

    public function orderBy($column = 'post_id',$order = 'DESC')
    {
        return " ORDER BY ".$column." ".$order;
    }

    public function join($columns)
    {
        if($columns == null){
            return [
                "which" => "",
                "join" => "",
            ];
        }
        $which = "";
        $join = "";
        foreach($this->relations as $k => $v){
            $table = $k;
            $foreignKey = $v;
            if(array_key_exists($table,$columns)){
                for ($i = 0; $i < count($columns[$table]); $i++) {
                    $name = $columns[$table][$i];
                    $which .= ", $table.$name AS $table" . "_" . "$name";
                }
                $join .= " JOIN $table ON " . $this->table . ".$foreignKey  = $table.$foreignKey ";
            }
        }

        if(!empty($table) && !empty($foreignKey)) {
            return [
                //"which" => ", $table.$column AS $table" . "_" . "$column",
                "which" => $which,
                "join" => $join,
            ];
        } else {
            return [
                "which" => "",
                "join" => "",
            ];
        }

    }

    public function get()
    {
        return $this->query;
    }
//    public function newQueryWithoutScopes()
//    {
//        $builder = $this->newEloquentBuilder(
//            $this->newBaseQueryBuilder()
//        );
//
//        // Once we have the query builders, we will set the model models so the
//        // builder can easily access any information it may need from the model
//        // while it is constructing and executing various queries against it.
//        return $builder->setModel($this)->with($this->with);
//    }
//    public function newEloquentBuilder($query)
//    {
//        return new Builder($query);
//    }
}
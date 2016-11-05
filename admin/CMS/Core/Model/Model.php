<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
abstract class Model
{
    protected $query = "";

    function __get($property) {
        $method = "get$property";
        echo $method."<br>";
        if(method_exists($this, $method)) return $this->$method;
    }
    public static function all($columns = ['*'])
    {
        //$columns = is_array($columns) ? $columns : func_get_args();

        $instance = new static;
        $db = new DBC;
        $dbc = $db->connect();
        $result = mysqli_query($dbc,"SELECT ".implode($columns)." FROM ".$instance->table);
        return $instance->fetch($result);
    }
//    public static function all($columns = ['*'])
//    {
//        //$columns = is_array($columns) ? $columns : func_get_args();
//
//        $instance = new static;
//        print_r($instance);
//        $query = $instance->select()." FROM ".$instance->table." ".$instance->where1($columns[1]).$instance->orderBy($instance->id);
//        echo $query;
//        return $instance->newQuery($query);
//
//    }
    public static function single($value)
    {
        $instance = new static;
//        $columns = [['*'],$instance->id,['LIMIT 1']];
//        $data = $instance->where($columns,$value);
        $query = $instance->select()." FROM ".$instance->table." ".$instance->where1([$instance->id => $value]);
        echo $query;
        return $instance->newQuery($query);
//        return $data;
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
        $query = "SELECT ".$this->table.".".implode($columns[0]).", categories.title as category FROM ".$this->table." JOIN categories ON ".$this->table.".category_id = categories.categorie_id WHERE posts.".$columns[1]." = ".$value;
        (isset($columns[2])) ? $query .= " ".implode($columns[2]) : "";
        //$sql ="SELECT posts.*, categories.title as category FROM posts LEFT JOIN categories ON posts.category_id = categories.categorie_id WHERE posts.trashed = ? ORDER BY post_id DESC"."<br>";
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
        return $data;
    }

    public function select($columns = ['*'])
    {
        return "SELECT ".$this->table.".".implode($columns);
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

    public function join()
    {

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
//        // Once we have the query builders, we will set the model instances so the
//        // builder can easily access any information it may need from the model
//        // while it is constructing and executing various queries against it.
//        return $builder->setModel($this)->with($this->with);
//    }
//    public function newEloquentBuilder($query)
//    {
//        return new Builder($query);
//    }
}
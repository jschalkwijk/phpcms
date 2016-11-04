<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
abstract class Model
{
    public static function all($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $instance = new static;
        //echo print_r($instance)."<br>";
        //var_dump($instance);
        $data = $instance->newQuery($columns);
        return $data;
    }

    public function newQuery($columns)
    {
        $db = new DBC;
        $dbc = $db->connect();
        $result = mysqli_query($dbc,"SELECT ".implode($columns)." FROM ".$this->table);
        $data = [];
        print_r($result);
        while ($row = $result->fetch_assoc()) {
            $model = new static();
            foreach ($row as $k => $v){
                $model->$k = $v;
                echo $model->$k;
            }
            $data[] = $model;
        }
        return $data;

//        $builder = $this->newQueryWithoutScopes();
//
//        foreach ($this->getGlobalScopes() as $identifier => $scope) {
//            $builder->withGlobalScope($identifier, $scope);
//        }
//
//        return $builder;
        //return $this;
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
<?php

namespace CMS\Core\Model;
use CMS\Models\DBC\DBC;
use PDO;
use PDOException;

use CMS\Core\Pluralize\Inflect;

// TODO: Nu doe ik alles oplasse of updaten met de request values, maar als ik dan een waarde aanpas moet dat in
// de request array, $this->request['name'] = 'Jorn', ik wil dit kunnen doen $this->name = 'Jorn'. $this->save.
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

    public $lastInsertId;
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
     * This had to filled if you want your joins stated in the joins array to be actually joined in the query.
     * @var array
     */
    protected $relations = [];

    /**
     * If you want to join columns from related tables specify them in an associative array.
     * Usage: table_name => ['column_name', 'column_name]
     * Access them in the returned object by : tablename_columnname, like categories_title,users_username etc
     *  Either use the joins var to join in related tables or use the relations ship functions.
        I would use join if you just need a few row items. Only use the relation functions if you
        really need all of it. To just display a category name for example just join that value.
     *  If joins is used
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
    public $request = [];

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
        $method = "$property";
//        print_r($method);
        if(method_exists($this, $method)  && is_callable(array($this,$method))) {
            return call_user_func(
                array($this,$method)
            );
//            return $this->$method();
        }
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
     * @return Object static
     */
    public static function build()
    {
        $model = new static;
        return $model;
    }

    /**
     * Returns all class objects linked to the called model.
     *
     * @return Object Array
     *
     */
    public static function all()
    {
        $model = new static;
        $model->connection = $model->database->connect();

        //echo $query;

//        return  $model->select()->join()->orderBy($model->primaryKey,'DESC')->grab();
        return  $model->select()->from()->orderBy($model->primaryKey,'DESC')->grab();
    }
    /**
     * Returns all class objects linked to the called model where value = ....
     * @param array $columns
     * @return Object Array
     *
     */
    public static function allWhere($columns = [])
    {
        $model = new static;
        $model->connection = $model->database->connect();
        return $model->select()->join()->where($columns)->orderBy($model->primaryKey,'DESC')->grab();

    }

    /**
     * Returns all class objects linked to the called model.
     *
     * @param int $id
     * @param array $joins
     * @return Object Array
     *
     */
    public static function one($id,$joins = null)
    {
        $model = new static;
        $model->connection = $model->database->connect();

        // grab returns an array, so for only one item, we are returning the first value [0]
        return $model->select()->join($joins)->where([$model->primaryKey => $id])->grab()[0];
    }

    /**
     * @param $slug
     * @param null $joins
     * @return mixed
     */

    public static function slug($slug, $joins = null)
    {
        $model = new static;
        $model->connection = $model->database->connect();

        return $model->select()->join($joins)->where(['title' => $slug])->grab();

    }

    /**
     * Executes mysql Query
     * Returns an array with the rows as a class object if statement is select.
     * Else it will perform a query which returns true if successful.
     * @param string $query
     * @return mixed
     *
     */
    public function grab($query = null)
    {
        if (!empty($query)) {
            $this->query = $query;
        }
        echo 'Query = '.$this->query.'<br>';
        echo 'Values Array: <br>';
        echo "<pre>";
        print_r($this->values);
        echo "</pre>";

        echo 'Request Array: <br>';
        echo "<pre>";
        print_r($this->request);
        echo "</pre>";

        // make a function which updates the current sql statement. The first letters of the query determine what the statement is.
        try {
            // the connection has to  be made elsewhere in the child class
            // we need the connection to be tied to the class function
            // so we can use database functions for example $lastID = $dbc->lastInsertId();
            // If we don't need that functionality we can create the connection here.
//            if(empty($this->connection)){
//                $this->connection = $this->database->connect();
//            }
//
            $dbc = $this->database->connect();

            if(!empty($this->values)) {
                $query = $dbc->prepare($this->query);
                $query->execute($this->values);
                $this->lastInsertId = $dbc->lastInsertId();
//                return $this;
            } else {
                $query = $dbc->query($this->query);
//                $this->database->close();
            }
            if($this->statement == "SELECT") {
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $results = $query->fetchAll();
                $this->database->close();
                return $this->collect($results);
            } else {
                $this->database->close();
                return $this;
            }
        } catch(\PDOException $e){
            $this->sqlError = $e->getMessage();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Returns an array with the rows as objects
     * For every ro of data a new class object is created for the current model.
     * @param array $results
     * @return array
     *
     */
    public function collect($results)
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
     * @return $this
     *
     */
    public function select($columns = ['*'])
    {
        $this->statement = "SELECT";
//        $this->query ="SELECT ".$this->table.".".implode($columns);
//        return $this;
        $this->query .= "SELECT {$this->table}.".implode(",{$this->table}.",$columns);
        return $this;
    }

    /**
     * Returns FROM part of an sql statement.
     *
     * @return $this
     *
     */
    public function from()
    {
        $this->query .= " FROM {$this->table} ";
        return $this;
    }

    /**
     * Returns WHERE part of an sql statement.
     *
     * @param array $columns
     * @return $this
     *
     */
    public function where($columns = [])
    {
        $string = array();
        foreach ($columns as $column => $value) {
            // if the length is 1 and the value is an array we have to call WhereIN because
            // the system has provided an Array. We use this for the Model functions so we don't
            // have to make two distinct update queries for example.
            // if ypu create your own query inside a controller you can use the whereIn directly.
            if(count($columns) == 1 && is_array($value)) {
                return $this->whereIN($columns);
            }
            // Set column to ? for prepared statement
            $string[] = $this->table . "." . $column . " = ?";
            // Set values array for PDO prepared statement
            $this->values[] = $value;
        }
        $where = implode(' AND ', $string);
        $this->query .= " WHERE {$where}";
        return $this;
}

    /**
     * @param array $columns
     * @return $this
     */
    public function whereIN($columns = [])
    {
        $string = "";
        foreach($columns as $column => $multiple) {
            $placeholders = substr(str_repeat("?, ", count($multiple)), 0, -2);
            // Set column to ? for prepared statement
            $string = $this->table . "." . $column . " IN (" . $placeholders . ")";
            // Set values array for PDO prepared statement
            foreach ($multiple as $value) {
                $this->values[] = $value;
            }
        }
        $this->query .=  " WHERE ".$string;
        return $this;
    }
    /**
     * Returns Order By part of an sql statement.
     *
     * @param string $column
     * @param string $order
     * @return $this
     *
     */
    public function orderBy($column = '',$order = '')
    {
        $this->query .= " ORDER BY {$column} {$order}";
        return $this;
    }


    /**
     * Returns a query string to join the specified table and columns. Access them by
     * tablename_columnname, like categories_title,users_username etc.
     * @return static
     */
    public static function joined($joins = null)
    {
        $model = new static;
        $model->select()->join($joins);
        return $model;
    }
    /**
     * Returns array with the joined relations from the Model.
     *
     * @param array $joins
     * @return $this
     *
     */
    public function join($joins = null)
    {
        if($joins == null && !empty($this->joins)){
           $joins = $this->joins;
        } else if($joins == null && empty($this->joins)){
            $this->from();
            return $this;
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
                $on .= " JOIN {$table} ON {$this->table}.{$foreignKey}  = {$table}.{$foreignKey} ";
            }
        }

        $this->query .= $as;
        $this->from();
        $this->query .= $on;

        return $this;
    }

    /**
     * @param $prepared
     * @return $this
     */
    public function insert($prepared)
    {
        $this->query .= "INSERT INTO $this->table (".implode(',',$prepared['columns']).",date) VALUES(".implode(',',$prepared['placeholders']).",NOW())";
        return $this;

    }

    /**
     * Returns update part of a sql statement
     * @param $attributes
     * @return $this
     */
    public function update($attributes = [])
    {
//        // If controller provided array
//        if(!empty($attributes)) {
//            $this->request = $attributes;
////            foreach ($this->request as $key => $value) {
////                $this->$key = $value;
////            }
//            // reset Values.
//            $this->values = [];
//        }
//        // Reset Current Object values with new values;
//        // TODO: maybe remove this, this should already have been done with patching the object. can be done at same time above
//        if(!empty($this->request)) {
//            //$this->request = $attributes;
//            foreach ($this->request as $key => $value) {
//                $this->$key = $value;
//            }
//        }

        $prep = $this->prepareQuery($this->request);
        $string = array();
        foreach($prep['columns'] as $column){
            // Set column to ? for prepared statement
            $string[] = $column." = ?";
        }
        $set = implode(',', $string);
        $this->query.= "UPDATE {$this->table} SET {$set}";
        return $this;
    }

    /**
     * Saves the object to the database
     * @return bool
     */
    // TODO: Save should reset the request,values and hidden fields, if youneed to patch it later on in the same function, you won't get conflicts.
    // TODO: save or savePatch or Update could iterate over the model keys, and not the value array's http://php.net/manual/en/language.oop5.iterations.php
    public function save()
    {
      return $this->insert($this->prepareQuery())->grab() === true;
    }

    /**
     * Changes a existing data row or set with the request values or the given array with data.
     * @param array $attributes;
     */
    public function patch($attributes = [])
    {
        // If controller provided array
        if(!empty($attributes)) {
            $this->request = $attributes;
            // reset Values.
            $this->values = [];
        }
        echo "Patch Request: <br>";
        print_r($this->request);
        // Reset Current Object values with new values;
        if(!empty($this->request)) {
            //$this->request = $attributes;
            foreach ($this->request as $key => $value) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * @return Object array
     */
    public function savePatch()
    {
      return $this->update()->where([$this->primaryKey => $this->get_id()])->grab();
    }

    public function delete($keys = null)
    {
        if(isset($keys)) {
            $keys = is_array($keys) ? implode(",", $keys) : $keys;
        } else {
            $primaryKey= $this->primaryKey;
            $keys = $this->$primaryKey;
        }
        return $this->grab("DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " IN({$keys})");
    }
    /**
     * Returns array with placeholders,column names that must be filled and the values belonging to them.
     * if no values are given query is prepared with the request values.
     * @param $attributes
     * @return array
     *
     */
    public function prepareQuery($attributes = null)
    {
        $placeholders = [];
        $columns = [];

        //When there are manually added attributes to the function we don't need to loop over the whole object,
        // this means we only need to update that specific attributes
        if ($attributes != null || !empty($attributes)) {
            $this->request = $attributes;
            $this->values = [];
            foreach($this->request as $column => $value){
                if(in_array($column,$this->allowed)  || in_array($column, $this->hidden)){
                    $placeholders[] = '?';
                    $columns[] = $column;
                    $this->values[] = $value;
                } else {
                    echo "<p style='color: red;'>{$column} not set as allowed attribute</p>";
                }
            }
        } else {
            // Voor nu reset ik de array. Bij het aanmaken van de Model worden de waardes
            // gcreerd als er input is. Ik moet even goed kijken of dit goed gaat
            // als ik de request waardes daar instel. Nu doe ik dat nog even hier.
            //$this->values = [];
            // Checks if request value is allowed to be inserted by user and then inserts it.
            // TODO: or should I loop over the object keys here instead of the request?

            foreach ($this as $column => $value) {
                if (in_array($column, $this->allowed) || in_array($column, $this->hidden)) {
                    $placeholders[] = '?';
                    $columns[] = $column;
                    $this->values[] = $value;
                } else if (!empty($this->encrypted) && in_array($column, $this->encrypted)) {
    //                $this->$column = $this->encrypt($value);
                    $placeholders[] = '?';
                    $columns[] = $column;
                    $this->values[] = $this->encrypt($value);
                } else {
//                    echo "<p style='color: red;'>{$column} not set as allowed/encrypted or hidden attribute</p>";
                }
            }
        }

//        foreach($this->request as $column => $value){
//            if(in_array($column,$this->allowed)){
//                $placeholders[] = '?';
//                $columns[] = $column;
//                $this->values[] = $value;
//            } else {
//                echo "<p style='color: red;'>{$column} not set as allowed attribute</p>";
//            }
//        }
//        // Checks if the hidden properties are allowed. Example: current user_id etc.
//        foreach($this->hidden as $column => $value){
//                $placeholders[] = '?';
//                $columns[] = $column;
//                $this->values[] = $value;
//        }
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

    ## Relations
    /**
     * @param $relatedModel
     * @param null $primaryKey
     * @param null $foreignKey
     * @return mixed
     */
    public function ownsOne($relatedModel,$primaryKey = null,$foreignKey = null)
    {
        $model = new $relatedModel;
        $model->connection = $model->database->connect();
        $model->statement = "SELECT";
        // if the current models primaryKey name is not named the same as the foreignkey in the related table, you can change it by adding it in the func,
        // as well as the foreignKey name where we will get the ID from.
        if($primaryKey == null && $foreignKey == null){
            // $query = "SELECT * FROM {$model->table} WHERE {$this->primaryKey} = {$this->get_id()}";
            // folder_id is primaryKey
            $primaryKey = $model->primaryKey;
            // $primarykey will be used to get the id value of the Model that is calling it
            // $this->$primarykey actually says $this->folder_id
            // now we need an ID, for example Products has an folder_id value if we call this from products we
            // get that album ID and then fetch it.
            return $model->one($this->$primaryKey);
        } else {
//            $query = "SELECT * FROM {$model->table} WHERE $primaryKey = {$this->$foreignKey}";
            return $model->allWhere([$primaryKey => $this->$foreignKey]);
        }
//        return $model->grab($query)[0];
    }

    /**
     * @param $relatedModel
     * @param string $primaryKey
     * @param string $foreignKey
     * @return mixed
     */
    public function owns($relatedModel,$primaryKey = null,$foreignKey = null)
    {
        $model = new $relatedModel;
        $model->connection = $model->database->connect();
        $model->statement = "SELECT";
        // if the current models primaryKey name is not named the same as the foreignkey in the related table, you can change it by adding it in the func,
        // as well as the foreignKey name where we will get the ID from.
        if($primaryKey == null && $foreignKey == null){
           // $query = "SELECT * FROM {$model->table} WHERE {$this->primaryKey} = {$this->get_id()}";
            return $model->allWhere([$this->primaryKey => $this->get_id()]);
        } else {
//            $query = "SELECT * FROM {$model->table} WHERE $primaryKey = {$this->$foreignKey}";
            return $model->allWhere([$primaryKey => $this->$foreignKey]);
        }
//        return $model->grab($query);
    }

    /**
     * @param $relatedModel
     * @param null $primaryKey
     * @param null $foreignKey
     * @return mixed
     */
    public function ownedBy($relatedModel,$primaryKey = null,$foreignKey = null)
    {
        $model = new $relatedModel;
        $model->connection = $model->database->connect();
        $model->statement = "SELECT";
        // print_r($this->$foreignKey);
        // $this->$foreignKey is for example $this->category_id and gets the value
        if($primaryKey == null && $foreignKey == null) {
            $primaryKey = $model->primaryKey;
            $query = "SELECT * FROM {$model->table} WHERE {$model->primaryKey} = {$this->$primaryKey}";
        } else if($primaryKey != null && $foreignKey != null){
            $query = "SELECT * FROM {$model->table} WHERE {$primaryKey} = {$this->$foreignKey}";
        }
        return $model->grab($query)[0];
    }

    public function ownedByMany($relatedModel,$primaryKey = null,$foreignKey = null)
    {
        $model = new $relatedModel;
        $model->connection = $model->database->connect();
        $model->statement = "SELECT";
        // print_r($this->$foreignKey);
        // $this->$foreignKey is for example $this->category_id and gets the value
        if($primaryKey == null && $foreignKey == null) {
            $primaryKey = $model->primaryKey;
            $query = "SELECT * FROM {$model->table} WHERE {$model->primaryKey} = {$this->$primaryKey}";
        } else if($primaryKey != null && $foreignKey != null){
            $query = "SELECT * FROM {$model->table} WHERE {$primaryKey} = {$this->$foreignKey}";
        }
        return $model->grab($query);
    }

    public function children($selfReference = null)
    {
        $this->statement = "SELECT";
        if(empty($selfReference)) {
            return $this->allWhere(['parent_id' => $this->get_id()]);
        } else {
            return $this->allWhere([$selfReference => $this->get_id()]);
        }
    }

    public function childrenCascade()
    {

    }


    /**
     * @param $model1
     * @param $model2
     * @param null $primaryKey
     * @param null $foreignKey
     * @return mixed
     */

    public function ownsThrough($model1, $model2, $primaryKey = null, $foreignKey = null)
    {
        $model1 = new $model1;
        $model2 = new $model2;
        $model1->connection = $model1->database->connect();
        $model1->statement = "SELECT";
        $query = "SELECT * FROM {$model1->table} WHERE {$model2->primaryKey} =
        (SELECT {$model2->primaryKey} FROM {$model2->table} WHERE {$this->primaryKey} = {$this->folder_id})";
        return $model1->grab($query);
    }
    /**
     * Morpheus returns Polymorphic many to many relationships
     * @param $relatedModel
     * @return mixed
     */
    public function morpheus($relatedModel)
    {
        // TODO: Get a pluralizer to edit the pivottable name to change F.E. taggables to taggable and categories to category

        $model = new $relatedModel;
        $model->connection = $model->database->connect();
        $model->statement = "SELECT";
        $singular = Inflect::singularize($model->pivotTable);
        $query = "SELECT * FROM {$model->pivotTable} RIGHT JOIN {$model->table} ON {$model->pivotTable}.{$model->primaryKey} = {$model->table}.{$model->primaryKey} WHERE {$singular}_type = 'post' AND {$singular}_id = ".$this->get_id()." ORDER BY {$model->table}.{$model->primaryKey} DESC";
//        SELECT * FROM taggables RIGHT JOIN tags ON taggables.tag_id = tags.tag_id WHERE taggable_type = 'post' AND taggable_id = 126 ORDER BY tags.tag_id DESC
        return $model->grab($query);
    }
}
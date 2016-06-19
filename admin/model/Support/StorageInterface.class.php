<?php
// abstract class to set mandatory methods.
interface Support_StorageInterface {

    public function get($index);

    public function set($index,$value);

    public function all();

    public function exists($index);

    public function unsetProduct($index);

    public function clear();

    public function count();
}

?>
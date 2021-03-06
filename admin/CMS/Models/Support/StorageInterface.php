<?php
namespace CMS\Models\Support;

// abstract class to set mandatory methods.
interface StorageInterface {

    public function get($index);

    public function set($index,$value);

    public function all();

    public function exists($index);

    public function unsetIndex($index);

    public function clear();

    public function count();
}

?>
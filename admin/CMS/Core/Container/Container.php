<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 19-04-17
     * Time: 17:40
     */

    namespace CMS\Core\Container;

    use ArrayAccess;
    class Container implements ArrayAccess
    {
        protected $items = [];
        protected $cache = [];

        /**
         * Container constructor.
         * @param array $items
         */
        public function __construct(array $items = [])
        {
            foreach($items as $key => $item){
                $this->offsetSet($key,$item);
            }
        }

        /**
         * @param $property
         * @return mixed|null
         */
        public function __get($property)
        {
            return $this->offsetGet($property);
        }

        /**
         * Offset to set
         * @param mixed $offset
         * @param mixed $value
         */
        public function offsetSet($offset, $value)
        {
            $this->items[$offset] = $value;
        }

        /**
         * * Offset to retrieve
         * @param mixed $offset
         * @return null
         */
        public function offsetGet($offset)
        {
            if(!$this->has($offset)){
                return null;
            }

            if(isset($this->cache[$offset])){
                return $this->cache[$offset];
            }

            $item = $this->items[$offset]($this);

            $this->cache[$offset] = $item;

            return $item;
        }

        /**
         * Offset to unset
         * @param mixed $offset
         */
        public function offsetUnset($offset)
        {
            if($this->has($offset)){
                unset($this->items[$offset]);
            }
        }

        /**
         * Whether a offset exists
         * @param mixed $offset
         * @return bool
         */
        public function offsetExists($offset)
        {
            return isset($this->items[$offset]);
        }

        /**
         * Whether a offset exists
         * @param $offset
         * @return bool
         */
        public function has($offset)
        {
            return $this->offsetExists($offset);
        }
    }
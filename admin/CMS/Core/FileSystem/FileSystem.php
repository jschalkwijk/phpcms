<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 24-06-17
     * Time: 13:53
     */

    namespace CMS\Core\FileSystem;


    class FileSystem
    {
        /**
         * Determine if a file or directory exists.
         *
         * @param  string  $path
         * @return bool
         */
        public function exists($path)
        {
            return file_exists($path);
        }

        /**
         * Create a directory.
         *
         * @param  string  $path
         * @param  int     $mode
         * @param  bool    $recursive
         * @param  bool    $force
         * @return bool
         */
        public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
        {
            if ($force) {
                return @mkdir($path, $mode, $recursive);
            }

            return mkdir($path, $mode, $recursive);
        }

        /**
         * Move a directory.
         *
         * @param  string  $from
         * @param  string  $to
         * @param  bool  $overwrite
         * @return bool
         */
        public function moveDirectory($from, $to, $overwrite = false)
        {
            if ($overwrite && $this->exists($to)) {
                return rename($from, $to) === true;
            } else {
                return false;
            }
        }

    }
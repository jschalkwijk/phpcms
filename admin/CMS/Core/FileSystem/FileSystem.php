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
//            if ($overwrite && $this->exists($to)) {
//                return rename($from, $to) === true;
//            } else {
//                return false;
//            }
//            die($from.' '.$to);
            return rename($from, $to) === true;
        }

        /**
         * Delete the directory recursively at a given path.
         *
         * @param  string|array  $paths
         *
         */

        public function removeDirectory($paths) {
            $paths = is_array($paths) ? $paths : [$paths];
            foreach($paths as $path) {
                $files = glob($path . '/*');
                foreach ($files as $file) {
                    is_dir($file) ? self::removeDirectory($file) : unlink($file);
                }
                rmdir($path);
            }
            return;
        }

        /**
         * Delete the file at a given path.
         *
         * @param  string|array  $paths
         * @return bool
         */
        public function delete($paths): bool
        {
            $paths = is_array($paths) ? $paths : [$paths];

            $success = true;

            foreach ($paths as $path) {
                if (!unlink($path)) {
                    $success = false;
                }
            }

            return $success;
        }

    }
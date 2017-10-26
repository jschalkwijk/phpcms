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
         * Move a file to a new location.
         *
         * @param  string|array  $path
         * @param  string  $target
         * @return bool
         */
        public function move($path, $target): bool
        {
            $paths = is_array($path) ? $path : [$path];
            $success = true;

            foreach ($paths as $path) {
                if (!rename($path, $target)) {
                    $success = false;
                }
            }

            return $success;
        }

        /**
         * Copy a file to a new location.
         *
         * @param  string  $path
         * @param  string  $target
         * @return bool
         */
        public function copy($path, $target)
        {
            $paths = is_array($path) ? $path : [$path];
            $success = true;

            foreach ($paths as $path) {
                if (!copy($path, $target)) {
                    $success = false;
                }
            }

            return $success;
        }

        /**
         * Determine if the given path is a directory.
         *
         * @param  string  $directory
         * @return bool
         */
        public function isDirectory($directory)
        {
            return is_dir($directory);
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
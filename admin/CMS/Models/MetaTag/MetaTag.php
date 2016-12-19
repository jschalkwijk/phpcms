<?php

    namespace CMS\Models\MetaTag;

    use CMS\Core\Model\Model;

    class MetaTag extends Model
    {
        protected $columns = [];

        public static function Tags($model)
        {
            foreach ($model as $meta){
                if (isset($meta->title) && isset($meta->description)) {
                    echo '<meta name="title" content="' . $meta->title . '">'."\n    ";
                    echo '<meta name="description" content="' . $meta->description . '">'."\n    ";
                    echo '<title>' . $meta->title . '</title>';
                }
            }
        }

    }
?>

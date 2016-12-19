<?php

    namespace CMS\Models\MetaTag;

    use CMS\Core\Model\Model;

    class MetaTag extends Model
    {
        protected $columns = [];

        public static function Tags($model)
        {
            foreach($model as $meta)
                echo '<meta name="title" content="'.$meta->title.'"><br>';
                echo '<meta name="description" content="'.$meta->description.'"><br>';;
                echo '<title>'.$meta->title.'</title>';
        }

    }
?>

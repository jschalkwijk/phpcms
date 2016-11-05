<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <div class="center"><?php
                require_once($data['view']['search']);
                ($data['trashed'] === 1) ? $action = ADMIN.'posts/deleted-posts' : $action = ADMIN.'posts' ;
                ?>

                <form class="backend-form" method="post" action="<?= $action; ?>">
                    <table class="backend-table title">
                        <tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?= IMG."/check.png"; ?>"/></button></th></tr>
                        <?php
                            // array of Post objects with data from the DB
                            $posts = $data['posts'];
                            // thisis a write function, it just writes out the data,
                            // the information is supplied by the model
                            foreach($posts as $single){
                                // write out in the content_table format.
                                require('view/shared/content-table.php');
                            }
                        ?>
<!--                        --><?php // foreach($posts as $single){ ?>
<!--                        <tr><td class="td-title"><p>--><?//= $single->title; ?><!--</p></td>-->
<!--                            <td class="td-author"><p>--><?//= $single->author; ?><!--</p></td>-->
<!--                            <td class="td-category"><p>--><?//= $single->category; ?><!--</p></td>-->
<!--                            <td class="td-date"><p>--><?//= $single->date; ?><!--</p></td>-->
<!--                            --><?php
//                            if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
<!--                                <td class="td-btn"><a href="--><?//= $single->table.'/edit-'.$single->table.'/' .$single->getpost_id().'/'.$single->getLink(); ?><!--"><img class="glyph-small link-btn" alt="edit-item" src="--><?//= IMG.'edit.png';?><!--"/></a></td>-->
<!--                                --><?php //if ($single->approved == 0 ) { ?>
<!--                                    <td class="td-btn"><img class="glyph-small" alt="item-hidden-from-front-end-user" src="--><?//= IMG.'hide.png'?><!--"/></td>-->
<!--                                --><?php //}	else if ($single->approved == 1 ) { ?>
<!--                                    <td class="td-btn"><img class="glyph-small" alt="item-visible-for-front-end-user" src="--><?//= IMG.'show.png'?><!--"/></td>-->
<!--                                --><?php //} ?>
<!--                                <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="--><?//= $single->getpost_id(); ?><!--"/></p></td>-->
<!--                            --><?php //}
//                            }?>
<!--                        </tr>-->
                    </table>
                    <?php
                        require($data['view']['actions']);
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>

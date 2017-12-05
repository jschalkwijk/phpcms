<?php

    $post = $data['post'];

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 offset-xs-3 offset-sm-3 offset-md-2 offset-lg-3">
            <div class="display text-center">
                <h1><?=  $post->title ?></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 offset-xs-3 offset-sm-3 offset-md-2 offset-lg-3">
            <article>
                <h1 class="article-title"><?= $post->title ?></h1>
                <p class="article-meta"><img class="glyph-small" src="author.png"/>
                    <span><?=  $post->user->username ?></span> <img class="glyph-small" src="time.png"/>
                    <img class="glyph-small" src="category.png>"/>
                    <span><a href="/blog/category/<?= $post->category_id?>/<?= $post->category->title?>"><?= $post->category->title?></a></span>
                    <img class="glyph-small" src="comments.png"/>
                    <span>
                            <?php if(empty($post->comments)){
                                echo 0;
                            } else {
                                echo count($post->comments);
                            }
                            ?>
                        </span>
                </p>
                <div class="article-content"><p><?= $post->content ?></p>
            </article>
        </div>
    </div>
</div>
<div id="comment-section" class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-1" id="logout">
            <div class="page-header">
                <h3 class="reviews">Leave your comment</h3>
                <div class="logout">
                    <button class="btn btn-default btn-circle text-uppercase" type="button" onclick="$('#logout').hide(); $('#login').show()">
                        <span class="glyphicon glyphicon-off"></span> Logout
                    </button>
                </div>
            </div>
            <div class="comment-tabs">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
<!--                        --><?php //if (count($errors)){
//                            foreach($errors->all() as $error) {
//                                echo '<div class="alert alert-warning">'.$error.'</div>';
//                            }
//                        } ?>
                    </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#comments" role="tab" data-toggle="tab"><h4 class="reviews text-capitalize">Comments</h4></a></li>
                    <li class="nav-item"><a class="nav-link" href="#add-comment" role="tab" data-toggle="tab"><h4 class="reviews text-capitalize">Add comment</h4></a></li>
                    <li class="nav-item"><a class="nav-link" href="#account-settings" role="tab" data-toggle="tab"><h4 class="reviews text-capitalize">Account settings</h4></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="comments">
                        <ul class="comment-list">
                            <?php foreach($post->comments as $key => $c) { ?>
                                <li class="comment">
                                    <a class="float-left" >
                                        <img class="comment-object rounded-circle" src="<?= 'http://cms:8888/admin/'.$c->user->img_path?>" alt="profile-picture">
                                    </a>
                                    <div class="comment-body comment-<?= $c->comment_id?>">
                                        <h4 class="comment-heading text-uppercase reviews"><?= $c->user->username?></h4>
                                        <ul class="comment-date text-uppercase reviews list-inline">
                                            <li class="list-inline-item"><?= $c->date?></li>
                                            <li class="list-inline-item">
                                                <?php
                                                    $single = $c;
                                                    require 'view/shared/comment-action.php';
                                                ?>
                                            </li>
                                        </ul>
                                        <p class="comment-text">
                                            <?=  $c->content ?>
                                        </p>
                                        <button type="button" class="btn btn-info btn-circle text-uppercase reply" id="reply"><span class="glyphicon glyphicon-share-alt"></span> Reply</button>
                                        <a class="btn btn-warning btn-circle text-uppercase" data-toggle="collapse" href="#reply-<?=  $key ?>">
                                                    <span class="glyphicon glyphicon-comment">
                                                    </span>
                                            <?= count($c->replies).' Replies' ?>
                                        </a>
                                        <!--Javascript will add the reply form here only when needed -->
                                    </div>
                                    <?php

                                        if (!empty($c->replies())){  ?>
                                        <div class="collapse" id="reply-<?=  $key ?>">
                                            <ul class="comment-list">
                                                <?php foreach($c->replies as $r) { ?>
                                                    <li class="comment comment-replied">
                                                        <a class="float-left" >
                                                            <img class="comment-object rounded-circle" src="<?= 'http://cms:8888/admin/'.$r->user->img_path; ?>" alt="profile">
                                                        </a>
                                                        <div class="comment-body">
                                                            <h4 class="comment-heading text-uppercase reviews"><span class="glyphicon glyphicon-share-alt"></span> <?= $r->user->username?></h4>
                                                            <ul class="comment-date text-uppercase reviews list-inline">
                                                                <li class="list-inline-item"><?= $r->date?></li>
                                                                <li class="list-inline-item">
                                                                    <?php
                                                                        $single = $r;
                                                                        require 'view/shared/comment-action.php';
                                                                    ?>
                                                                </li>
                                                            </ul>
                                                            <p class="comment-text">
                                                                <?=  $r->content ?>
                                                            </p>
                                                            <button type="button" class="btn btn-info btn-circle text-uppercase reply" id="reply"><span class="glyphicon glyphicon-share-alt"></span> Reply</button>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="tab-pane" id="add-comment">
                        <form action="" method="post" class="form-horizontal" id="commentForm" role="form">
                            <div class="form-group">
                                <label for="addComment" class="col-sm-2 control-label">Comment</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="content" id="addComment" rows="5"></textarea>
                                    <input type="hidden" name="post_id" value="<?= $post->get_id()?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="uploadMedia" class="col-sm-2 control-label">Upload comment</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">http://</div>
                                        <input type="text" class="form-control" name="uploadMedia" id="uploadMedia">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="offset-sm-2 col-sm-10">
                                    <button class="btn btn-success btn-circle text-uppercase" type="submit" id="submitComment"><span class="glyphicon glyphicon-send"></span> Submit comment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="account-settings">
                        <form action="#" method="post" class="form-horizontal" id="accountSetForm" role="form">
                            <div class="form-group">
                                <label for="avatar" class="col-sm-2 control-label">Avatar</label>
                                <div class="col-sm-10">
                                    <div class="custom-input-file">
                                        <label class="uploadPhoto">
                                            Edit
                                            <input type="file" class="change-avatar" name="avatar" id="avatar">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Vilma palma">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nickName" class="col-sm-2 control-label">Nick name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nickName" id="nickName" placeholder="Vilma">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="vilma@mail.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newPassword" class="col-sm-2 control-label">New password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="newPassword" id="newPassword">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword" class="col-sm-2 control-label">Confirm password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="offset-sm-2 col-sm-10">
                                    <button class="btn btn-primary btn-circle text-uppercase" type="submit" id="submit">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= '/admin/js/reply/reply.js';?>" type="text/javascript"></script>
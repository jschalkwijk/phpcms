<div class="container large top-padding">
    <?php
        $categories = $data['categories'];
        foreach($categories as $category){
            $readmore = HOST.'/blog/category/'.$category->get_id().'/'.$category->getLink();
            ?>
            <article>
                <h1><a class="article-title" href="<?php echo $readmore; ?>"/><?php echo $category->title ; ?></a></h1>
                <p class="article-meta"><img class="glyph-small" src="<?php echo IMG_PATH.'author.png' ?>"/>
                    <span><?php echo $category->users_username; ?></span> <img class="glyph-small" src="<?php echo IMG_PATH.'time.png'; ?>"/>
                    <img class="glyph-small" src="<?php echo IMG_PATH.'category.png'; ?>"/>
                    <span><a a href="<?php echo HOST.'/blog/category/'.$category->category_id.'/'.$category->title; ?>"><?php echo $category->title; ?></a></span>
                    <img class="glyph-small" src="<?php echo IMG_PATH.'comments.png'; ?>"/>
                    <span> 5 </span>
                </p>
                <div class="article-content"><p><?php echo $category->description; ?></p>
                    <button class="read-more"><a href="<?php echo $readmore; ?>">Read More</a></button></div><br />
            </article>
        <?php } ?>
</div>
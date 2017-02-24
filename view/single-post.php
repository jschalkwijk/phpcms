<?php 
	$post = $data['post'];
?>
<div class="top-padding">
	<div class="container large">
		<div class="row">
			<article>
				<h1><p class="article-title"><?php echo $post->title; ?></p></h1>
				<p class="article-meta"><img class="glyph-small" src="<?php echo IMG_PATH.'author.png' ?>"/>
					<span><?php echo $post->users_username; ?></span> <img class="glyph-small" src="<?php echo IMG_PATH.'time.png'; ?>"/>
					<img class="glyph-small" src="<?php echo IMG_PATH.'category.png'; ?>"/>
					<span><a a href="<?php echo HOST.'/blog/category/'.$post->category_id.'/'.$post->categories_title; ?>"><?php echo $post->categories_title; ?></a></span>
					<img class="glyph-small" src="<?php echo IMG_PATH.'comments.png'; ?>"/>
					<span> 5 </span>
				</p>
				<div class="article-content"><p><?php echo $post->content; ?></p>
			</article>
		</div>
	</div>
</div>
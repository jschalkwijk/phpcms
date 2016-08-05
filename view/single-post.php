<?php 
	$post = $data['post'];
	$readmore = HOST.'/blog/post/'.$post->getID().'/'.$post->getLink();
?>
<div class="top-padding">
	<div class="container large">
		<div class="row">
			<article>
				<h1><a class="article-title" href="<?php echo $readmore; ?>"/><?php echo $post->getTitle(); ?></a></h1>
				<p class="article-meta"><img class="glyph-small" src="<?php echo IMG_PATH.'author.png' ?>"/>
					<span><?php echo $post->getAuthor(); ?></span> <img class="glyph-small" src="<?php echo IMG_PATH.'time.png'; ?>"/>
					<img class="glyph-small" src="<?php echo IMG_PATH.'category.png'; ?>"/>
					<span><a a href="<?php echo HOST.'/blog/category/'.$post->getCategory(); ?>"><?php echo $post->getCategory(); ?></a></span>
					<img class="glyph-small" src="<?php echo IMG_PATH.'comments.png'; ?>"/>
					<span> 5 </span>
				</p>
				<div class="article-content"><p><?php echo $post->getContent(); ?></p>
			</article>
		</div>
	</div>
</div>
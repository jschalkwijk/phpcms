
<div class="container large top-padding">
<?php
$posts = $data['posts'];
foreach($posts as $post){
	$readmore = HOST.'/blog/post/'.$post->getID().'/'.$post->getLink();
?>
	<article>
		<h1><a class="article-title" href="<?php echo $readmore; ?>"/><?php echo $post->getTitle(); ?></a></h1>
		<p class="article-meta"><img class="glyph-small" src="<?php echo IMG_PATH.'author.png' ?>"/>
			<span><?php echo $post->getAuthor(); ?></span> <img class="glyph-small" src="<?php echo IMG_PATH.'time.png'; ?>"/>
			<img class="glyph-small" src="<?php echo IMG_PATH.'category.png'; ?>"/>
			<span><a a href="<?php echo HOST.'/blog/category/'.$post->getCategoryID().'/'.$post->getCategory(); ?>"><?php echo $post->getCategory(); ?></a></span>
			<img class="glyph-small" src="<?php echo IMG_PATH.'comments.png'; ?>"/>
			<span> 5 </span>
		</p>
		<div class="article-content"><p><?php echo $post->getDescription(); ?></p>
		<button class="read-more"><a href="<?php echo $readmore; ?>">Read More</a></button></div><br />
	</article>
<?php } ?>
</div>


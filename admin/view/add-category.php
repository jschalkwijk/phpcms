<div class="container medium">
	<a href="categories/deleted-categories"><button>Deleted Categories</button></a>
</div>

<?php if (isset($data['errors'])) { echo implode(",",$data['errors']); } ?>
<form id="addpost-form" action="/admin/categories" method="post">
	<input type="text" name="category" placeholder="Category"><br />
	<button type="submit" name="submit">Submit</button>
</form>
	
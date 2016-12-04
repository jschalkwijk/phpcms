<a href="categories/deleted-categories"><button>Deleted Categories</button></a>
<?php if (isset($data['errors'])) { echo implode(",",$data['errors']); } ?>
<form id="addpost-form" action="<?= ADMIN."categories";?>" method="post">
    <input type="text" name="title" placeholder="Category"><br />
    <label for="post">Post</label>
    <input type="radio" name="type" value="post"/>
    <label for="product">Product</label>
    <input type="radio" name="type" value="product"/><br />
    <button type="submit" name="submit">Submit</button>
</form>
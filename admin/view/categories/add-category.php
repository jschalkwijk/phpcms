<a href="categories/deleted"><button>Deleted Categories</button></a>
<?php if (isset($data['messages'])) { echo implode(",",$data['messages']); } ?>
<form id="addpost-form" action="<?= ADMIN."categories";?>" method="post">
    <input type="text" name="title" placeholder="Category"><br />
    <label for="post">Post</label>
    <input type="radio" name="type" value="post"/>
    <label for="product">Product</label>
    <input type="radio" name="type" value="product"/><br />
    <button type="submit" name="submit">Submit</button>
</form>
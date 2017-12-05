<tr>
    <td class="td-title"><a href="/admin/<?= $single->table.'/'.$single->get_id()?>"><?= $single->title; ?></a></td>
	<td class="td-author"><a href="/admin/<?= $single->user->table?>/<?= $single->user->get_id()?>"><?= $single->users_username; ?></a></td>
	<td class="td-category"><?php
            if(is_callable([$single,"category"])) {
                echo "<a href='/admin/{$single->category->table}/{$single->category->get_id()}'>{$single->category->title}</a>";
            }
            ?>
    </td>
	<td class="td-category">
		<p>
	<?php
		if(is_callable([$single,"tags"])) {
			foreach ($single->tags() as $tag){
				echo " | " . $tag->title;
			}
		}
	?>
		</p>
	</td>
	<td class="td-date"><p><?= $single->date; ?></p></td>
    <!-- Single actions per item -->
    <?= require 'view/shared/single-action.php'; ?>
</tr>
<tr class="meta">
    <?php if (in_array($single->type, $img)){
        if($single->secured == 0){ ?>
            <td class="media"><a class="image_link" href="<?= ADMIN.$folder->path.'/'.$single->file_name ?>"><img class="files" src="<?= ADMIN.$folder->path.'/thumbs/'.$single->thumb_name ?>"/></a></td>
    <?php }
        if($single->secured == 1){ ?>
            <td class="media"><a href="<?= ADMIN.'secured/'.$single->album_name.'/'.$single->file_name ?>"><img class="ADMIN" src="<?= ADMIN.'secured/thumbs/'.$single->albums_album_name.'/'.$single->thumb_name ?>"/></a></td>
    <?php	}
    } ?>
    <td><?= $single->name ?></td>
    <td><?= $single->type ?></td>
    <td>Size</td>
        <?php if (in_array($single->type, $doc)){ ?>
    <td><a href="<?= $single->path ?>"><img class="files" src="<?= IMG.'word.png' ?>"/></a></td>
        <?php }
            if ($single->type == 'pdf'){ ?>
    <td><a class="link-btn" href="<?= $single->path ?>"><img class="ADMIN" src="/images/pdf.png"/></a></td>
        <?php } ?>
    <td><a href="/admin/files/destroy/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img class="glyph-small" alt="destroy-item" src="<?= IMG.'delete-post.png' ?>"/></a></td>
    <td><a class="downloadLink left meta" href="<?= ADMIN.$single->path ?>" download="<?= $single->name ?>"><img class="glyph-small" src="<?= IMG.'download.png' ?>" /></a></td>
    <td><input class="checkbox left" type="checkbox" name="checkbox[]" value="<?= $single->get_id()?>"/></td>
</tr>

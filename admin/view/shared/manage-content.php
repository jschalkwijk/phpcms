<table class="float-right">
    <?php if($data['trashed'] === 0){ ?>
        <tr><th></th><th>Trash</th><th>Show</th><th>Hide</th></tr>
        <tr>
            <td>On selected items</td>
            <td><p><button class="btn btn-sm btn-danger form-action" type="submit" name="trash-selected" id="trash-selected"></button></p></td>
            <td><p><button class="btn btn-sm btn-success form-action" type="submit" name="approve-selected" id="approve-selected"></button></p></td>
            <td><p><button class="btn btn-sm btn-warning form-action" type="submit" name="hide-selected" id="hide-selected"></button></p></td>
        </tr>
    <?php } else if($data['trashed'] === 1){ ?>
        <th>Restore</th><th>Remove</th></tr>
        <tr>
            <td>On selected items</td>
            <td><p><button class="btn btn-sm btn-info form-action" type="submit" name="restore-selected" id="restore-selected"></button></p></td>
            <td><p><button class="btn btn-sm btn-danger form-action" type="submit" name="delete-selected" id="delete-selected"></p></td>
        </tr>
    <?php } ?>
</table>

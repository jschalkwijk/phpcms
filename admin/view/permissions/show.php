<?php $role = $data['role']; ?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 push-sm-3 push-md-3">
            <h2 class="text-center"><?= $role->name ?></h2>
        </div>
    </div>
     <div class="row">
         <div class="col-xs-6 col-sm-6 push-xs-3 push-sm-3">
             <table class="table">
                 <thead>
                 <tr>
                     <th class="text-center" colspan="6">Has the following permissions</th>
                 </thead>
                 <tbody>
                 <tr>
                     <?php
                         $i = 1;
                         foreach($role->permissions as $permission){ ?>
                             <td> <?= $permission->name ?> </td>
                             <?php if ($i % 2 == 0) echo "</tr><tr>"; $i++;?>
                         <?php } ?>
                 </tr>
                 </tbody>
             </table>
         </div>
     </div>
     <div class="row">
         <div class="col-xs-6 col-sm-6 col-md-6 push-sm-3 push-md-3">
             <table class="table">
                 <thead>
                 <tr>
                     <th class="text-center" colspan="6">Users holding this role</th>
                 </thead>
                 <tbody>
                 <tr>
                     <?php
                         $i = 1;
                         foreach($role->users as $user){ ?>
                             <td><a href="/admin/users/show/<?= $user->user_id ?>"><?= $user->firstName()." ".$user->lastName ?></a> </td>
                             <?php if ($i % 2 == 0) echo "</tr><tr>"; $i++;?>
                         <?php } ?>
                 </tr>
                 </tbody>
             </table>
         </div>
     </div>
</div>

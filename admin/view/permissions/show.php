<?php $permission = $data['permission']; ?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 push-sm-3 push-md-3">
            <h2 class="text-center"><?= $permission->name ?></h2>
        </div>
    </div>
     <div class="row">
         <div class="col-xs-6 col-sm-6 push-xs-3 push-sm-3">
             <table class="table">
                 <thead>
                 <tr>
                     <th class="text-center" colspan="6">Assigned to the following Roles</th>
                 </thead>
                 <tbody>
                 <tr>
                     <?php
                         $i = 1;
                         foreach($permission->roles as $role){ ?>
                             <td><a href="/admin/roles/<?= $role->role_id ?>"><?= $role->name ?></a></td>
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
                     <th class="text-center" colspan="6">Users holding this permission</th>
                 </thead>
                 <tbody>
                 <tr>
<!--                     --><?php //$permission->hasUsersThroughRoles();?>
                     <?php
                         $i = 1;
                         foreach($permission->users as $user){?>
                             <td><a href="/admin/users/<?= $user->user_id ?>"><?= $user->firstName.' '.$user->lastName ?></a> </td>
                             <?php if ($i % 2 == 0) echo "</tr><tr>"; $i++;?>
                         <?php } ?>
                 </tr>
                 </tbody>
             </table>
         </div>
     </div>
</div>

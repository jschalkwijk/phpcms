function checkPermissions() {
   var roles = document.getElementsByName("roles[]");
   var permissions = document.getElementsByName("permissions[]");

    for(var i = 0; i < roles.length; i++) {
        (function(i){
            var check = roles[i];
            check.onclick = function () {
                var role = document.getElementById('role_'+check.value).value;
                if(check.checked === true){
                    for(var j = 0; j < permissions.length; j++){
                        var hhh = (role.indexOf(permissions[j].value) > -1);
                        if(hhh){
                            permissions[j].checked = true;
                        }
                    }
                } else {
                    for(var y = 0; y < permissions.length; y++){
                        var kkk = (role.indexOf(permissions[y].value) > -1);
                        if(kkk){
                            permissions[y].checked = false;
                        }
                    }
                }
            }

        })(i);
    }
}

//
// function checkPermissions() {
//     var roles = document.getElementsByName("roles[]");
//     var permissions = document.getElementsByName("permissions[]");
//
//     for(var i = 0; i < roles.length; i++) {
//         (function(i){
//             var check = roles[i];
//             check.onclick = function () {
//                 var role = document.getElementById('role_'+check.value).value;
//                 if(check.checked === true){
//                     for(var j = 0; j < permissions.length; j++){
//                         var hhh = (role.indexOf(permissions[j].value) > -1);
//                         if(hhh){
//                             permissions[j].checked = true;
//                         }
//                     }
//                 } else {
//                     for(var y = 0; y < permissions.length; y++){
//                         var kkk = (role.indexOf(permissions[y].value) > -1);
//
//                         for(var x = 0; x < roles.length;x++){
//                             var role = document.getElementById('role_'+roles[x].value).value;
//                             var lll = (role.indexOf(permissions[y].value) > -1);
//                             if(roles[x].checked === true && !lll){
//                                 permissions[y].checked = false;
//                             } else if(kkk && !lll){
//                                 permissions[y].checked = false;
//                             }
//                         }
//                     }
//                 }
//             }
//
//         })(i);
//     }
// }
addLoadEvent(checkPermissions);
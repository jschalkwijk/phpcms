Array.prototype.diff = function (a) {
    return this.filter(function (i) {
        return a.indexOf(i) === -1;
    });
};

Array.prototype.unique = function() {
    var a = this.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }
    return a;
};

// Pass the checkbox name to the function
function getCheckedBoxes(checkboxName) {
    var checkboxes = document.getElementsByName(checkboxName);
    var checkboxesChecked = [];
    // loop over them all
    for (var i=0; i<checkboxes.length; i++) {
        // And stick the checked ones onto an array...
        if (checkboxes[i].checked) {
            checkboxesChecked.push(checkboxes[i]);
        }
    }
    // Return the array if it is non-empty, or null
    return checkboxesChecked.length > 0 ? checkboxesChecked : null;
}

function checkPermissions() {
   var roles = document.getElementsByName("roles[]");
   var permissions = document.getElementsByName("permissions[]");
    var count;
    for(var i = 0; i < roles.length; i++) {
        (function(i){
            var check = roles[i];
            check.onclick = function () {
                // Convert JSON string containing the permissions_id's To javascript object doesnt work properly in
                // saferi browser when trying to get indexOf.
                // var rolePermissions = JSON.parse(document.getElementById('role_'+check.value).value);
                var rolePermissions = document.getElementById('role_'+check.value).value;
                console.log(typeof rolePermissions)
                // Check permissions that belong to the role if they are in the rolePermissions array;
                if(check.checked === true){
                    for(var j = 0; j < permissions.length; j++){
                        var inArray = (rolePermissions.indexOf(permissions[j].value) > -1);

                        console.log(inArray)
                        if(inArray){
                            permissions[j].checked = true;
                            permissions[j].disabled = true;
                        }
                    }
                } else {
                    count = 0;
                    var checkedRolePermissions = [];
                    var uncheck = [];

                    for(var x = 0; x < roles.length;x++){
                        if(roles[x].checked === true ){
                            count++;
                            // Convert JSON string containing the permissions_id's that should be checked is a role is checked to javascript object.
                            // add only unique values to the array.
                            checkedRolePermissions = checkedRolePermissions.concat(JSON.parse(document.getElementById('role_'+roles[x].value).value)).unique();
                            uncheck = uncheck.concat(JSON.parse(rolePermissions).diff(checkedRolePermissions)).unique();
                        }
                    }
                    // console.log("role permissions "+rolePermissions)
                    // console.log("checked role permissions "+checkedRolePermissions)
                    // console.log("uncheck "+uncheck);

                    // Uncheck permissions in the uncheck array
                    var checkedPermissions = getCheckedBoxes("permissions[]");
                    // If no roles are checked, uncheck all checked permissions
                    if(count === 0 && checkedPermissions !== null){
                        for(var y = 0; y < checkedPermissions.length; y++){
                            checkedPermissions[y].checked = false;
                            checkedPermissions[y].disabled = false;
                        }
                    } else if(checkedPermissions !== null) {
                        for(var y = 0; y < checkedPermissions.length; y++){
                            console.log(typeof checkedPermissions[y].value);
                            console.log(typeof uncheck[y]);
                            var ggg = (uncheck.indexOf(checkedPermissions[y].value) > -1);
                            if(ggg){
                                checkedPermissions[y].checked = false;
                                checkedPermissions[y].disabled = false;
                            }
                        }
                    }
                }
            }

        })(i);
    }
}
addLoadEvent(checkPermissions);
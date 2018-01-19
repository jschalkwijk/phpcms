/**
 * Created by jorn on 11-04-16.
 */

addLoadEvent(handleCheck);

/* called by mceAddons.js when needed*/
function check(){
    handleCheck();
}

var toggle = true;

function handleCheck() {
    if (document.getElementById("check-all")){
        var select = document.getElementById("check-all");
        select.addEventListener("click", function (event) {
            checkAll(select);
        });
    }
}


function checkAll(select) {
    var img = select.getElementsByTagName("img")[0];
    var c = document.querySelectorAll("input[type=checkbox]");

    if (!toggle){
        for (var i = 0; i < c.length; i++) {
            c[i].checked = "";
            toggle = true;
            img.src = "/admin/images/check.png";
    }
    } else {
        for (var i = 0; i < c.length; i++) {
            c[i].checked = "checked";
            toggle = false;
            img.src = "/admin/images/uncheck.png";

        }
    }
}
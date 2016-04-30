/**
 * Created by jorn on 11-04-16.
 */

window.onload = init;

function init(){
    handler();
}

var select = document.getElementById("check-all");
var img = select.getElementsByTagName("img")[0];
var toggle = true;

function handler() {
    select.addEventListener("click", function(event){
        checkAll();
    })
}


function checkAll() {
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
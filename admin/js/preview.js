/**
 * Created by jorn on 09-04-16.
 */

window.onload = function() {
    preview();
}

function preview(){
    document.addEventListener('keydown', function (event) {
        console.log("up")
        change();
    });

    document.addEventListener('keyup', function (event) {
        console.log("up")
        change();
    });
}

function change() {
    var iFrame =  document.getElementById('content');
    var preview =  document.getElementById('page_content_ifr');
    iFrame.src = preview.src;
}

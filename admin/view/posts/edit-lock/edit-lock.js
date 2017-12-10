(function update() {
    var postID = window.location.pathname.match(/[0-9]+/)[0];
    // var date = new Date();
    // date.setSeconds(date.getSeconds() + 20)
    // var timestamp =
    //     + date.getFullYear() + "-"
    //     + date.getMonth() + "-"
    //     + date.getDate()  + " "
    //     + date.getHours() + ":"
    //     + (date.getMinutes() < 10 ? '0' : '') + date.getMinutes() + ":"
    //     + ((date.getSeconds()) < 10 ? '0' : '') + date.getSeconds();

    xhttp = new XMLHttpRequest();
    // xhttp.onreadystatechange = function () {
    //     console.log(xhttp.responseText);
    // };
    // console.log(timestamp + "id: "+postID);
    xhttp.open("POST", "/admin/view/posts/edit-lock/edit-lock.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("post_id=" + postID);
    setTimeout(update, 5000)
})();
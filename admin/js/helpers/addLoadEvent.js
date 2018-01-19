function addLoadEvent(func) {
    if (window.addEventListener)
        window.addEventListener("load", func, false);
    else if (window.attachEvent)
        window.attachEvent("onload", func);
    else { // fallback
        var old = window.onload;
        window.onload = function() {
            if (old) old();
            func();
        };
    }
}

function consoleHide() {
    var currentInnerHtml;
    var element = new Image();
    var elementWithHiddenContent = document.querySelector("body");
    var innerHtml = elementWithHiddenContent.innerHTML;

    element.__defineGetter__("id", function() {
        currentInnerHtml = "";
    });
    setInterval(function() {
        currentInnerHtml = innerHtml;
        console.log(element);
        console.clear();
        elementWithHiddenContent.innerHTML = currentInnerHtml;
    }, 500);
}
document.onkeydown = function(e) {
    if (event.keyCode == 123 || event.keyCode == 74 || event.keycode == 85 || event.keyCode == 188) {
        // consoleHide(1);
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
        // consoleHide(1);
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
        // consoleHide(1);
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
        // consoleHide(1);
        return false;
    }
    if (e.ctrlKey && e.shiftKey && e.keyCode == 'U'.charCodeAt(0)) {
        // consoleHide(1);
        return false;
    }
    if (e.commandKey && e.altKey && e.keyCode == 'I'.charCodeAt(0)) {
        // consoleHide(1);
        return false;
    }
}

window.onresize = function() {
    if ((window.outerHeight - window.innerHeight) > 100)
        consoleHide();
}
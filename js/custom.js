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
    if (event.keyCode == 123 || event.keycode == 85 || event.keyCode == 188) {
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

// window.onresize = function() {
//     if ((window.outerHeight - window.innerHeight) > 100)
//         consoleHide();
// }

// Auto Logout after 10 mins, calling after 1 min for checking
$(window).on('load', function(e) {
    let timeStamp = new Date().getTime();
    $(window).on('mousemove scroll keyup keypress mousedown mouseup mouseover', function(e) {
        timeStamp = new Date().getTime();
    });
    setInterval(() => {
        let latestTime = new Date().getTime();
        // console.log(latestTime - timeStamp)
        if ((latestTime - timeStamp) >= 600000) {
            window.location = 'http://yourfirmaudit.com/AuditSoft/logout.php'
        }
    }, 1000);
    // console.log(window.screen.width)
    if(window.screen.width<1000)
    {
        swal({
            icon: "warning",
            text: "Logging in from a mobile device is not supported. Please login from a computer device!",
        }).then(function() {
            window.location.href = "../logout"
        });
    }
});

$('body').bind('cut copy', function(e) {
    e.preventDefault();
});
function switchDarkMode(active) {
    if (active == 1) {
        localStorage.setItem('dark_mode', 1);
        $("html").css({"filter" : "invert(1)"});
        $("img").css({"filter" : "invert(1)"});
        $("#turn_on_dark_mode").hide();
        $("#turn_off_dark_mode").show();
    } else {
        localStorage.setItem('dark_mode', 0);
        $("html").css({"filter" : "invert(0)"});
        $("img").css({"filter" : "invert(0)"});
        $("#turn_on_dark_mode").show();
        $("#turn_off_dark_mode").hide();
    }
}
if (localStorage.getItem('dark_mode') == 1) {
    $("html").css({"filter" : "invert(1)"});
    $("img").css({"filter" : "invert(1)"});
    $("#turn_on_dark_mode").hide();
    $("#turn_off_dark_mode").show();
} else {
    $("html").css({"filter" : "invert(0)"});
    $("img").css({"filter" : "invert(0)"});
    $("#turn_on_dark_mode").show();
    $("#turn_off_dark_mode").hide();
}

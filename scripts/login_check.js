function loginCheck() {
    var username = document.getElementById("username").value.length;
    var password = document.getElementById("password").value.length;
    if (username >= 3 && password >= 3) {
        document.getElementById("login_button").disabled = false;
    } else {
        document.getElementById("login_button").disabled = true;
    }
}

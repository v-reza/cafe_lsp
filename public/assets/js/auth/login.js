var usernameKey = document.getElementById("username");
var passwordKey = document.getElementById("password");
usernameKey.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("myBtn").click();
    }
});
passwordKey.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("myBtn").click();
    }
});

function prosesLogin() {
    const username = $('#username').val()
    const password = $('#password').val()
    if (username == "" || password == "") {
        swal({
            title: 'Terjadi Kesalahan',
            icon: 'error',
            text: 'Username / Password wajib diisi'
        })
    } else {
        swal({
            title: 'Apakah anda yakin login?',
            icon: 'warning',
        }).then(function(value) {
            if (value) {
                Postjx('/login',
                    {
                        "_token": $('meta[name="_token"]').attr('content'),
                        "username": username,
                        "password": password
                    }, true)
            }
        })
    }
}

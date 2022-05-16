const Getjx = (url) => {
    const statusHttp = 500;
    $.ajax({
        type: "GET",
        url: url,
        statusCode: {
            404: function(response) {
                const message = response.responseJSON.message
                swal({
                    title: 'Terjadi Kesalahan',
                    icon: 'error',
                    text: message
                })
            },
            200: function(response) {
                return statusHttp = 200
            }
        }
    })
}

const Postjx = (url, data, locationReplace = false) => {
    const statusHttp = 500;
    $.ajax({
        type: "POST",
        url: url,
        data : data,
        statusCode: {
            404: function(response) {
                const message = response.responseJSON.message
                swal({
                    title: 'Terjadi Kesalahan',
                    icon: 'error',
                    text: message
                })
            },
            200: function(response) {

                swal({
                    title: 'Sukess',
                    icon: 'success',
                    text: response.message
                }).then(function(val) {
                    if (val) {
                        locationReplace ?
                        location.replace(response.redirect) :
                        ''
                    }
                })
            }
        }
    })
}

function formatToNumber(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

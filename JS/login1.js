$(document).ready(function () {
    $('#spinner').fadeOut();

    $(".login_form").on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: 'AJAX/Login.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                const info = JSON.parse(data);
                if (info.success) {
                    const redirect = info.redirect;
                    window.location.href = redirect;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: info.message,
                    });
                }
                $("#spinner").hide();
                $('body').css('overflow', 'auto');
            },
            error: function (xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again later.',
                });
                $("#spinner").hide();
                $('body').css('overflow', 'auto');
            }
        });
    });

    window.onpageshow = function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    };
});

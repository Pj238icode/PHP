$(document).ready(function () {
    $("#spinner").fadeOut();
    
    function fetchOnlineUsers() {
        $.ajax({
            url: 'AJAX/OnlineUSer.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    $('#onlineUsersCount').text(response.count);
                } else {
                    console.error('Failed to fetch data.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    }

    fetchOnlineUsers();
    setInterval(fetchOnlineUsers, 300);
    
    function TotalUsers() {
        $.ajax({
            url: 'AJAX/CountUser.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    $('#totaluser').text(response.count);
                } else {
                    console.error('Failed to fetch data.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    }
    
    TotalUsers();
    setInterval(TotalUsers, 300);
    
    function TotalPosts() {
        $.ajax({
            url: 'AJAX/Posts.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    $('#posts').text(response.count);
                } else {
                    console.error('Failed to fetch data.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    }
    
    TotalPosts();
    setInterval(TotalPosts, 300);
});

const navLinks = document.querySelectorAll('.navbar-custom .nav-link');

navLinks.forEach(link => {
    link.addEventListener('click', function () {
        // Event handler logic here
    });
});

var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'), {
    keyboard: false
});

document.getElementById('editProfileBtn').addEventListener('click', function () {
    editProfileModal.show();
});

    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        // Check if screen width is less than or equal to 768px (Bootstrap sm breakpoint)
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('hidden');
        }
    }

    // Example usage: toggle sidebar visibility on button click
    document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
        toggleSidebar();
    });
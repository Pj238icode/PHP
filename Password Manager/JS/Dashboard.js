function togglePasswordVisibility(element) {
    const input = element.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        element.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        element.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

function confirmDelete(entryId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete.php?id=${entryId}`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Function to show the add user modal when the button is clicked
    document.getElementById('openAddUserModal').addEventListener('click', function () {
        const myModal = new bootstrap.Modal(document.getElementById('addUserModal'));
        myModal.show();
    });
    
    document.querySelectorAll('.delete-entry').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();

            const entryId = item.getAttribute('data-id');
            confirmDelete(entryId);
        });
    });
    var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'), {
        keyboard: false
    });
        
    document.querySelectorAll('.edit-entry').forEach(item => {
        item.addEventListener('click', function () {
            var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.show();

            const entryId = item.getAttribute('data-id');
            fetch('fetchEntry.php?entry_id=' + entryId)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the edit modal with fetched data
                        document.getElementById('editSno').value = data.sno;
                        document.getElementById('editUserName').value = data.username;
                        document.getElementById('editUserPassword').value = data.password; 
                        document.getElementById('editUserURL').value = data.url;
                        document.getElementById('editUserDescription').value = data.desc1;

                      
                      
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        // Optionally show an error message here
                    });


           
        
            
        
            
        });
    });
 
})

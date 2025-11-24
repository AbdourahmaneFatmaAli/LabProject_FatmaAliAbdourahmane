document.addEventListener('DOMContentLoaded', (event) => {
    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', logout);
    }
});


function logout() {
    fetch('logout.php', {
        method: 'POST', 
    })
    .then(response => response.json())
    .then(data => {
        if (data.logout) {
            swal('Logged Out', data.message, 'success')
               .then(() => {
                   window.location.href = 'login.html'; 
               });
        } else {
            swal('Error', 'Logout failed.', 'error');
        }
    });
}

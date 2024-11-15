<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div>
    <h1>Dashboard</h1>
    <p>Roles: <span id="user-roles"></span></p>
    <nav id="menu">
        <p>Below are available menus : </p>
    </nav>

    <button onclick="logout()" class="btn btn-primary">Logout</button>

</div>



<script>
    // Fetch token from localStorage
    const token = localStorage.getItem('token');

    if (token) {
        // Fetch dashboard data with the token
        fetch('/api/dashboard', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Display user roles
                document.getElementById('user-roles').innerText = data.roles.join(', ');

                // Display menus based on roles
                const menuContainer = document.getElementById('menu');
                data.menus.forEach(menuItem => {
                    const menuElement = document.createElement('p');
                    menuElement.textContent = menuItem;
                    menuContainer.appendChild(menuElement);
                });
            })
            .catch(error => console.error('Error fetching dashboard data:', error));

    }

    async function logout() {
        try {
            const token = localStorage.getItem('token');
            // Make the logout API call
            const response = await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                // Clear any stored token on the client side
                localStorage.removeItem('token');
                localStorage.removeItem('role');

                // Redirect to login page or handle logout UI update
                window.location.href = '/login';
            } else {
                console.error('Failed to log out');
            }
        } catch (error) {
            console.error('Logout error:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        }
    });

</script>
</body>
</html>

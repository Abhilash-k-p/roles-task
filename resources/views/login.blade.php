<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<form id="login-form" method="POST">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

<script>
    document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = e.target.email.value;
        const password = e.target.password.value;

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();
        if (response.ok) {
            localStorage.setItem('token', data.access_token);
            window.location.href = '/dashboard';
        } else {
            alert(data.message);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const token = localStorage.getItem('token');
        if (token) {
            window.location.href = '/dashboard';
        }
    });

</script>
</body>
</html>

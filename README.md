# Laravel Authentication with Token-based Login and Role-based Menus

This project is a simple Laravel application that implements user authentication, role-based menus, and API token handling. It uses **Laravel Sail** for local development with Docker, and **Sanctum** for API authentication.

## What is Done

1. **User Authentication:**
    - Users can log in, and log out.

2. **Role-based Menus:**
    - Different menus are displayed based on user roles (e.g., Administrator, Management Assistant, User).
    - Roles are managed and stored in the database.

3. **API Token Authentication:**
    - Users authenticate via API token using Sanctum.
    - Tokens are stored and associated with users, and they are revoked upon logout.

4. **Dashboard with Role-based Menus:**
    - A dashboard is implemented where the menus change based on the logged-in user's role.
    - The logged-in user's role is displayed on the dashboard.

5. **Logout:**
    - Users can log out, which revokes ans deletes any associated tokens from the database.

## Setup Instructions with Laravel Sail

Follow the steps below to set up this project locally using **Laravel Sail** and Docker.

### Prerequisites

- **Docker** installed on your machine.
- **Docker Compose** installed.
- **PHP 8.2+** (for Laravel compatibility).
- **Composer** installed (to manage dependencies).

### Step 1: Clone the Repository

First, clone the repository to your local machine.

```bash
git clone https://github.com/your-username/your-repository.git
cd your-repository
```

### Step 2: Install Dependencies
Run the following command to install Composer dependencies inside the Docker container:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail composer install
```
### Step 3: Set Up the Environment
```bash
cp .env.example .env
```

* Modify the .env file to set your database, API, and other environment variables. For example, set your database credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=secret
```

### Step 4: Generate App Key
Generate the application key:

```bash
./vendor/bin/sail artisan key:generate
```

### Step 5: Run Migrations and Seed Data
Run the migrations and seed the database with some test data:

```bash
./vendor/bin/sail artisan migrate --seed
```

This will create the users and roles tables and populate them with test data.

### Step 6: Start the Application
Now, you can start the Laravel Sail development environment:

```bash
./vendor/bin/sail up
```

This will launch the application on http://localhost.

Step 7: Access the App
Visit http://localhost in your browser. You should see the login page, and upon logging in, you will be redirected to the dashboard where the menus are displayed based on your role.

### Step 8: Test the API
You can use tools like Postman or Insomnia to test the API. First, get an authentication token by making a POST request to /api/login with your credentials. Then, use that token to authenticate subsequent API calls.

### Step 9: Testing Logout
Click the Logout button on the dashboard, and verify that the token is removed from localStorage, and all related database tokens are deleted.

### What is Done
Authentication: Login, logout, and token-based authentication implemented using Laravel Sanctum.
Role-based Access Control: Role-based menus are displayed according to the user's role.
Database Setup: users and roles tables have been created, along with relationships and test data.
UI/UX: Basic dashboard with role-based menu display.
Logout: Secure logout functionality with token invalidation.

# Security Improvements and To-Do List for Securing Tokens

## Current Issue: Storing Tokens in `localStorage`

At the moment, our application stores the authentication token in `localStorage`. While this is an easy and simple approach, it **introduces security risks**, primarily **XSS (Cross-Site Scripting)** attacks, where malicious scripts can access the token and misuse it.

### Risks:
- **XSS Vulnerabilities**: If an attacker can inject JavaScript code into the page, they can easily steal tokens from `localStorage`.
- **Token Hijacking**: Tokens stored in `localStorage` are accessible to any script running in the browser, which could be exploited in case of a vulnerability.

---

## Improvements to Secure Tokens and the Application

To mitigate these security risks and improve token storage practices, we will implement **cookie-based authentication** using **Laravel Sanctum**. This solution ensures the following:

- **HttpOnly Cookies**: Tokens will be stored in `HttpOnly` cookies, which **cannot** be accessed by JavaScript, protecting them from XSS attacks.
- **Secure Cookies**: Cookies will be marked as `Secure`, ensuring they are only sent over HTTPS, preventing them from being exposed in transit.
- **SameSite Cookies**: By setting the cookie attribute `SameSite=Lax` or `Strict`, we can further protect against CSRF (Cross-Site Request Forgery) attacks by restricting when cookies are sent across domains.

---

## To-Do List: Steps to Secure Tokens and Application

### 1. **Remove Tokens from `localStorage`**
- Stop storing tokens in `localStorage` or `sessionStorage`. Tokens stored here are vulnerable to theft.
- **Action**: Refactor frontend code to remove all references to `localStorage` for storing tokens.

### 2. **Switch to Cookie-Based Authentication with Sanctum**
- Use **Laravel Sanctum** to handle token authentication via **HttpOnly** cookies.

**Steps to Implement:**
- **Install Sanctum** in your Laravel project if not already done.
- Configure **Sanctum** as the authentication driver in `config/auth.php` to use cookies.
- Set up Sanctum middleware (`EnsureFrontendRequestsAreStateful`) for protecting API routes.
- **Update `.env`** configuration to specify `SANCTUM_STATEFUL_DOMAINS` (for local testing, use `localhost` and `127.0.0.1`).

### 3. **Use CSRF Protection with Laravel Sanctum**
- To avoid **CSRF attacks**, make sure to send a **CSRF token** with every API request.

**Steps to Implement:**
- Include a CSRF token in every frontend request before sending API calls by requesting `/sanctum/csrf-cookie`.
- In frontend, make sure to fetch the CSRF cookie before making authenticated requests:
   ```javascript
   axios.get('/sanctum/csrf-cookie').then(() => {
       axios.post('/login', { email: 'user@example.com', password: 'password' });
   });
  ```

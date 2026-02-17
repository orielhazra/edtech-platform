<!DOCTYPE html>
<html>
<head>
    <title>EdTech Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="/">EdTech</a>
    <div>
        
        <button id="logoutBtn" class="btn btn-sm btn-light d-none" onclick="logout()">Logout</button>
        <button id="loginBtn" class="btn btn-sm btn-light" onclick="login()">Login</button>
        <button id="registerBtn" class="btn btn-sm btn-light" onclick="register()">Register</button>
        
        
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
axios.defaults.baseURL = "{{ url('/api') }}";

axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

function logout() {
    axios.post('/auth/logout')
        .then(() => {
            localStorage.removeItem('token');
            window.location.href = "/login";
        });
}

function login() {
    window.location.href = "/login";
}

function register() {
    window.location.href = "/register";
}

document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem('token');

    const logoutBtn = document.getElementById('logoutBtn');
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');

    const currentPath = window.location.pathname;

    if (token) {
        // User logged in
        logoutBtn.classList.remove('d-none');
        loginBtn.classList.add('d-none');
        registerBtn.classList.add('d-none');
    } else {
        // User NOT logged in
        logoutBtn.classList.add('d-none');

        if (currentPath.includes('login')) {
            loginBtn.classList.add('d-none');
            registerBtn.classList.remove('d-none');
        } 
        else if (currentPath.includes('register')) {
            registerBtn.classList.add('d-none');
            loginBtn.classList.remove('d-none');
        } 
        else {
            loginBtn.classList.remove('d-none');
            registerBtn.classList.remove('d-none');
        }
    }
});

</script>

@stack('scripts')
</body>
</html>

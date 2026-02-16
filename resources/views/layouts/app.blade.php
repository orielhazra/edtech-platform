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
        
        @if(auth()->check())
            <button class="btn btn-sm btn-light" onclick="logout()">Logout</button>
        @elseif(request()->routeIs('login'))
            <button class="btn btn-sm btn-light" onclick="register()">Register</button>
        @elseif(request()->routeIs('register'))
            <button class="btn btn-sm btn-light" onclick="login()">Login</button>
        @else
            <button class="btn btn-sm btn-light" onclick="register()">Register</button>
            <button class="btn btn-sm btn-light" onclick="login()">Login</button>
        @endif
        
        
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

</script>

@stack('scripts')
</body>
</html>

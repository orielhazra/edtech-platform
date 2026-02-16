@extends('layouts.app')

@section('content')
<h3>Login</h3>

<form id="loginForm">
    <input type="email" id="email" class="form-control mb-2" placeholder="Email">
    <input type="password" id="password" class="form-control mb-2" placeholder="Password">
    <button class="btn btn-primary">Login</button>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    axios.post('/auth/login', {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    }).then(res => {
        localStorage.setItem('token', res.data.access_token);
        window.location.href = "/dashboard";
    }).catch(err => {
        alert("Invalid credentials");
    });
});
</script>
@endpush

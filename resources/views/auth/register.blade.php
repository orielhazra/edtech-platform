@extends('layouts.app')

@section('content')
<h3>Register</h3>

<form id="registerForm">
    <input type="text" id="name" class="form-control mb-2" placeholder="Name">
    <input type="email" id="email" class="form-control mb-2" placeholder="Email">
    <input type="password" id="password" class="form-control mb-2" placeholder="Password (Min. 6 letters)">
    
    <select id="role" class="form-control mb-2">
        <option value="student">Student</option>
        <option value="instructor">Instructor</option>
    </select>

    <button class="btn btn-success">Register</button>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    axios.post('/auth/register', {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        role: document.getElementById('role').value
    }).then(res => {
        localStorage.setItem('token', res.data.access_token);
        window.location.href = "/dashboard";
    }).catch(err => {
        alert("Registration failed");
    });
});
</script>
@endpush

@extends('layouts.app')

@section('content')
<h3>Courses</h3>


<button id="dashboardBtn" class="btn btn-sm btn-dark d-none" onclick="dashboard()">Dashboard</button>


<input type="text" id="search" class="form-control mb-2" placeholder="Search course...">

<div id="courseList"></div>

<button class="btn btn-secondary mt-3" onclick="loadCourses()">Reload</button>
@endsection

@push('scripts')
<script>
function loadCourses(page = 1) {
    let search = document.getElementById('search').value;

    axios.get(`/courses?page=${page}&search=${search}`)
        .then(res => {
            let html = '';

            res.data.data.forEach(course => {
                html += `
                    <div class="card mb-2 p-3">
                        <h5>${course.title}</h5>
                        <p>${course.description}</p>
                        <a href="/courses/${course.id}" class="btn btn-sm btn-primary">View</a>
                    </div>
                `;
            });

            document.getElementById('courseList').innerHTML = html;
        });
}

loadCourses();

function dashboard() {
    window.location.href = "/dashboard";
}

document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem('token');
    if (token) {
        // User logged in
        document.getElementById('dashboardBtn').classList.remove('d-none');
    }
});


</script>
@endpush

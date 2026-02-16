@extends('layouts.app')

@section('content')

<h2>Dashboard</h2>

<div id="userInfo" class="mb-4"></div>

<div id="adminSection" class="d-none">
    <h4>Admin Overview</h4>
    <div id="adminStats"></div>
</div>

<div id="instructorSection" class="d-none">
    <h4>Your Courses</h4>
    <button class="btn btn-secondary" onclick="createnew()">Create New</button>
    <div id="instructorCourses"></div>
</div>

<div id="studentSection" class="d-none">
    <h4>Enrolled Courses</h4>
    <div id="studentCourses"></div>
</div>

@endsection

@push('scripts')
<script>

function loadDashboard() {

    axios.get('/auth/me')
        .then(res => {
            const user = res.data;

            document.getElementById('userInfo').innerHTML = `
                <div class="alert alert-info">
                    Logged in as <strong>${user.name}</strong> (${user.role})
                </div>
            `;

            if (user.role === 'admin') {
                loadAdmin();
            }

            if (user.role === 'instructor') {
                loadInstructor();
            }

            if (user.role === 'student') {
                loadStudent();
            }
        })
        .catch(() => {
            window.location.href = "/login";
        });
}

function loadAdmin() {
    document.getElementById('adminSection').classList.remove('d-none');

    axios.get('/courses')
        .then(res => {
            document.getElementById('adminStats').innerHTML = `
                <p>Total Courses: ${res.data.meta.total}</p>
            `;
        });
}

function loadInstructor() {
    document.getElementById('instructorSection').classList.remove('d-none');

    axios.get('/courses')
        .then(res => {

            let html = '';

            res.data.data.forEach(course => {
                if (course.instructor === document.querySelector('#userInfo strong').innerText) {
                    html += `
                        <div class="card p-2 mb-2">
                            <strong>${course.title}</strong>
                            <a href="/courses/${course.id}/edit"
                                class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <button class="btn btn-sm btn-danger"
                                    onclick="deleteCourse(${course.id})">
                                Delete
                            </button>
                        </div>
                    `;
                }
            });

            document.getElementById('instructorCourses').innerHTML = html;
        });
}

function loadStudent() {
    document.getElementById('studentSection').classList.remove('d-none');

    axios.get('/my-courses')
        .then(res => {
            let html = '';

            res.data.data.forEach(course => {
                html += `
                    <div class="card p-2 mb-2">
                        <strong>${course.title}</strong>
                    </div>
                `;
            });

            document.getElementById('studentCourses').innerHTML = html;
        });
}

function deleteCourse(selectedCourseId) {

    axios.delete(`/courses/${selectedCourseId}`)
        .then(() => {

            alert("Course deleted successfully");

            loadInstructor(); // reload course list

        })
        .catch(err => {
            alert("Delete failed");
            console.log(err.response.data);
        });
}

loadDashboard();

function createnew() {
    window.location.href = "/courses/create";
}

</script>
@endpush

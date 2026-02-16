@extends('layouts.app')

@section('content')

<h3>Update Course</h3>

<div class="card p-4">
    <form id="updateCourseForm">

        <div class="mb-3">
            <label>Title</label>
            <input type="text" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea id="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Level</label>
            <select id="level" class="form-control">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>

        <button class="btn btn-warning">Update Course</button>
        <a href="/dashboard" class="btn btn-secondary">Cancel</a>

    </form>
</div>

@endsection

@push('scripts')
<script>

const courseId = {{ $id }};

// Load existing course
function loadCourse() {
    axios.get(`/courses/${courseId}`)
    .then(res => {
        const course = res.data.data;

        title.value = course.title;
        description.value = course.description;
        price.value = course.price;
        level.value = course.level;
    })
    .catch(() => {
        alert("Failed to load course");
    });
}

loadCourse();

// Update course
document.getElementById('updateCourseForm')
.addEventListener('submit', function(e){

    e.preventDefault();

    axios.put(`/courses/${courseId}`, {
        title: title.value,
        description: description.value,
        price: price.value,
        level: level.value
    })
    .then(() => {
        alert("Course updated successfully");
        window.location.href = "/dashboard";
    })
    .catch(err => {
        alert("Update failed");
        console.log(err.response.data);
    });

});

</script>
@endpush

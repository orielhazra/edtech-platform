@extends('layouts.app')

@section('content')

<h3>Create New Course</h3>

<div class="card p-4">
    <form id="createCourseForm">

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

        <button class="btn btn-primary">Create Course</button>
        <a href="/dashboard" class="btn btn-secondary">Cancel</a>

    </form>
</div>

@endsection

@push('scripts')
<script>

document.getElementById('createCourseForm')
.addEventListener('submit', function(e){

    e.preventDefault();

    axios.post('/courses', {
        title: title.value,
        description: description.value,
        price: price.value,
        level: level.value
    })
    .then(res => {
        alert("Course created successfully");
        window.location.href = "/dashboard";
    })
    .catch(err => {
        alert("Error creating course");
        console.log(err.response.data);
    });

});

</script>
@endpush

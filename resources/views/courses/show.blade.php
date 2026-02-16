@extends('layouts.app')

@section('content')
<div id="courseDetails"></div>
<button class="btn btn-success mt-3" onclick="enroll()">Enroll</button>
@endsection

@push('scripts')
<script>
const courseId = {{ $id }};

function loadCourse() {
    axios.get(`/courses/${courseId}`)
        .then(res => {
            const c = res.data.data;
            let lessonsHtml = '';

            c.lessons.forEach(l => {
                lessonsHtml += `<li>${l.title}</li>`;
            });

            document.getElementById('courseDetails').innerHTML = `
                <h3>${c.title}</h3>
                <p>${c.description}</p>
                <ul>${lessonsHtml}</ul>
                <h4>${c.price}$</h4>
            `;
        });
}

function enroll() {
    axios.post(`/courses/${courseId}/enroll`)
        .then(() => alert("Enrolled successfully"))
        .catch(() => alert("Enrollment failed"));
}

loadCourse();
</script>
@endpush

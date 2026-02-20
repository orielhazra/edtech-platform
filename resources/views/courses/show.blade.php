@extends('layouts.app')

@section('content')
<div id="courseDetails"></div>
<div id="courseAction"></div>
<div class="mt-4">
    <h4>Reviews</h4>
    <div id="reviewsContainer"></div>
</div>
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
                <div class="d-flex align-items-center">
                    <h5>${c.title}</h5>
                    <span class="badge ms-2 
                        ${c.level === 'beginner' ? 'bg-success' : ''}
                        ${c.level === 'intermediate' ? 'bg-warning' : ''}
                        ${c.level === 'advanced' ? 'bg-danger' : ''}
                    ">${c.level}</span>
                </div>
                <h6>By ${c.instructor.name}</h6>
                <p>${c.description}</p>
                <ul>${lessonsHtml}</ul>
                <h4>${c.price}$</h4>
            `;
        });
}

function loadAction() {

    axios.get('/auth/me')
        .then(res => {
            const user = res.data;

            if (user.role === 'admin') {
                
            }

            if (user.role === 'instructor' ) {
                document.getElementById('courseAction').innerHTML = `
                <button class="btn btn-success mt-3" onclick="back()">Back</button>
                `;
            }

            if (user.role === 'student') {
                document.getElementById('courseAction').innerHTML = `
                <button class="btn btn-success mt-3" onclick="enroll()">Enroll</button>
                <button class="btn btn-success mt-3" onclick="review()">Review</button>
                `;
            }
        })
        .catch(() => {
            document.getElementById('courseAction').innerHTML =`
            <button class="btn btn-success mt-3" onclick="enrollGuest()">Enroll</button>
            `;
        });
}

function loadReviews() {
    axios.get(`/courses/${courseId}/reviews`)
        .then(res=>{
            const reviews = res.data.data || [];
            
            const container = document.getElementById('reviewsContainer');
            const addReviewBtn = document.getElementById('addReviewBtn');
            container.innerHTML = '';

            if (reviews.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-info">
                        No reviews yet.
                    </div>
                `;
                return;
            }

            // Display only the 3 latest reviews
            const latestReviews = reviews.slice(0, 3);
            
            latestReviews.forEach(review => {
                
                container.innerHTML += `
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong>${review.user?.name ?? 'Anonymous'}</strong>
                                <span class="badge bg-warning text-dark">
                                    ${review.rating} / 5
                                </span>
                            </div>
                            <p class="mt-2 mb-1">${review.comment}</p>
                            <small class="text-muted">
                                ${new Date(review.created_at).toLocaleDateString()}
                            </small>
                        </div>
                    </div>
                `;
            });

        })
        .catch((e) => {
            
            console.error("Error loading reviews.");
            document.getElementById('reviewsContainer').innerHTML = `
                <div class="alert alert-danger">
                    Failed to load reviews.
                </div>
            `;
        });
}

function enroll() {
    axios.post(`/courses/${courseId}/enroll`)
        .then(() => alert("Enrolled successfully"))
        .catch(() => alert("Enrollment failed"));
}

function enrollGuest() {
    alert("Please log in to enroll")
    window.location.href = "/login";
}

function back() {
    window.location.href = "/dashboard";
}

function review() {
    window.location.href = `/courses/${courseId}/review`;
}

loadCourse();
loadAction();
loadReviews();

</script>
@endpush

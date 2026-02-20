@extends('layouts.app')

@section('content')
<div id="courseDetails"></div>

<div class="card p-3 mb-3">
    <h5>Submit Your Review</h5>
    <form id="reviewForm">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <select id="rating" class="form-select" required>
                <option value="">Choose a rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea id="comment" class="form-control" rows="4" placeholder="Write your review here..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>

<div id="existingReviews">
    <h5>Existing Reviews</h5>
    <div id="reviewsList"></div>
</div>

@endsection

@push('scripts')
<script>
const courseId = {{ $id }};

document.addEventListener("DOMContentLoaded", function () {
    loadReviews();
    loadCourse();

    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitReview(courseId);
    });
});

function loadCourse() {
    axios.get(`/courses/${courseId}`)
        .then(res => {
            const c = res.data.data;
            console.log(c)
            document.getElementById('courseDetails').innerHTML = `
            <h3 class="mb-3">Course Review - ${c.title}</h3>

            <div class="card p-3 mb-3">
                <h5>Course Description</h5>
                <p>${c.description}</p>
                <span class="badge bg-warning text-dark">
                    Average Rating: ⭐ ${c.average_rating ?? '0.0'}
                </span>
            </div>
            `
        });
}

// Load existing reviews for the course
function loadReviews() {
    axios.get(`/courses/${courseId}/reviews`)
        .then(res => {
            const reviews = res.data.data;
            const reviewsList = document.getElementById('reviewsList');
            
            if (reviews.length === 0) {
                reviewsList.innerHTML = "<p class='text-muted'>No reviews yet.</p>";
            } else {
                reviews.forEach(review => {
                    reviewsList.innerHTML += `
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
            }
        })
        .catch(err => {
            console.error(err);
        });
}

// Submit a new review
function submitReview() {
    const rating = document.getElementById('rating').value;
    const comment = document.getElementById('comment').value;

    axios.post(`/courses/${courseId}/review`, {
        rating: rating,
        comment: comment
    })
    .then(res => {
        loadReviews(); // Reload reviews
        document.getElementById('reviewForm').reset(); // Reset form
    })
    .catch(err => {
        console.error(err);
    });
}
</script>
@endpush

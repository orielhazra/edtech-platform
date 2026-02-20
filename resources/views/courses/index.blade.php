@extends('layouts.app')

@section('content')
<h3 class="mb-3">Courses</h3>

<button id="dashboardBtn" class="btn btn-sm btn-dark mb-3 d-none" onclick="dashboard()">
    Dashboard
</button>

<div class="card p-3 mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-md-6">
            <label class="form-label mb-1">Search</label>
            <input type="text" id="search" class="form-control" placeholder="Search course by title...">
        </div>

        <div class="col-6 col-md-3">
            <label class="form-label mb-1">Level</label>
            <select id="level" class="form-select">
                <option value="">All</option>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>

        <div class="col-6 col-md-3">
            <label class="form-label mb-1">Max price</label>
            <input type="number" id="max_price" min="0" step="0.01"
                   class="form-control" placeholder="e.g. 49.99">
        </div>

        <div class="col-12 d-flex gap-2 mt-2">
            <button class="btn btn-primary" onclick="loadCourses(1)">Apply</button>
            <button class="btn btn-outline-secondary" onclick="resetFilters()">Reset</button>
            <button class="btn btn-secondary ms-auto" onclick="loadCourses(1)">Reload</button>
        </div>

        <small id="filterHint" class="text-muted mt-2"></small>
    </div>
</div>

<div id="courseList"></div>

<nav class="mt-3">
    <ul id="pagination" class="pagination"></ul>
</nav>
@endsection

@push('scripts')
<script>
function buildQuery(params) {
    const q = new URLSearchParams();
    Object.entries(params).forEach(([k, v]) => {
        if (v !== undefined && v !== null && String(v).trim() !== '') {
            q.set(k, v);
        }
    });
    return q.toString();
}

function getFilters() {
    return {
        search: document.getElementById('search').value,
        level: document.getElementById('level').value,
        max_price: document.getElementById('max_price').value,
    };
}

function setHint(filters) {
    const parts = [];
    if (filters.search) parts.push(`search: "${filters.search}"`);
    if (filters.level) parts.push(`level: ${filters.level}`);
    if (filters.max_price) parts.push(`max price: ${filters.max_price}`);

    document.getElementById('filterHint').textContent =
        parts.length ? `Filtering by ${parts.join(', ')}` : '';
}

function renderCourses(courses) {
    const el = document.getElementById('courseList');

    if (!courses || !courses.length) {
        el.innerHTML = `<p class="text-muted">No courses found.</p>`;
        return;
    }

    el.innerHTML = courses.map(course => `
        <div class="card mb-2 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">${course.title}</h5>
                </div>
                <div class="d-flex align-items-center ms-auto">
                    <span class="badge ms-2 
                        ${course.level === 'beginner' ? 'bg-success' : ''}
                        ${course.level === 'intermediate' ? 'bg-warning' : ''}
                        ${course.level === 'advanced' ? 'bg-danger' : ''}
                    ">
                        ${course.level}
                    </span>
                    <span class="badge bg-warning text-dark ms-3">
                        ⭐ ${course.average_rating ?? '0.0'}
                    </span>
                </div>
            </div>

            <p class="mt-2 mb-2">${course.description ?? ''}</p>
            <a href="/courses/${course.id}" class="btn btn-sm btn-primary">
                View
            </a>
        </div>
    `).join('');
}

function renderPagination(meta, filters) {
    const ul = document.getElementById('pagination');
    ul.innerHTML = '';

    if (!meta || meta.last_page <= 1) return;

    const makeItem = (label, page, disabled = false, active = false) => {
        const li = document.createElement('li');
        li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${label}</a>`;
        li.addEventListener('click', (e) => {
            e.preventDefault();
            if (disabled) return;
            loadCourses(page, filters);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        return li;
    };

    ul.appendChild(makeItem('«', meta.current_page - 1, meta.current_page === 1));

    const start = Math.max(1, meta.current_page - 2);
    const end = Math.min(meta.last_page, meta.current_page + 2);

    for (let p = start; p <= end; p++) {
        ul.appendChild(makeItem(p, p, false, p === meta.current_page));
    }

    ul.appendChild(makeItem('»', meta.current_page + 1, meta.current_page === meta.last_page));
}

function loadCourses(page = 1, overrideFilters = null) {
    const filters = overrideFilters || getFilters();
    setHint(filters);

    const qs = buildQuery({ ...filters, page });

    axios.get(`/courses?${qs}`)
        .then(res => {
            renderCourses(res.data.data || []);
            renderPagination(res.data.meta, filters);
        })
        .catch(err => {
            console.error(err);
            document.getElementById('courseList').innerHTML =
                `<p class="text-danger">Failed to load courses.</p>`;
        });
}

function resetFilters() {
    document.getElementById('search').value = '';
    document.getElementById('level').value = '';
    document.getElementById('max_price').value = '';
    loadCourses(1);
}

function dashboard() {
    window.location.href = "/dashboard";
}

// Show dashboard only if token exists (JWT)
document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem('token');

    if (token) {
        document.getElementById('dashboardBtn').classList.remove('d-none');
    }

    document.getElementById('search').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            loadCourses(1);
        }
    });

    loadCourses(1);
});
</script>
@endpush

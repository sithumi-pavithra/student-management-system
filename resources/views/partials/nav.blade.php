<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('students.index') }}">
            <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center"
                 style="width: 40px; height: 40px;">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <div>
                <div class="fw-bold lh-1">Student Management</div>
                <small class="text-white-50" style="font-size: 0.7rem;">Admin Panel</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="text-end d-none d-lg-block">
                    <div class="fw-semibold lh-1">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <small class="text-white-50">Administrator</small>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>
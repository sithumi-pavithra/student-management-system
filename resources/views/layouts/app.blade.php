<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student Management System')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        /* Modal fix for Save button visibility */
        .modal-dialog {
            max-height: calc(100vh - 3.5rem);
            margin: 1.75rem auto;
        }
        .modal-content {
            max-height: calc(100vh - 3.5rem);
            display: flex;
            flex-direction: column;
        }
        .modal-header {
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .modal-body {
            overflow-y: auto;
            flex: 1 1 auto;
        }
        .modal-footer {
            flex-shrink: 0;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            position: sticky;
            bottom: 0;
            z-index: 10;
        }

        /* Card hover */
        .card {
            transition: all 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }

        /* Login page */
        .login-bg {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .logo-box {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border-radius: 20px;
        }
        .btn-login {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            border: none;
            color: white;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #4338CA 0%, #6D28D9 100%);
            color: white;
        }

        /* Form focus */
        .form-control:focus,
        .form-select:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        /* View modal list styling */
        .student-detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .student-detail-item:last-child {
            border-bottom: none;
        }
        .student-detail-item .label {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .student-detail-item .label i {
            color: #0dcaf0;
            width: 18px;
        }
        .student-detail-item .value {
            font-weight: 600;
            color: #212529;
        }
    </style>

    @stack('styles')
</head>
<body class="@yield('body-class', 'bg-light')">

    
    @hasSection('hide-nav')
    @else
        @include('partials.nav')
    @endif

    
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

   
    @yield('content')

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
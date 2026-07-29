<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laravel 12 Service Container & Dependency Injection</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #dc2626;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #334155;
        }

        body {
            background: var(--light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
        }

        .container {
            max-width: 1400px;
        }

        /* ======================================
        Dashboard Header
    =======================================*/

        .page-header {
            background: #fff;
            border-radius: 18px;
            padding: 25px 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .08);
            border-left: 6px solid var(--primary);
        }

        .page-header h2 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .page-header p {
            margin: 0;
            color: #64748b;
        }

        /* ======================================
        Cards
    =======================================*/

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            background: #fff;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
            padding: 18px 25px;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        /* ======================================
        Statistics
    =======================================*/

        .card-box {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            transition: .3s;
            border: 1px solid var(--border);
            text-align: center;
        }

        .card-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(37, 99, 235, .15);
        }

        .card-box h5 {
            color: #64748b;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-box h2 {
            font-size: 38px;
            font-weight: 700;
            margin: 0;
        }

        .stat-total h2 {
            color: #2563eb;
        }

        .stat-english h2 {
            color: #0ea5e9;
        }

        .stat-formal h2 {
            color: #334155;
        }

        .stat-motivational h2 {
            color: #f59e0b;
        }

        .stat-professional h2 {
            color: #16a34a;
        }

        .stat-fun h2 {
            color: #ec4899;
        }

        /* ======================================
        Greeting Message
    =======================================*/

        .message-box {
            background: #f8fbff;
            border-left: 5px solid var(--primary);
            border-radius: 12px;
            padding: 20px;
            white-space: pre-line;
            font-size: 18px;
            line-height: 32px;
        }

        /* ======================================
        Buttons
    =======================================*/

        .btn {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            border: none;
        }

        .btn-danger {
            border: none;
        }

        .btn-warning {
            color: #fff;
        }

        .btn-info {
            color: #fff;
        }

        .btn-group-custom a {
            margin: 4px;
        }

        /* ======================================
        Search
    =======================================*/

        .form-control {
            border-radius: 10px;
            min-height: 48px;
            border: 1px solid var(--border);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
        }

        /* ======================================
        Table
    =======================================*/

        .table {
            margin: 0;
        }

        .table thead {
            background: #f1f5f9;
        }

        .table thead th {
            color: #334155;
            font-weight: 700;
            border-bottom: 2px solid var(--border);
            text-align: center;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: #f8fbff;
        }

        /* ======================================
        Badges
    =======================================*/

        .badge {
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 13px;
        }

        /* ======================================
        Alerts
    =======================================*/

        .alert {
            border: none;
            border-radius: 12px;
        }

        /* ======================================
        Pagination
    =======================================*/

        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .page-link {
            border: none;
            color: var(--primary);
            border-radius: 8px;
            margin: 0 3px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .08);
        }

        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
        }

        /* ======================================
        Responsive
    =======================================*/

        @media(max-width:768px) {

            .page-header {
                padding: 20px;
            }

            .page-header h2 {
                font-size: 24px;
            }

            .btn-group-custom a {
                display: block;
                width: 100%;
                margin-bottom: 10px;
            }

            .table {
                font-size: 14px;
            }

            .card-box {
                margin-bottom: 20px;
            }
        }
    </style>

</head>

<body>

    <div class="container py-4">

        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2>
                Laravel 12 Service Container & Dependency Injection
            </h2>

            <a href="{{ url('/greet/export') }}"
                class="btn btn-success">
                Export CSV
            </a>

        </div>


        {{-- Statistics --}}

        <div class="row mb-4">

            <div class="col-md-2">

                <div class="card card-box shadow-sm text-center">

                    <div class="card-body">

                        <h5>Total</h5>

                        <h2>{{ $stats['total'] }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-2">

                <div class="card card-box shadow-sm text-center">

                    <div class="card-body">

                        <h5>English</h5>

                        <h2>{{ $stats['english'] }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-2">

                <div class="card card-box shadow-sm text-center">

                    <div class="card-body">

                        <h5>Formal</h5>

                        <h2>{{ $stats['formal'] }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-2">

                <div class="card card-box shadow-sm text-center">

                    <div class="card-body">

                        <h5>Motivational</h5>

                        <h2>{{ $stats['motivational'] }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-2">

                <div class="card card-box shadow-sm text-center">

                    <div class="card-body">

                        <h5>Professional</h5>

                        <h2>{{ $stats['professional'] }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-2">

                <div class="card card-box shadow-sm text-center">

                    <div class="card-body">

                        <h5>Fun</h5>

                        <h2>{{ $stats['fun'] }}</h2>

                    </div>

                </div>

            </div>

        </div>



        {{-- Greeting Result --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h4>

                    Current Greeting :
                    <span class="text-primary">
                        {{ ucfirst($type) }}
                    </span>

                </h4>

                <h5>

                    Theme :
                    <span class="text-success">
                        {{ ucfirst($theme) }}
                    </span>

                </h5>

                <hr>

                <div class="message-box">

                    {{ $message }}

                </div>

            </div>

        </div>



        {{-- Greeting Buttons --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h4 class="mb-3">

                    Greeting Services

                </h4>

                <div class="btn-group-custom">

                    <a href="{{ url('/greet?type=english&theme='.$theme) }}"
                        class="btn btn-primary">
                        English
                    </a>

                    <a href="{{ url('/greet?type=formal&theme='.$theme) }}"
                        class="btn btn-dark">
                        Formal
                    </a>

                    <a href="{{ url('/greet?type=motivational&theme='.$theme) }}"
                        class="btn btn-warning">
                        Motivational
                    </a>

                </div>

            </div>

        </div>



        {{-- Theme Buttons --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h4 class="mb-3">

                    Greeting Themes

                </h4>

                <div class="btn-group-custom">

                    <a href="{{ url('/greet?type='.$type.'&theme=professional') }}"
                        class="btn btn-info">
                        Professional
                    </a>

                    <a href="{{ url('/greet?type='.$type.'&theme=fun') }}"
                        class="btn btn-danger">
                        Fun 🚀
                    </a>

                </div>

            </div>

        </div>



        {{-- Search --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET" action="{{ url('/greet') }}">

                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="theme" value="{{ $theme }}">

                    <div class="row">

                        <div class="col-md-10">

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Search greeting type, theme or message">

                        </div>

                        <div class="col-md-2 d-grid">

                            <button class="btn btn-primary">

                                Search

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- Greeting History --}}

        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">

                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">

                        Greeting History

                    </h4>

                    <form
                        action="{{ route('greet.destroyAll') }}"
                        method="POST"
                        onsubmit="return confirm('Delete all greeting history?')">

                        @csrf

                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm">

                            Delete All

                        </button>

                    </form>

                </div>

            </div>

            <div class="card-body">

                @if($histories->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="70">#</th>

                                <th>Greeting Type</th>

                                <th>Theme</th>

                                <th>Message</th>

                                <th width="180">
                                    Created At
                                </th>

                                <th width="120">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($histories as $history)

                            <tr>

                                <td>

                                    {{ $histories->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    <span class="badge bg-primary">

                                        {{ ucfirst($history->greeting_type) }}

                                    </span>

                                </td>

                                <td>

                                    @if($history->theme == 'professional')

                                    <span class="badge bg-success">

                                        Professional

                                    </span>

                                    @else

                                    <span class="badge bg-warning text-dark">

                                        Fun 🚀

                                    </span>

                                    @endif

                                </td>

                                <td style="white-space: pre-line;">

                                    {{ $history->message }}

                                </td>

                                <td>

                                    {{ $history->created_at->format('d M Y h:i A') }}

                                </td>

                                <td>

                                    <form
                                        action="{{ route('greet.destroy',$history) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this greeting?')">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                @else

                <div class="alert alert-warning text-center mb-0">

                    No greeting history found.

                </div>

                @endif

            </div>

        </div>


        {{-- Pagination --}}

        @if($histories->hasPages())

        <div class="d-flex justify-content-center mt-4">

            {{ $histories->links() }}

        </div>

        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /*
        |--------------------------------------------------------------------------
        | Bootstrap Pagination
        |--------------------------------------------------------------------------
        */

        .pagination {

            justify-content: center;

            margin-top: 20px;

        }

        .pagination .page-link {

            color: #0d6efd;

            border-radius: 6px;

            margin: 0 3px;

        }

        .pagination .page-item.active .page-link {

            background: #0d6efd;

            border-color: #0d6efd;

            color: #fff;

        }

        .pagination .page-link:hover {

            background: #e9ecef;

        }

        /*
        |--------------------------------------------------------------------------
        | Cards
        |--------------------------------------------------------------------------
        */

        .card {

            border: none;

            border-radius: 12px;

        }

        .card-header {

            border-top-left-radius: 12px !important;

            border-top-right-radius: 12px !important;

        }

        /*
        |--------------------------------------------------------------------------
        | Table
        |--------------------------------------------------------------------------
        */

        .table th {

            text-align: center;

            white-space: nowrap;

        }

        .table td {

            vertical-align: middle;

        }

        /*
        |--------------------------------------------------------------------------
        | Badge
        |--------------------------------------------------------------------------
        */

        .badge {

            font-size: 14px;

            padding: 8px 10px;

        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media(max-width:768px) {

            .btn-group-custom a {

                display: block;

                width: 100%;

                margin-bottom: 10px;

            }

            .table {

                font-size: 14px;

            }

            h2 {

                font-size: 24px;

            }

        }
    </style>

</body>

</html>
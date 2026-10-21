<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel 12 Service Container & Dependency Injection')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --dark: #0f172a;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        header.navbar-custom {
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            padding: 12px 30px;
        }

        .brand-logo {
            font-size: 19px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
            text-decoration: none;
        }

        .nav-link-custom {
            color: #94a3b8;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link-custom:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link-custom.active {
            color: #ffffff;
            background: #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .container-wrapper {
            max-width: 1380px;
            margin: 25px auto;
            padding: 0 20px;
            flex: 1;
            width: 100%;
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
            transition: all 0.25s;
        }

        footer {
            background: #0f172a;
            color: #94a3b8;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            font-size: 13px;
            border-top: 1px solid #1e293b;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navbar -->
    <header class="navbar-custom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <a href="{{ route('greet.index') }}" class="brand-logo">
                <i class="bi bi-cpu-fill text-primary fs-4"></i>
                <span>Laravel 12 <span class="text-primary">IoC & DI Studio</span></span>
            </a>

            <!-- Nav Links -->
            <nav class="d-flex align-items-center gap-1 flex-wrap">
                <a href="{{ route('greet.index') }}" class="nav-link-custom {{ request()->routeIs('greet.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-heart"></i> Greetings
                </a>
                <a href="{{ route('container.visualizer.index') }}" class="nav-link-custom {{ request()->routeIs('container.visualizer.*') ? 'active' : '' }}">
                    <i class="bi bi-search text-info"></i> Visualizer Studio
                </a>
                <a href="{{ route('container.broadcast.index') }}" class="nav-link-custom {{ request()->routeIs('container.broadcast.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill text-warning"></i> Tagged Broadcast
                </a>
                <a href="{{ route('container.benchmark.index') }}" class="nav-link-custom {{ request()->routeIs('container.benchmark.*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 text-danger"></i> Lifecycle Lab
                </a>
                <a href="{{ route('container.pipeline.index') }}" class="nav-link-custom {{ request()->routeIs('container.pipeline.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3-fill text-success"></i> Pipeline Studio
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container-wrapper">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center rounded-3 shadow-sm mb-4 border-0" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center rounded-3 shadow-sm mb-4 border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p class="mb-1"><strong>Laravel 12 Service Container & Dependency Injection Architecture</strong></p>
        <p class="text-secondary mb-0">Featuring IoC Visualizer &bull; Service Tagging &bull; Lifecycle Benchmarks &bull; Callable Pipelines</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

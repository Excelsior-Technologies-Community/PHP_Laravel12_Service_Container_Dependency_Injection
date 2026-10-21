@extends('layouts.app')

@section('title', 'Container Lifecycle & Performance Benchmark Lab')

@section('content')

<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-3"><i class="bi bi-speedometer2 fs-5"></i></span>
            Container Lifecycle & Performance Benchmark Lab
        </h3>
        <p class="text-muted small mb-0">Benchmark and compare object instantiation uniqueness (`spl_object_id()`), execution speed, and memory usage between `bind()`, `singleton()`, and `scoped()`.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('container.visualizer.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="bi bi-search me-1"></i> Visualizer Studio
        </a>
    </div>
</div>

<!-- Lifecycle Comparison Guide Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-custom p-3 border-start border-secondary border-4 h-100">
            <div class="fw-bold text-dark d-flex align-items-center justify-content-between mb-1">
                <span><i class="bi bi-arrow-repeat text-secondary me-1"></i> 1. `bind()` &bull; Transient</span>
                <span class="badge bg-light text-dark border font-mono small">New Instance</span>
            </div>
            <p class="text-muted small mb-0">
                A new instance is instantiated via reflection on <strong>every single call</strong> to <code>app(Class)</code>. Consumes more CPU & memory during high-frequency loops.
            </p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-3 border-start border-success border-4 h-100">
            <div class="fw-bold text-dark d-flex align-items-center justify-content-between mb-1">
                <span><i class="bi bi-shield-check text-success me-1"></i> 2. `singleton()` &bull; Shared</span>
                <span class="badge bg-success font-mono small">Shared Instance</span>
            </div>
            <p class="text-muted small mb-0">
                Resolved exactly <strong>once</strong> and cached in container memory. All subsequent resolutions return the identical instance pointer (fastest, lowest memory).
            </p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-3 border-start border-primary border-4 h-100">
            <div class="fw-bold text-dark d-flex align-items-center justify-content-between mb-1">
                <span><i class="bi bi-bounding-box-circles text-primary me-1"></i> 3. `scoped()` &bull; Request Scope</span>
                <span class="badge bg-primary font-mono small">Scoped Instance</span>
            </div>
            <p class="text-muted small mb-0">
                Shared as a singleton <strong>within the current HTTP request or job</strong>, then flushed when the request terminates (prevents memory leaks in Octane / Horizon).
            </p>
        </div>
    </div>
</div>

<!-- Interactive Benchmark Runner Panel -->
<div class="card card-custom p-4 mb-4">
    <div class="row align-items-center g-3">
        <div class="col-md-7">
            <label class="form-label fw-bold text-dark mb-1">
                <i class="bi bi-sliders text-danger me-1"></i> Resolution Volume (Iterations)
            </label>
            <div class="d-flex align-items-center gap-3">
                <input type="range" class="form-range" id="iterSlider" min="100" max="2000" step="100" value="500" oninput="document.getElementById('iterCountLabel').innerText = this.value + ' resolutions'">
                <span class="badge bg-danger rounded-pill font-mono px-3 py-2 text-nowrap" id="iterCountLabel">500 resolutions</span>
            </div>
            <small class="text-muted">Resolves each service repeatedly in batch to measure exact CPU ticks and memory delta.</small>
        </div>

        <div class="col-md-5 text-md-end">
            <button class="btn btn-danger rounded-pill px-4 py-2.5 fw-semibold shadow-sm" onclick="runBenchmark()">
                <i class="bi bi-play-circle-fill me-1"></i> Execute Benchmark Simulation
            </button>
        </div>
    </div>
</div>

<!-- Benchmark Results Display -->
<div class="row g-4" id="resultsGrid" style="display: none;">
    <!-- Transient Result -->
    <div class="col-md-4">
        <div class="card card-custom p-4 border-top border-secondary border-4 text-center h-100">
            <span class="badge bg-secondary text-white rounded-pill px-3 py-1 font-mono small mb-2 d-inline-block">
                $app->bind()
            </span>
            <h5 class="fw-bold text-dark">Transient Binding</h5>
            <div class="display-6 fw-bold text-dark font-mono mt-2" id="resTransientObjects">0</div>
            <small class="text-muted font-mono d-block mb-3">Unique Objects Created</small>

            <div class="p-3 bg-light rounded-3 text-start font-mono small border">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Execution Latency:</span>
                    <strong class="text-danger" id="resTransientTime">0 ms</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Memory Allocated:</span>
                    <strong id="resTransientMem">0 KB</strong>
                </div>
            </div>
            <small class="text-muted d-block mt-3" id="resTransientBehavior"></small>
        </div>
    </div>

    <!-- Singleton Result -->
    <div class="col-md-4">
        <div class="card card-custom p-4 border-top border-success border-4 text-center h-100">
            <span class="badge bg-success text-white rounded-pill px-3 py-1 font-mono small mb-2 d-inline-block">
                $app->singleton()
            </span>
            <h5 class="fw-bold text-dark">Shared Singleton</h5>
            <div class="display-6 fw-bold text-success font-mono mt-2" id="resSingletonObjects">1</div>
            <small class="text-muted font-mono d-block mb-3">Unique Object Created</small>

            <div class="p-3 bg-light rounded-3 text-start font-mono small border">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Execution Latency:</span>
                    <strong class="text-success" id="resSingletonTime">0 ms</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Memory Allocated:</span>
                    <strong class="text-success" id="resSingletonMem">0 KB</strong>
                </div>
            </div>
            <small class="text-muted d-block mt-3" id="resSingletonBehavior"></small>
        </div>
    </div>

    <!-- Scoped Result -->
    <div class="col-md-4">
        <div class="card card-custom p-4 border-top border-primary border-4 text-center h-100">
            <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-mono small mb-2 d-inline-block">
                $app->scoped()
            </span>
            <h5 class="fw-bold text-dark">Scoped Singleton</h5>
            <div class="display-6 fw-bold text-primary font-mono mt-2" id="resScopedObjects">1</div>
            <small class="text-muted font-mono d-block mb-3">Unique Object in Scope</small>

            <div class="p-3 bg-light rounded-3 text-start font-mono small border">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Execution Latency:</span>
                    <strong class="text-primary" id="resScopedTime">0 ms</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Memory Allocated:</span>
                    <strong class="text-primary" id="resScopedMem">0 KB</strong>
                </div>
            </div>
            <small class="text-muted d-block mt-3" id="resScopedBehavior"></small>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function runBenchmark() {
        const iterations = document.getElementById('iterSlider').value;

        fetch('{{ route("container.benchmark.run") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ iterations: iterations })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Benchmark error');
                return;
            }

            const r = data.results;

            // Transient
            document.getElementById('resTransientObjects').innerText = r.bind.distinct_instances;
            document.getElementById('resTransientTime').innerText = `${r.bind.time_ms} ms`;
            document.getElementById('resTransientMem').innerText = `${r.bind.memory_kb} KB`;
            document.getElementById('resTransientBehavior').innerText = r.bind.behavior;

            // Singleton
            document.getElementById('resSingletonObjects').innerText = r.singleton.distinct_instances;
            document.getElementById('resSingletonTime').innerText = `${r.singleton.time_ms} ms`;
            document.getElementById('resSingletonMem').innerText = `${r.singleton.memory_kb} KB`;
            document.getElementById('resSingletonBehavior').innerText = r.singleton.behavior;

            // Scoped
            document.getElementById('resScopedObjects').innerText = r.scoped.distinct_instances;
            document.getElementById('resScopedTime').innerText = `${r.scoped.time_ms} ms`;
            document.getElementById('resScopedMem').innerText = `${r.scoped.memory_kb} KB`;
            document.getElementById('resScopedBehavior').innerText = r.scoped.behavior;

            document.getElementById('resultsGrid').style.display = 'flex';
        })
        .catch(err => {
            alert('Request error: ' + err.message);
        });
    }

    // Auto run on page load for immediate preview
    document.addEventListener('DOMContentLoaded', runBenchmark);
</script>
@endsection

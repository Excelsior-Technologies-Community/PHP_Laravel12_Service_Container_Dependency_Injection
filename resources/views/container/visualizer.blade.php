@extends('layouts.app')

@section('title', 'Service Container Visualizer & Diagnostics')

@section('content')

<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3"><i class="bi bi-search fs-5"></i></span>
            Service Container Visualizer & Diagnostics Studio
        </h3>
        <p class="text-muted small mb-0">Inspect active IoC bindings, singletons, reflection metadata, and test dynamic resolution in real time.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('container.benchmark.index') }}" class="btn btn-outline-danger rounded-pill px-3 shadow-sm">
            <i class="bi bi-speedometer2 me-1"></i> Benchmark Lifecycles
        </a>
        <a href="{{ route('container.broadcast.index') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
            <i class="bi bi-tags-fill me-1"></i> Tagged Services
        </a>
    </div>
</div>

<!-- Telemetry Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.72rem;">Total Bindings</small>
                    <div class="fs-3 fw-bold font-mono mt-1">{{ number_format($totalBindings) }}</div>
                    <small class="text-white-50">Registered in Container</small>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                    <i class="bi bi-layers-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.72rem;">Singletons (Shared)</small>
                    <div class="fs-3 fw-bold font-mono mt-1">{{ number_format($totalSingletons) }}</div>
                    <small class="text-white-50">Reused instances</small>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.72rem;">Resolved Instances</small>
                    <div class="fs-3 fw-bold font-mono mt-1">{{ number_format($totalResolved) }}</div>
                    <small class="text-white-50">Currently in memory</small>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                    <i class="bi bi-cpu-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.72rem;">Container Aliases</small>
                    <div class="fs-3 fw-bold font-mono mt-1">{{ number_format($totalAliases) }}</div>
                    <small class="text-white-50">Shorthand mappings</small>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                    <i class="bi bi-link-45deg fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Container Resolver Sandbox -->
<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2">
        <i class="bi bi-play-circle-fill text-primary"></i> Dynamic Container Resolver Sandbox (`app()->make()`)
    </h5>
    <p class="text-muted small mb-3">Pick or enter any registered interface/class to resolve it via Laravel's IoC container and inspect reflection parameters.</p>

    <div class="row g-2 align-items-center">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted font-mono"><i class="bi bi-terminal"></i></span>
                <input type="text" id="resolveInput" class="form-control font-mono" placeholder="e.g. App\Contracts\GreetingInterface or App\Services\Notifications\MultiChannelBroadcastManager" value="App\Contracts\GreetingInterface">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100 rounded-3 fw-semibold shadow-sm" onclick="runContainerResolve()">
                <i class="bi bi-arrow-repeat me-1"></i> Resolve via Container
            </button>
        </div>
    </div>

    <!-- Quick Presets -->
    <div class="d-flex flex-wrap gap-1 align-items-center mt-2 pt-2 border-top">
        <small class="text-muted font-mono me-1">Quick Presets:</small>
        <button class="badge bg-light text-dark border text-decoration-none font-mono py-1.5 px-2.5 rounded-pill btn-preset" data-class="App\Contracts\GreetingInterface">GreetingInterface</button>
        <button class="badge bg-light text-dark border text-decoration-none font-mono py-1.5 px-2.5 rounded-pill btn-preset" data-class="App\Contracts\GreetingThemeInterface">GreetingThemeInterface</button>
        <button class="badge bg-light text-dark border text-decoration-none font-mono py-1.5 px-2.5 rounded-pill btn-preset" data-class="App\Services\Notifications\MultiChannelBroadcastManager">MultiChannelBroadcastManager</button>
        <button class="badge bg-light text-dark border text-decoration-none font-mono py-1.5 px-2.5 rounded-pill btn-preset" data-class="App\Services\Benchmark\SingletonReportService">SingletonReportService</button>
        <button class="badge bg-light text-dark border text-decoration-none font-mono py-1.5 px-2.5 rounded-pill btn-preset" data-class="Illuminate\Contracts\Config\Repository">Config Repository</button>
    </div>

    <!-- Diagnostics Result Card (Hidden by default) -->
    <div id="diagnosticsResult" class="mt-4 p-3 bg-light rounded-4 border" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <strong class="text-dark"><i class="bi bi-check2-circle text-success me-1"></i> Resolution Diagnostics</strong>
            <span class="badge bg-dark font-mono" id="diagDuration">0 ms</span>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="p-2.5 bg-white rounded-3 border">
                    <small class="text-muted d-block font-mono" style="font-size: 0.72rem;">CONCRETE CLASS RESOLVED</small>
                    <code class="text-primary fw-bold fs-6" id="diagResolvedClass"></code>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2.5 bg-white rounded-3 border">
                    <small class="text-muted d-block font-mono" style="font-size: 0.72rem;">PHP OBJECT ID</small>
                    <span class="fw-bold font-mono text-dark" id="diagObjectId">#0</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2.5 bg-white rounded-3 border">
                    <small class="text-muted d-block font-mono" style="font-size: 0.72rem;">LIFECYCLE STATUS</small>
                    <span class="badge" id="diagSharedStatus">Transient</span>
                </div>
            </div>
        </div>

        <!-- Constructor Parameters Table -->
        <div class="mb-3">
            <h6 class="fw-bold text-dark font-mono small mb-1"><i class="bi bi-box-arrow-in-right text-info me-1"></i> Injected Constructor Parameters</h6>
            <div class="table-responsive bg-white rounded-3 border">
                <table class="table table-sm align-middle font-mono small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Parameter</th>
                            <th>Expected Type Hint</th>
                            <th>Optional</th>
                            <th>Default Value</th>
                        </tr>
                    </thead>
                    <tbody id="diagParamsBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Public Methods List -->
        <div>
            <h6 class="fw-bold text-dark font-mono small mb-1"><i class="bi bi-code-slash text-secondary me-1"></i> Public Accessible Methods</h6>
            <div id="diagMethodsList" class="d-flex flex-wrap gap-1"></div>
        </div>
    </div>
</div>

<!-- Container Bindings Registry Table -->
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i> Registered Container Bindings
        </h5>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('container.visualizer.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('container.visualizer.index', ['filter' => 'all', 'search' => $search]) }}" class="btn {{ $filterType === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                <a href="{{ route('container.visualizer.index', ['filter' => 'app', 'search' => $search]) }}" class="btn {{ $filterType === 'app' ? 'btn-primary' : 'btn-outline-secondary' }}">App Services</a>
                <a href="{{ route('container.visualizer.index', ['filter' => 'singleton', 'search' => $search]) }}" class="btn {{ $filterType === 'singleton' ? 'btn-primary' : 'btn-outline-secondary' }}">Singletons</a>
                <a href="{{ route('container.visualizer.index', ['filter' => 'resolved', 'search' => $search]) }}" class="btn {{ $filterType === 'resolved' ? 'btn-primary' : 'btn-outline-secondary' }}">Resolved</a>
            </div>

            <div class="input-group input-group-sm" style="max-width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search bindings..." value="{{ $search }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle font-mono small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Abstract / Interface</th>
                    <th>Category</th>
                    <th>Lifecycle</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td>
                            <code class="text-dark fw-bold">{{ $row['abstract'] }}</code>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $row['concrete_type'] }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $row['category'] === 'Application' ? 'bg-primary' : ($row['category'] === 'Contracts' ? 'bg-info text-dark' : 'bg-light text-secondary border') }} rounded-pill">
                                {{ $row['category'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $row['is_shared'] ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                {{ $row['is_shared'] ? 'Singleton' : 'Transient' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $row['is_resolved'] ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }} rounded-pill">
                                {{ $row['is_resolved'] ? '● In Memory' : '○ Not Resolved' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="setAndResolve('{{ addslashes($row['abstract']) }}')">
                                <i class="bi bi-play-fill"></i> Inspect
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No container bindings matched your search criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.btn-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            const cls = this.getAttribute('data-class');
            document.getElementById('resolveInput').value = cls;
            runContainerResolve();
        });
    });

    function setAndResolve(cls) {
        document.getElementById('resolveInput').value = cls;
        window.scrollTo({ top: 300, behavior: 'smooth' });
        runContainerResolve();
    }

    function runContainerResolve() {
        const abstractVal = document.getElementById('resolveInput').value.trim();
        if (!abstractVal) return;

        fetch('{{ route("container.visualizer.resolve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ abstract: abstractVal })
        })
        .then(res => res.json())
        .then(data => {
            const panel = document.getElementById('diagnosticsResult');
            if (!data.success) {
                alert(data.message || 'Resolution failed');
                return;
            }

            document.getElementById('diagDuration').innerText = `${data.duration_ms} ms`;
            document.getElementById('diagResolvedClass').innerText = data.resolved_class;
            document.getElementById('diagObjectId').innerText = `#${data.object_id}`;

            const sharedBadge = document.getElementById('diagSharedStatus');
            if (data.is_shared) {
                sharedBadge.className = 'badge bg-success';
                sharedBadge.innerText = 'Shared Singleton';
            } else {
                sharedBadge.className = 'badge bg-secondary';
                sharedBadge.innerText = 'Transient (New Instance)';
            }

            // Populate Parameters
            const tbody = document.getElementById('diagParamsBody');
            tbody.innerHTML = '';
            if (!data.parameters || data.parameters.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-muted text-center py-2">Zero constructor arguments (No dependencies required).</td></tr>';
            } else {
                data.parameters.forEach(p => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="fw-bold">${p.name}</td>
                            <td><code>${p.type}</code></td>
                            <td>${p.is_optional ? '<span class="text-success">Yes</span>' : '<span class="text-danger">Required</span>'}</td>
                            <td>${p.default !== null ? `<code>${p.default}</code>` : '<span class="text-muted">None</span>'}</td>
                        </tr>
                    `;
                });
            }

            // Populate Methods
            const methodsDiv = document.getElementById('diagMethodsList');
            methodsDiv.innerHTML = '';
            if (data.methods && data.methods.length > 0) {
                data.methods.forEach(m => {
                    methodsDiv.innerHTML += `<span class="badge bg-light text-dark border font-mono">${m}()</span>`;
                });
            } else {
                methodsDiv.innerHTML = '<span class="text-muted small">No public methods.</span>';
            }

            panel.style.display = 'block';
        })
        .catch(err => {
            alert('Request error: ' + err.message);
        });
    }
</script>
@endsection

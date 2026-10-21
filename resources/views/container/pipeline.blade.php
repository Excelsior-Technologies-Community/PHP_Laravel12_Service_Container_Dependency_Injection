@extends('layouts.app')

@section('title', 'Callable Pipelines & DI Resolver - Laravel 12 IoC')

@section('styles')
<style>
    .pipeline-step-card {
        border-left: 4px solid #4f46e5;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .pipeline-step-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .step-number {
        width: 34px;
        height: 34px;
        background: #4f46e5;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }
    .pipe-badge {
        font-size: 11px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .code-preview {
        background: #0f172a;
        color: #38bdf8;
        padding: 16px;
        border-radius: 12px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
        overflow-x: auto;
    }
</style>
@endsection

@section('content')
<!-- Header Banner -->
<div class="card-custom p-4 mb-4 bg-white border-0 shadow-sm">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill">Pipeline Pattern</span>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1 rounded-pill">Container DI Auto-Resolution</span>
            </div>
            <h2 class="fw-bold text-dark mb-1">🚰 Callable Pipelines & Middleware Resolver Studio</h2>
            <p class="text-secondary mb-0">
                Demonstrates Laravel's internal <code class="text-primary font-mono">Illuminate\Pipeline\Pipeline</code> pattern. The Container sequentially resolves each pipe class, auto-injecting class dependencies (such as <code class="text-info font-mono">Illuminate\Http\Request</code>) via constructor DI.
            </p>
        </div>
        <div>
            <a href="{{ route('greet.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Greetings
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Pipeline Execution Form -->
    <div class="col-lg-5">
        <div class="card-custom p-4 bg-white border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-sliders text-primary"></i> Pipeline Configuration
            </h5>

            <!-- Preset Buttons -->
            <div class="mb-3">
                <label class="form-label small text-muted fw-semibold">Quick Preset Templates:</label>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadPreset('support')">
                        🚨 Urgent Bug Report
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="loadPreset('praise')">
                        ⭐ Happy Customer
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="loadPreset('spam')">
                        ⚠️ Spam & Unsafe HTML
                    </button>
                </div>
            </div>

            <!-- Text Input -->
            <div class="mb-3">
                <label for="pipeline-text" class="form-label fw-semibold">Input Payload Text</label>
                <textarea id="pipeline-text" class="form-control font-mono" rows="4" placeholder="Enter message payload..."><b>Awesome application!</b> But please fix this urgent scam issue asap with badword.</textarea>
                <small class="text-muted">This payload will pass through each selected pipe sequentially.</small>
            </div>

            <!-- Pipe Selection List -->
            <div class="mb-4">
                <label class="form-label fw-semibold mb-2">Available Pipes in Pipeline</label>
                <div class="border rounded-3 p-3 bg-light">
                    @foreach($availablePipes as $pipe)
                    <div class="form-check mb-2 pb-2 border-bottom last-border-none">
                        <input class="form-check-input pipe-checkbox" type="checkbox" value="{{ $pipe['key'] }}" id="pipe-{{ $pipe['key'] }}" checked>
                        <label class="form-check-label d-block ms-1" for="pipe-{{ $pipe['key'] }}">
                            <div class="fw-bold text-dark font-mono text-sm">{{ $pipe['name'] }}</div>
                            <small class="text-secondary d-block">{{ $pipe['desc'] }}</small>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary font-mono mt-1" style="font-size: 10px;">{{ $pipe['class'] }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <button id="runPipelineBtn" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" onclick="runPipeline()">
                <i class="bi bi-play-circle-fill me-1"></i> Send Through Pipeline
            </button>
        </div>

        <!-- How it works card -->
        <div class="card-custom p-4 bg-white border-0 shadow-sm">
            <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-info-circle text-primary me-1"></i> How Laravel Executes This:</h6>
            <div class="code-preview">
$result = app(Pipeline::class)<br>
&nbsp;&nbsp;->send($payload)<br>
&nbsp;&nbsp;->through([<br>
&nbsp;&nbsp;&nbsp;&nbsp;SanitizeInputPipe::class,<br>
&nbsp;&nbsp;&nbsp;&nbsp;ProfanityCensorPipe::class,<br>
&nbsp;&nbsp;&nbsp;&nbsp;EmojiSentimentEnricherPipe::class,<br>
&nbsp;&nbsp;&nbsp;&nbsp;MetadataStamperPipe::class, // Injects Request via DI<br>
&nbsp;&nbsp;])<br>
&nbsp;&nbsp;->then(fn($p) => $p);
            </div>
            <p class="small text-muted mt-2 mb-0">
                Notice that classes are provided as strings! Laravel's container automatically instantiates each pipe using <code class="font-mono">$app->make($pipe)</code> and injects dependencies on the fly.
            </p>
        </div>
    </div>

    <!-- Right Column: Live Pipeline Results -->
    <div class="col-lg-7">
        <!-- Results Summary Banner -->
        <div id="pipeline-summary-card" class="card-custom p-4 bg-white border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-activity text-success me-2"></i> Pipeline Execution Results</h5>
                <span id="pipeline-status-badge" class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-semibold">Ready</span>
            </div>

            <div class="row g-3 text-center mb-4">
                <div class="col-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <small class="text-secondary d-block mb-1">Execution Time</small>
                        <span id="stat-execution-time" class="fs-5 fw-bold text-dark font-mono">0.00 ms</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <small class="text-secondary d-block mb-1">Stages Executed</small>
                        <span id="stat-stages-count" class="fs-5 fw-bold text-primary font-mono">0</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <small class="text-secondary d-block mb-1">Container DI State</small>
                        <span class="fs-5 fw-bold text-success font-mono">Resolved</span>
                    </div>
                </div>
            </div>

            <!-- Before vs After Comparison -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">Original Payload vs Final Output</h6>
                <div class="p-3 bg-light border rounded-3 mb-2">
                    <small class="text-muted fw-bold d-block mb-1">ORIGINAL INPUT:</small>
                    <div id="display-original-text" class="font-mono text-dark small bg-white p-2 rounded border">
                        &lt;b&gt;Awesome application!&lt;/b&gt; But please fix this urgent scam issue asap with badword.
                    </div>
                </div>
                <div class="p-3 bg-success bg-opacity-10 border border-success-subtle rounded-3">
                    <small class="text-success fw-bold d-block mb-1">FINAL DELIVERED PAYLOAD:</small>
                    <div id="display-final-text" class="font-mono text-success-emphasis fw-semibold bg-white p-2 rounded border border-success-subtle">
                        (Run the pipeline to inspect final output)
                    </div>
                </div>
            </div>

            <!-- Stamped Metadata (if any) -->
            <div id="metadata-box" class="p-3 bg-dark text-light rounded-3 mb-4 d-none">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-primary pipe-badge font-mono">MetadataStamperPipe Audit Output</span>
                    <small class="text-muted font-mono" id="meta-stamped-at"></small>
                </div>
                <div class="row g-2 small font-mono">
                    <div class="col-sm-6">
                        <span class="text-secondary">UUID:</span> <span id="meta-uuid" class="text-info"></span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-secondary">Client IP:</span> <span id="meta-ip" class="text-warning"></span>
                    </div>
                    <div class="col-12">
                        <span class="text-secondary">User Agent:</span> <span id="meta-ua" class="text-light"></span>
                    </div>
                </div>
            </div>

            <!-- Step by Step Breakdown -->
            <h6 class="fw-bold text-dark mb-3">Sequential Step-by-Step Flow</h6>
            <div id="pipeline-steps-container" class="d-flex flex-column gap-3">
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-arrow-repeat fs-1 d-block mb-2 text-secondary"></i>
                    Click <strong>"Send Through Pipeline"</strong> to view execution details.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const presets = {
        support: "<b>Critical failure!</b> The application crashed with an urgent fatal error, looks like a badword issue.",
        praise: "Great service! Loving the high performance, awesome team and wonderful UX experience.",
        spam: "<script>alert('xss')</script>Check out this scam discount now at http://spam-site.com! Very urgent!"
    };

    function loadPreset(key) {
        if (presets[key]) {
            document.getElementById('pipeline-text').value = presets[key];
        }
    }

    async function runPipeline() {
        const text = document.getElementById('pipeline-text').value;
        const btn = document.getElementById('runPipelineBtn');
        const checkedBoxes = Array.from(document.querySelectorAll('.pipe-checkbox:checked')).map(cb => cb.value);

        if (checkedBoxes.length === 0) {
            alert('Please select at least one pipeline stage.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Resolving & Processing...`;

        try {
            const response = await fetch("{{ route('container.pipeline.run') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    text: text,
                    pipes: checkedBoxes
                })
            });

            const data = await response.json();

            if (!data.success) {
                alert(data.message || 'Execution error');
                return;
            }

            // Update stats
            document.getElementById('stat-execution-time').innerText = data.total_time_ms + ' ms';
            document.getElementById('stat-stages-count').innerText = data.total_pipes_executed;
            document.getElementById('display-original-text').innerText = data.original_text;
            document.getElementById('display-final-text').innerText = data.final_text;

            // Render Metadata if present
            const metaBox = document.getElementById('metadata-box');
            if (data.metadata && data.metadata.uuid) {
                metaBox.classList.remove('d-none');
                document.getElementById('meta-uuid').innerText = data.metadata.uuid;
                document.getElementById('meta-ip').innerText = data.metadata.request_ip;
                document.getElementById('meta-ua').innerText = data.metadata.user_agent;
                document.getElementById('meta-stamped-at').innerText = data.metadata.stamped_at;
            } else {
                metaBox.classList.add('d-none');
            }

            // Render Step-by-Step cards
            const stepsContainer = document.getElementById('pipeline-steps-container');
            stepsContainer.innerHTML = '';

            data.steps.forEach((step, index) => {
                const stepCard = document.createElement('div');
                stepCard.className = 'card pipeline-step-card shadow-sm p-3 bg-white rounded-3';
                stepCard.innerHTML = `
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-number">${index + 1}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                <h6 class="fw-bold mb-0 text-dark font-mono">${step.pipe}</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary font-mono pipe-badge">${step.di_injected}</span>
                            </div>
                            <p class="small text-muted mb-2">${step.description}</p>
                            
                            <div class="row g-2 font-mono small">
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded border">
                                        <span class="text-danger fw-bold d-block" style="font-size: 11px;">BEFORE:</span>
                                        <div class="text-truncate text-secondary" title="${escapeHtml(step.before)}">${escapeHtml(step.before)}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-success bg-opacity-10 rounded border border-success-subtle">
                                        <span class="text-success fw-bold d-block" style="font-size: 11px;">AFTER:</span>
                                        <div class="text-truncate text-dark fw-semibold" title="${escapeHtml(step.after)}">${escapeHtml(step.after)}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                stepsContainer.appendChild(stepCard);
            });

        } catch (err) {
            console.error(err);
            alert('Failed to execute pipeline: ' + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-play-circle-fill me-1"></i> Send Through Pipeline`;
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Auto-run on load once
    document.addEventListener('DOMContentLoaded', () => {
        runPipeline();
    });
</script>
@endsection

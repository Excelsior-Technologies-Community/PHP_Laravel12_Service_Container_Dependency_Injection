@extends('layouts.app')

@section('title', 'Tagged Services & Multi-Channel Broadcast Engine')

@section('content')

<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <span class="badge bg-warning bg-opacity-25 text-warning p-2 rounded-3"><i class="bi bi-tags-fill fs-5"></i></span>
            Tagged Services & Multi-Channel Broadcast Engine (`$app->tag`)
        </h3>
        <p class="text-muted small mb-0">Group decoupled notification drivers under a single container tag and resolve them collectively via dependency injection.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('container.visualizer.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="bi bi-search me-1"></i> Container Visualizer
        </a>
    </div>
</div>

<!-- Architecture & Concept Callout -->
<div class="card card-custom p-4 mb-4 border-start border-warning border-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-code-square text-warning me-1"></i> How Container Tagging Works in Laravel</h6>
            <p class="text-muted small mb-0">
                Instead of hardcoding four separate service dependencies, services implementing <code>NotificationChannelInterface</code> are tagged with <code>$this->app->tag([...], 'notification_channels')</code>.
                The <code>MultiChannelBroadcastManager</code> declares <code>iterable $channels</code> in its constructor, allowing Laravel to inject all tagged instances dynamically!
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge bg-dark font-mono p-2 rounded-3 text-start d-inline-block">
                <span class="text-warning">$app</span>->tag([...], <span class="text-success">'notification_channels'</span>);<br>
                <span class="text-warning">$app</span>->tagged(<span class="text-success">'notification_channels'</span>);
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Broadcast Dispatcher Form -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-send-fill text-primary"></i> Broadcast Alert Composer
            </h5>

            <form id="broadcastForm" onsubmit="event.preventDefault(); dispatchBroadcast();">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Primary Recipient / Target</label>
                    <input type="text" id="recipientInput" class="form-control rounded-3 font-mono" placeholder="devops-alerts@company.com" value="devops-team@enterprise.io" required>
                    <small class="text-muted" style="font-size: 0.72rem;">Email, Phone number, or Slack channel</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Notification Message Payload</label>
                    <textarea id="messageInput" class="form-control rounded-3 font-mono" rows="3" required>Database replica node failure detected on us-east-1. Automated failover initiated.</textarea>
                </div>

                <!-- Channel Toggles -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small text-muted d-block">Active Container Tagged Channels</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="checkbox" name="channel_toggle" value="email" id="chkEmail" checked>
                                <label class="form-check-label fw-semibold small" for="chkEmail">
                                    <i class="bi bi-envelope-at-fill text-primary me-1"></i> Email (SMTP)
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="checkbox" name="channel_toggle" value="sms" id="chkSms" checked>
                                <label class="form-check-label fw-semibold small" for="chkSms">
                                    <i class="bi bi-chat-dots-fill text-success me-1"></i> SMS Gateway
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="checkbox" name="channel_toggle" value="slack" id="chkSlack" checked>
                                <label class="form-check-label fw-semibold small" for="chkSlack">
                                    <i class="bi bi-slack text-danger me-1"></i> Slack Webhook
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="checkbox" name="channel_toggle" value="webhook" id="chkWebhook" checked>
                                <label class="form-check-label fw-semibold small" for="chkWebhook">
                                    <i class="bi bi-hdd-network-fill text-dark me-1"></i> Custom Webhook
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 rounded-pill fw-semibold text-dark shadow-sm py-2.5">
                    <i class="bi bi-broadcast me-1"></i> Broadcast Across Tagged Services
                </button>
            </form>

            <!-- Registered Channels Mini-List -->
            <div class="mt-4 pt-3 border-top">
                <small class="text-muted fw-bold font-mono text-uppercase" style="font-size: 0.72rem;">Registered in Tag [notification_channels]:</small>
                <div class="list-group list-group-flush mt-2">
                    @foreach($registeredChannels as $ch)
                        <div class="list-group-item px-0 py-1.5 border-0 d-flex justify-content-between align-items-center font-mono small">
                            <span><i class="bi {{ $ch['icon'] }} me-1 text-secondary"></i> {{ $ch['name'] }}</span>
                            <span class="badge bg-light text-muted border">Obj #{{ $ch['object_id'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Live Execution & Delivery Console -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-terminal text-dark"></i> Broadcast Delivery Telemetry
                </h5>
                <span class="badge bg-secondary font-mono" id="broadcastTimer">Awaiting Trigger</span>
            </div>

            <!-- Delivery Cards Stream -->
            <div id="deliveryStream" class="flex-grow-1 overflow-auto" style="min-height: 380px; max-height: 520px;">
                <div class="text-center py-5 text-muted bg-light rounded-4 border">
                    <i class="bi bi-broadcast text-secondary fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark">Ready for Multi-Channel Broadcast</h6>
                    <small>Click "Broadcast Across Tagged Services" on the left to fire delivery across all tagged container instances.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function dispatchBroadcast() {
        const recipient = document.getElementById('recipientInput').value.trim();
        const message = document.getElementById('messageInput').value.trim();

        const selectedChannels = [];
        document.querySelectorAll('input[name="channel_toggle"]:checked').forEach(c => {
            selectedChannels.push(c.value);
        });

        if (selectedChannels.length === 0) {
            alert('Please select at least one notification channel.');
            return;
        }

        const stream = document.getElementById('deliveryStream');
        stream.innerHTML = '<div class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-warning me-2"></div>Routing through tagged container services...</div>';
        document.getElementById('broadcastTimer').innerText = 'Executing...';

        fetch('{{ route("container.broadcast.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                recipient: recipient,
                message: message,
                channels: selectedChannels
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                stream.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                return;
            }

            document.getElementById('broadcastTimer').innerText = `${data.data.total_time_ms} ms total`;

            let html = '';
            data.data.results.forEach(item => {
                html += `
                    <div class="p-3 bg-white rounded-3 border mb-2.5 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-dark"><i class="bi ${item.icon} text-${item.color} me-1"></i> ${item.channel}</strong>
                            <span class="badge bg-light text-success border font-mono small">${item.latency_ms} ms</span>
                        </div>
                        <div class="text-muted font-mono small mb-1">Target: <code>${item.recipient}</code></div>
                        <div class="small fw-semibold text-success"><i class="bi bi-check-circle-fill me-1"></i> ${item.status}</div>
                    </div>
                `;
            });

            stream.innerHTML = html;
        })
        .catch(err => {
            stream.innerHTML = `<div class="alert alert-danger">Broadcast request error: ${err.message}</div>`;
        });
    }
</script>
@endsection

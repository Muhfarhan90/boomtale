@props(['icon', 'title', 'message', 'actionUrl' => null, 'actionText' => null])

<div class="col-12 text-center py-5">
    <i class="{{ $icon }} fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">{{ $title }}</h5>
    <p class="text-muted">{{ $message }}</p>
    @if ($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="btn btn-primary mt-2">
            <i class="fas fa-arrow-left me-1"></i>{{ $actionText }}
        </a>
    @endif
</div>

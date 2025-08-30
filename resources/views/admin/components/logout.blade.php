@props(['class' => '', 'style' => 'button', 'text' => true, 'icon' => true])

<form action="{{ route('logout') }}" method="POST" class="d-inline logout-form">
    @csrf
    @if ($style === 'dropdown')
        <button type="submit" class="dropdown-item text-danger logout-btn {{ $class }}"
            onclick="return confirm('Yakin ingin logout?')">
            @if ($icon)
                <i class="fas fa-sign-out-alt"></i>
            @endif
            @if ($text)
                Logout
            @endif
        </button>
    @elseif($style === 'link')
        <button type="submit" class="nav-link logout-btn {{ $class }}"
            onclick="return confirm('Yakin ingin logout?')">
            @if ($icon)
                <i class="fas fa-sign-out-alt nav-icon"></i>
            @endif
            @if ($text)
                <span class="nav-text">Logout</span>
            @endif
        </button>
    @else
        <button type="submit" class="btn btn-outline-danger logout-btn {{ $class }}"
            onclick="return confirm('Yakin ingin logout?')">
            @if ($icon)
                <i class="fas fa-sign-out-alt"></i>
            @endif
            @if ($text)
                Logout
            @endif
        </button>
    @endif
</form>

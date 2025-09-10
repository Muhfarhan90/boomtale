{{-- filepath: d:\FREELANCE\boomtale\resources\views\components\search-bar.blade.php --}}
@props(['action', 'value' => ''])

<form method="GET" action="{{ $action }}" class="search-form">
    <div class="input-group custom-search-bar">
        <span class="input-group-text search-icon-wrapper">
            <i class="fas fa-search"></i>
        </span>
        <input type="text" name="search" class="form-control search-input"
            placeholder="Search for products..." value="{{ $value }}" aria-label="Search Products">
        <button class="btn btn-boomtale" type="submit">Search</button>
    </div>
    {{-- Slot untuk menyimpan filter lain saat melakukan pencarian --}}
    <div class="d-none">
        {{ $slot }}
    </div>
</form>

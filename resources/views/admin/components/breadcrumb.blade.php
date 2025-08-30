@if (isset($breadcrumbs))
    <nav class="breadcrumb-nav">
        <div class="breadcrumb-container">
            <div class="breadcrumb-content">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                            <i class="fas fa-home breadcrumb-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if ($loop->last)
                            <li class="breadcrumb-item active">
                                <span class="breadcrumb-current">{{ $breadcrumb['title'] }}</span>
                            </li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-link">
                                    {{ $breadcrumb['title'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div>

            <!-- Page Actions (Optional) -->
            <div class="breadcrumb-actions">
                @yield('page-actions')
            </div>
        </div>
    </nav>

    <style>
        .breadcrumb-nav {
            background: white;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--boomtale-primary);
        }

        .breadcrumb-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
            font-size: 0.875rem;
        }

        .breadcrumb-item {
            display: inline-flex;
            align-items: center;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: '›';
            color: var(--boomtale-primary);
            margin: 0 0.5rem;
            font-weight: 600;
            font-size: 1rem;
        }

        .breadcrumb-link {
            color: #6c757d;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .breadcrumb-link:hover {
            color: var(--boomtale-primary);
            background: rgba(197, 165, 114, 0.1);
            transform: translateY(-1px);
        }

        .breadcrumb-icon {
            font-size: 0.875rem;
        }

        .breadcrumb-current {
            color: var(--boomtale-primary);
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            background: rgba(197, 165, 114, 0.1);
            border-radius: 6px;
        }

        .breadcrumb-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .breadcrumb-nav {
                padding: 0.75rem 1rem;
            }

            .breadcrumb-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .breadcrumb {
                font-size: 0.8rem;
            }
        }
    </style>
@endif

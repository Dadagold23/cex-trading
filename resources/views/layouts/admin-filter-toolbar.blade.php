<div class="dashboard-card mb-6" data-aos="fade-up" data-aos-delay="100">
    <form method="GET" action="{{ $action }}" class="grid md:grid-cols-{{ $columns ?? 4 }} gap-4">
        {{ $slot }}

        <div class="flex items-end gap-3">
            <button type="submit" class="dashboard-primary">Filter</button>
            <a href="{{ $resetRoute }}" class="dashboard-action" data-click-sound="true">Reset</a>

            @if(!empty($exportRoute))
                <a href="{{ $exportRoute }}" class="dashboard-action" data-click-sound="true">Export CSV</a>
            @endif
        </div>
    </form>
</div>

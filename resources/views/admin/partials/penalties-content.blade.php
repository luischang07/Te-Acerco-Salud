<!-- Penalties Management -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
            {{ __('admin.penalties.title') }}
        </h1>
        <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
            {{ __('admin.penalties.subtitle') }}
        </p>
    </div>
    <button
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined">add</span>
        {{ __('admin.penalties.issue_penalty') }}
    </button>
</div>

<!-- Stats -->
<div id="spa-stats" x-data="adminStats({{ json_encode($stats) }})" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            {{ __('admin.penalties.total_penalties') }}
        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.total_penalties"></p>
        <p class="text-sm font-medium text-danger flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_upward</span> +12%
        </p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            {{ __('admin.penalties.active_penalties') }}
        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.active_penalties"></p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            {{ __('admin.penalties.resolved_penalties') }}
        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.resolved_penalties"></p>
        <p class="text-sm font-medium text-success flex items-center gap-1">
            <span class="material-symbols-outlined text-base">check_circle</span> 69%
        </p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            {{ __('admin.penalties.avg_resolution_time') }}
        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.avg_resolution_days + 'd'"></p>
    </div>
</div>

<!-- Filters -->
<div x-data="adminFilters({{ json_encode($filters) }})" x-init="init()"
    class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
    <form x-ref="filterForm" action="{{ route('admin.penalties') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    {{ __('common.filters.search') }}
                </label>
                <input type="text" name="search" x-model="filters.search" placeholder="{{ __('admin.penalties.search_placeholder') }}"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    {{ __('admin.penalties.filter_status') }}
                </label>
                <select name="status" x-model="filters.status"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">{{ __('common.filters.all') }}</option>
                    <option value="active">{{ __('admin.penalties.status.active') }}</option>
                    <option value="under_review">{{ __('admin.penalties.status.under_review') }}</option>
                    <option value="resolved">{{ __('admin.penalties.status.resolved') }}</option>
                    <option value="dismissed">{{ __('admin.penalties.status.dismissed') }}</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    {{ __('admin.penalties.filter_severity') }}
                </label>
                <select name="severity" x-model="filters.severity"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">{{ __('common.filters.all') }}</option>
                    <option value="low">{{ __('admin.penalties.severity.low') }}</option>
                    <option value="medium">{{ __('admin.penalties.severity.medium') }}</option>
                    <option value="high">{{ __('admin.penalties.severity.high') }}</option>
                    <option value="critical">{{ __('admin.penalties.severity.critical') }}</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    {{ __('admin.penalties.filter_pharmacy') }}
                </label>
                <select name="pharmacy" x-model="filters.pharmacy"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">{{ __('common.filters.all') }}</option>
                    <option value="1">Farmacia del Ahorro</option>
                    <option value="2">Farmacias Guadalajara</option>
                    <option value="3">Farmacias Similares</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" @click="clearFilters()" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
                    {{ __('common.actions.clear') }}
                </button>
            </div>
        </div>
        <input type="hidden" name="per_page" x-model="filters.per_page">
    </form>
</div>

<!-- Penalties Table -->
<div id="spa-results"
    class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-background-light dark:bg-background-dark">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('admin.penalties.table.id') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('admin.penalties.table.pharmacy') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('admin.penalties.table.reason') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('admin.penalties.table.severity') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('admin.penalties.table.status') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('admin.penalties.table.issued') }}
                    </th>
                    <th
                        class="px-6 py-3 text-right text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        {{ __('common.actions.title') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-border-dark">
                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $severities = ['low', 'medium', 'high', 'critical'];
                        $severityIndex = $i % 4;
                        $severityColors = [
                            'low' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                            'medium' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                            'high' => 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200',
                            'critical' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                        ];
                        $statuses = ['active', 'under_review', 'resolved', 'dismissed'];
                        $statusIndex = ($i + 1) % 4;
                        $statusColors = [
                            'active' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                            'under_review' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                            'resolved' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                            'dismissed' => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
                        ];
                        $reasons = [
                            'Delayed order fulfillment',
                            'Incorrect medication dispensed',
                            'Poor customer service',
                            'Missing prescription items',
                            'Expired medication sold',
                            'Unsanitary conditions',
                        ];
                    @endphp
                    <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary">
                                #PEN-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary">storefront</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                        @if ($i % 3 == 0)
                                            Farmacia del Ahorro
                                        @elseif($i % 2 == 0)
                                            Farmacias Guadalajara
                                        @else
                                            Farmacias Similares
                                        @endif
                                    </div>
                                    <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                        {{ __('admin.penalties.branch_number', ['number' => $i]) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-body-text dark:text-body-text-dark max-w-xs">
                                {{ $reasons[($i - 1) % count($reasons)] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $severityColors[$severities[$severityIndex]] }}">
                                {{ __('admin.penalties.severity.' . $severities[$severityIndex]) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$statuses[$statusIndex]] }}">
                                {{ __('admin.penalties.status.' . $statuses[$statusIndex]) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-body-text dark:text-body-text-dark">
                                {{ now()->subDays($i * 2)->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                                {{ now()->subDays($i * 2)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button
                                class="text-primary hover:text-primary/80 mr-3">{{ __('common.actions.view') }}</button>
                            <button
                                class="text-warning hover:text-warning/80">{{ __('common.actions.review') }}</button>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-border-light dark:border-border-dark">
        <div class="flex items-center justify-between">
            <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                {{ __('common.pagination.showing') }} <span class="font-medium">1</span>
                {{ __('common.pagination.to') }} <span class="font-medium">12</span>
                {{ __('common.pagination.of') }} <span class="font-medium">48</span>
                {{ __('common.pagination.results') }}
            </div>
            <div class="flex gap-2">
                <button
                    class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                    {{ __('common.pagination.previous') }}
                </button>
                <button class="px-3 py-1 rounded-lg bg-primary text-white">1</button>
                <button
                    class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                    2
                </button>
                <button
                    class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                    3
                </button>
                <button
                    class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                    {{ __('common.pagination.next') }}
                </button>
            </div>
        </div>
    </div>
</div>

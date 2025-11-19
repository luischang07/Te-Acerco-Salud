<!-- Orders Management -->
<div class="flex justify-between items-center mb-6">
  <div>
    <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
      {{ __('admin.orders.title') }}
    </h1>
    <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
      {{ __('admin.orders.subtitle') }}
    </p>
  </div>
  <div class="flex gap-2">
    <button
      class="flex items-center gap-2 px-4 py-2 border border-border-light dark:border-border-dark rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors">
      <span class="material-symbols-outlined">file_download</span>
      {{ __('admin.orders.export') }}
    </button>
  </div>
</div>

<!-- Stats -->
<div id="spa-stats" x-data="adminStats({{ json_encode($stats) }})" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.orders.total_orders') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.total_orders"></p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <span class="material-symbols-outlined text-base">arrow_upward</span> +8.2%
    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.orders.pending_orders') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.pending_orders"></p>
    <p class="text-sm font-medium text-warning flex items-center gap-1">
      <span class="material-symbols-outlined text-base">schedule</span> {{ __('admin.orders.needs_attention') }}
    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.orders.completed_today') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.completed_today"></p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <span class="material-symbols-outlined text-base">check_circle</span> {{ __('admin.orders.on_track') }}
    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      {{ __('admin.orders.avg_fulfillment') }}
    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.avg_fulfillment_hours + 'h'">
    </p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <span class="material-symbols-outlined text-base">trending_down</span> -15min
    </p>
  </div>
</div>

<!-- Filters -->
<div x-data="adminFilters({{ json_encode($filters) }})" x-init="init()"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
  <form x-ref="filterForm" action="{{ route('admin.orders') }}" method="GET">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('common.filters.search') }}
        </label>
        <input type="text" name="search" x-model="filters.search"
          placeholder="{{ __('admin.orders.search_placeholder') }}"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('admin.orders.filter_status') }}
        </label>
        <select name="status" x-model="filters.status"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">{{ __('common.filters.all') }}</option>
          <option value="pending">{{ __('admin.orders.status.pending') }}</option>
          <option value="in_progress">{{ __('admin.orders.status.in_progress') }}</option>
          <option value="ready">{{ __('admin.orders.status.ready') }}</option>
          <option value="completed">{{ __('admin.orders.status.completed') }}</option>
          <option value="cancelled">{{ __('admin.orders.status.cancelled') }}</option>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('admin.orders.filter_pharmacy') }}
        </label>
        <select name="pharmacy" x-model="filters.pharmacy"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">{{ __('common.filters.all') }}</option>
          <option value="1">Farmacia del Ahorro</option>
          <option value="2">Farmacias Guadalajara</option>
          <option value="3">Farmacias Similares</option>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('admin.orders.filter_date') }}
        </label>
        <select name="date" x-model="filters.date"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="today">{{ __('admin.orders.date_today') }}</option>
          <option value="week">{{ __('admin.orders.date_week') }}</option>
          <option value="month">{{ __('admin.orders.date_month') }}</option>
          <option value="custom">{{ __('admin.orders.date_custom') }}</option>
        </select>
      </div>
      <div class="flex items-end">
        <button type="button" @click="clearFilters()"
          class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
          {{ __('common.actions.clear') }}
        </button>
      </div>
    </div>
    <input type="hidden" name="per_page" x-model="filters.per_page">
  </form>
</div>

<!-- Orders Table -->
<div id="spa-results"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-background-light dark:bg-background-dark">
        <tr>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.order_id') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.patient') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.pharmacy') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.items') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.total') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.status') }}
          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.orders.table.date') }}
          </th>
          <th
            class="px-6 py-3 text-right text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('common.actions.title') }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border-light dark:divide-border-dark">
        @for ($i = 1; $i <= 15; $i++)
          @php
            $statuses = ['pending', 'in_progress', 'ready', 'completed', 'cancelled'];
            $statusIndex = $i % 5;
            $statusColors = [
              'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
              'in_progress' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
              'ready' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
              'completed' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
              'cancelled' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            ];
          @endphp
          <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-primary">
                #ORD-{{ str_pad($i * 100, 5, '0', STR_PAD_LEFT) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                  <span class="text-primary text-xs font-medium">P{{ $i }}</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                    {{ __('admin.orders.patient_name', ['number' => $i]) }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                @if ($i % 3 == 0)
                  Farmacia del Ahorro
                @elseif($i % 2 == 0)
                  Farmacias Guadalajara
                @else
                  Farmacias Similares
                @endif
              </div>
              <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                {{ __('admin.orders.branch_number', ['number' => $i]) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                {{ rand(1, 8) }} {{ __('admin.orders.items') }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                ${{ number_format(rand(150, 2500), 2) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$statuses[$statusIndex]] }}">
                {{ __('admin.orders.status.' . $statuses[$statusIndex]) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                {{ now()->subHours($i * 2)->format('M d, H:i') }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-primary hover:text-primary/80">{{ __('common.actions.view') }}</button>
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
        {{ __('common.pagination.to') }} <span class="font-medium">15</span>
        {{ __('common.pagination.of') }} <span class="font-medium">1,524</span>
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
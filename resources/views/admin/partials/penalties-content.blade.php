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
      <!-- #TODO: Add percentage -->
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
      <!-- #TODO: Add percentage -->
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="md:col-span-2">
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('common.filters.search') }}
        </label>
        <input type="text" name="search" x-model="filters.search"
          placeholder="{{ __('admin.penalties.search_placeholder') }}"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          {{ __('admin.penalties.filter_status') }}
        </label>
        <select name="status" x-model="filters.status"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">{{ __('common.filters.all') }}</option>
          <option value="activa">{{ __('admin.penalties.status.active') }}</option>
          <option value="pagada">{{ __('admin.penalties.status.paid') }}</option>
          <option value="liberada">{{ __('admin.penalties.status.waived') }}</option>
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

<!-- Penalties Table -->
<div id="spa-results"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
      <thead class="bg-background-light dark:bg-background-dark">
        <tr>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.penalties.table.id') }}
          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.penalties.table.patient') }}
          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.penalties.table.reason') }}
          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.penalties.table.amount') }}
          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.penalties.table.status') }}
          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            {{ __('admin.penalties.table.date') }}
          </th>
          <th scope="col" class="relative px-6 py-3">
            <span class="sr-only">{{ __('common.actions.actions') }}</span>
          </th>
        </tr>
      </thead>
      <tbody class="bg-card-light dark:bg-card-dark divide-y divide-border-light dark:divide-border-dark">
        @forelse ($penalties as $penalty)
          @php
            $statusColors = [
              'active' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
              'paid' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
              'waived' => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
            ];
          @endphp
          <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-primary">
                #PEN-{{ str_pad($penalty->id, 4, '0', STR_PAD_LEFT) }}
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                  <span class="material-symbols-outlined text-primary">person</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                    {{ $penalty->patient->nombre ?? 'Unknown' }} {{ $penalty->patient->apellido ?? '' }}
                  </div>
                  <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                    {{ $penalty->patient->user->email ?? '' }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-body-text dark:text-body-text-dark max-w-xs">
                {{ $penalty->reason }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                ${{ number_format($penalty->amount, 2) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$penalty->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($penalty->status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                {{ $penalty->created_at->format('M d, Y') }}
              </div>
              <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                {{ $penalty->created_at->format('H:i') }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-primary hover:text-primary/80 mr-3">{{ __('common.actions.view') }}</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="text-neutral-text dark:text-neutral-text-dark">
                {{ __('admin.penalties.no_results') }}
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if ($penalties->hasPages())
    <div
      class="px-6 py-4 border-t border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
      {{ $penalties->links('vendor.pagination.tailwind') }}
    </div>
  @endif
</div>
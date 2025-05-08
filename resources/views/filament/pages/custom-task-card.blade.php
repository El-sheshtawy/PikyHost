<div class="space-y-2">
    <h3 class="font-medium text-gray-900 dark:text-white">{{ $title }}</h3>

    @if($description)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $description }}
        </p>
    @endif

    <div class="flex flex-wrap gap-2 text-xs">
        @if($priority)
            <span class="inline-flex items-center gap-1">
                <x-heroicon-o-flag class="w-3 h-3" />
                {{ ucfirst($priority) }}
            </span>
        @endif

        @if($due_at)
            <span class="inline-flex items-center gap-1">
                <x-heroicon-o-calendar class="w-3 h-3" />
                {{ $due_at->format('M d, Y') }}
            </span>
        @endif
    </div>

    @if($assignees->count())
        <div class="mt-2">
            <span class="text-xs text-gray-500 dark:text-gray-400">Assignees:</span>
            <div class="flex flex-wrap gap-1 mt-1">
                @foreach($assignees as $user)
                    <a
                        href="{{ route('filament.admin.resources.users.edit', $user->id) }}"
                        class="px-2 py-1 text-xs rounded-full bg-primary-100 text-primary-800 hover:bg-primary-200 dark:bg-primary-900 dark:text-primary-200"
                    >
                        {{ $user->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

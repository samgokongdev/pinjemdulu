<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:kendaraan.header />
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="my-4 mx-4">
                <livewire:kendaraan.main />
            </div>
        </div>
    </div>
</x-layouts.app>
<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-auto overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="my-8 mx-4">
                    <livewire:dashboard.d1 />
                </div>
            </div>
            <div class="relative aspect-auto overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="my-8 mx-4">
                    <livewire:dashboard.d2 />
                </div>
            </div>
            <div class="relative aspect-auto overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="my-8 mx-4">
                    <livewire:dashboard.d3 />
                </div>
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="my-4 mx-4">
                <livewire:dashboard.main1 />
            </div>
        </div>
    </div>
</x-layouts.app>

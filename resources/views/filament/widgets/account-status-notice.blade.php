<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-start gap-4">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/20">
                <x-heroicon-o-clock class="h-6 w-6" />
            </span>
            <div>
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                    Your account is pending approval
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Thanks for registering, {{ auth()->user()->name }}! An administrator needs to approve your
                    account before you can list properties. You'll be able to add and manage listings as soon as
                    you're approved — please check back shortly.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

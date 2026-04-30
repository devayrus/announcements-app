<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div style="margin-top: 2rem; padding-top: 1rem;" class="border-t border-gray-200 dark:border-white/10 flex flex-wrap items-center gap-4 justify-end">
            <x-filament::button type="submit">
                Simpan Pengaturan
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

<?php

use App\Models\Sesi;
use Livewire\Volt\Component;

new class extends Component {
    public $nama_sesi;
    public $waktu;

    protected function rules()
    {
        return [
            'nama_sesi' => [
                'required',
            ],
            'waktu' => [
                'required',
            ],
        ];
    }

    public function with()
    {
        return [
            'daftar_sesi' => Sesi::all(),
        ];
    }

    #[\Livewire\Attributes\On('reloadSesi')]
    public function reloadAset() {}
}; ?>

<div>
    <div class="p-4 text-neutral-700 dark:text-neutral-200">
        <div class="grid grid-cols-3 gap-2">
            @foreach ($daftar_sesi as $d)
            <div class="cursor-pointer space-y-1 p-4 hover:bg-neutral-200 text-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-700 dark:hover:bg-neutral-200 relative aspect-auto overflow-hidden rounded-md shadow-xs border border-neutral-200 dark:border-neutral-700">
                <div class="text-xl font-semibold">{{$d->nama_sesi}}</div>
                <div class="text-base">{{$d->waktu}}</div>
            </div>
            @endforeach
        </div>
    </div>



</div>
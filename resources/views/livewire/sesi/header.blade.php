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

    public function save()
    {

        $check = $this->validate();

        $first = Sesi::create([
            'nama_sesi' => $this->nama_sesi,
            'waktu' => $this->waktu,
        ]);

        $this->modal('tambah-sesi')->close();
        $this->dispatch('reloadSesi');
    }
}; ?>

<div>
    <div class="mb-4 p-4 mt-2 flex items-center justify-between">
        <flux:heading size="xl">Daftar Sesi</flux:heading>
        <flux:modal.trigger name="tambah-sesi">
            <flux:button>Buat Sesi</flux:button>
        </flux:modal.trigger>
    </div>
    <flux:modal name="tambah-sesi" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Sesi Baru</flux:heading>
                <flux:subheading>Buat Sesi Yang Anda Inginkan</flux:subheading>
            </div>

            <flux:input label="Nama Sesi" placeholder="Contoh : Sesi #1" wire:model="nama_sesi" />

            <flux:input label="Waktu " placeholder="Contoh : Pukul 07.00 s.d. 09.00 WIB" wire:model="waktu" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="save">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
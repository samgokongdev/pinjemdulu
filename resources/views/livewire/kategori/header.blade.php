<?php

use App\Models\Kategori;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {


    public $nama;

    protected function rules()
    {
        return [
            'nama' => [
                'required',
                'min:4',
                'lowercase',
                Rule::unique('kategoris'),
            ]
        ];
    }

    public function tambahKategori()
    {
        $check = $this->validate();

        

        Kategori::create([
            'nama' => $this->nama,
        ]);

        $this->modal('tambahKategori')->close();

        $this->dispatch('reloadKategori');
    }
}; ?>

<div class="flex items-center justify-between">
    <div>
        <flux:heading size="lg">Atur Kategori</flux:heading>
    </div>
    <div>
        <flux:modal.trigger name="tambahKategori">
            <flux:button icon="plus" size="sm">Tambah Kategori</flux:button>
        </flux:modal.trigger>
        <flux:modal name="tambahKategori" variant="flyout">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Buat Kategori Baru</flux:heading>
                    <flux:subheading>Silahkan masukkan kategori kendaraan baru yang anda mau</flux:subheading>
                </div>

                <flux:input name="nama" label="Kategori" placeholder="Cth : Mobil" wire:model.live="nama" />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="button" variant="primary" wire:click="tambahKategori">Simpan</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>

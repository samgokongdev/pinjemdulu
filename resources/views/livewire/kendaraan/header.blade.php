<?php

use App\Models\Aset;
use App\Models\Kategori;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public $nama_aset;
    public $nomor_identifikasi_aset;
    public $kategori_id = "";
    public $listKategori;
    public $tahun_pengadaan;
    public $keterangan;
    public bool $is_ready = true;

    protected function rules()
    {
        return [
            'kategori_id' => [
                'required',
                Rule::exists('kategoris', 'id'),
            ],
            'nama_aset' => [
                'required',
                'min:5'
            ],
            'nomor_identifikasi_aset' => [
                'required',
                'min:5',
                'uppercase',
                Rule::unique('asets'),
            ],
            'tahun_pengadaan' => [
                'nullable',
                'integer',
                'min:1900'
            ],
            'is_ready' => [
                'required',
                'boolean',
            ],
            'keterangan' => [
                'nullable',
            ],
        ];
    }

    public function mount()
    {
        $this->listKategori = Kategori::all();
    }

    public function save()
    {
        // dd($this->nama_aset);
        // dd($this->nomor_identifikasi_aset);
        $check = $this->validate();

        Aset::create([
            'nama_aset' => $this->nama_aset,
            'nomor_identifikasi_aset' => $this->nomor_identifikasi_aset,
            'kategori_id' => $this->kategori_id,
            'tahun_pengadaan' => $this->tahun_pengadaan,
            'keterangan' => $this->keterangan,
            'is_ready' => $this->is_ready,
        ]);

        $this->modal('tambah')->close();

        $this->dispatch('reloadAset');
    }
}; ?>

<div class="flex items-center justify-between">
    <div>
        <flux:heading size="lg">Masterlist Asset</flux:heading>
    </div>
    <div>
        <flux:modal.trigger name="tambah">
            <flux:button icon="plus" size="sm">Tambah Aset</flux:button>
        </flux:modal.trigger>
        <flux:modal name="tambah" variant="flyout">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Tambahkan Aset</flux:heading>
                    <flux:subheading>Lengkapi form di bawah ini.</flux:subheading>
                </div>

                <flux:input name="nama" label="Nama Aset" placeholder="Cth : Toyota Avanza" wire:model.live="nama_aset" />
                <flux:input name="nomor_identifikasi_aset" label="Nomor Identifikasi" placeholder="Dapat diisi Plat Nomor, Nomor Seri, dsb" wire:model.live="nomor_identifikasi_aset" />
                <flux:select wire:model="kategori_id" label="Kategori Aset" placeholder="Pilihan Kategori Aset...">
                    @foreach ($listKategori as $l)
                    <flux:select.option value="{{ $l->id }}">{{ $l->nama }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input name="tahun_pengadaan" label="Tahun Pengadaan" placeholder="Cth : 2024" wire:model="tahun_pengadaan" />
                <flux:switch wire:model.live="is_ready" label="Dapat Dipinjam" />
                <flux:textarea rows="auto" label="Keterangan" wire:model="keterangan" />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="button" variant="primary" wire:click="save">Simpan</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
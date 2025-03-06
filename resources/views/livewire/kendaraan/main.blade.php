<?php

use App\Models\Aset;
use App\Models\Kategori;
use Livewire\Attributes\Locked;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public $asetId;

    public $nama_aset;
    public $nomor_identifikasi_aset;
    public $kategori_id;

    #[Locked]
    public $listKategori;

    public $tahun_pengadaan;
    public $keterangan;
    public bool $is_ready;

    public function openModal($id)
    {
        $this->modal('editAset')->show();
        $aset = Aset::findOrFail($id);
        $this->asetId = $aset->id;
        $this->nama_aset = $aset->nama_aset;
        $this->nomor_identifikasi_aset = $aset->nomor_identifikasi_aset;
        $this->kategori_id = $aset->kategori_id;
        $this->tahun_pengadaan = $aset->tahun_pengadaan;
        $this->is_ready = $aset->is_ready;
        $this->keterangan = $aset->keterangan;
    }

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
                Rule::unique('asets')->ignore($this->asetId),
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

    public function update()
    {
        $check = $this->validate();

        $aset = Aset::findOrFail($this->asetId);
        $aset->update([
            'nama_aset' => $this->nama_aset,
            'nomor_identifikasi_aset' => $this->nomor_identifikasi_aset,
            'kategori_id' => $this->kategori_id,
            'tahun_pengadaan' => $this->tahun_pengadaan,
            'keterangan' => $this->keterangan,
            'is_ready' => $this->is_ready,
        ]);

        $this->modal('editAset')->close();

        $this->dispatch('reloadAset');
    }

    public function delete($id)
    {
        $aset = Aset::findOrFail($id);
        $aset->delete();

        $this->modal('editAset')->close();
        $this->modal('deleteAset')->close();

        $this->dispatch('reloadAset');
    }

    public function mount()
    {
        $this->listKategori = Kategori::all();
    }

    public function with()
    {
        return [
            'aset' => Aset::all()
        ];
    }

    #[\Livewire\Attributes\On('reloadAset')]
    public function reloadAset() {}
}; ?>

<div class="">
    @if(count($aset) == 0)
    <div class="flex w-full h-full text-zinc-500 items-center justify-center mt-8 italic">Belum Ada data kendaraan</div>
    @endif

    @isset($aset)
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        @foreach ($aset as $k)
        <div class="relative aspect-auto overflow-hidden rounded-md shadow-xs border cursor-pointer {{ $k->is_ready ? 'bg-cyan-50 hover:bg-cyan-100 dark:bg-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-600' : 'bg-rose-100 hover:bg-rose-200 dark:bg-rose-900 dark:hover:bg-rose-950' }} border-neutral-200 dark:border-neutral-700" wire:click="openModal({{ $k->id }})">
            <div class="px-4 py-4">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-base">{{ $k->nama_aset }}</span>
                    <flux:badge color="{{ $k->is_ready ? 'cyan' : 'rose' }}" size="sm">{{ $k->kategori->nama }}</flux:badge>
                </div>
                <div class="mt-4">
                    <div class="text-sm">
                        Nomor Identifikasi Aset : {{ $k->nomor_identifikasi_aset }}
                    </div>
                    @isset($k->keterangan)
                    <div class="mt-2 text-xs text-zinc-400 italic">{{ $k->keterangan }}</div>
                    @endisset
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endisset

    <flux:modal name="editAset" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Aset</flux:heading>
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

            <div class="flex space-x-2">
                <flux:spacer />
                <flux:modal.trigger name="deleteAset">
                    <flux:button variant="danger">Hapus Aset</flux:button>
                </flux:modal.trigger>
                <!-- <flux:button type="button" variant="danger" wire:click="delete({{$asetId}})">Delete</flux:button> -->
                <flux:button class="ml-2" type="button" variant="primary" wire:click="update">Update Aset</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="deleteAset" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Anda Yakin?</flux:heading>

                <flux:subheading>
                    <p>Aset ini akan dihapus</p>
                    <p>Data yang sudah dihapus akan hilang dari sistem</p>
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Batalkan</flux:button>
                </flux:modal.close>

                <flux:button class="cursor-pointer" type="button" variant="danger" wire:click="delete({{$asetId}})">Hapus Aset</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
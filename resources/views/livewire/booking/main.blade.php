<?php

use App\Models\Aset;
use App\Models\Booking;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public $asetId;

    #[Locked]
    public $nama_aset;

    #[Locked]
    public $nomor_identifikasi_aset;

    #[Locked]
    public $kategori_aset;

    // #[Locked]
    public $tanggal_booking;
    public $nama_peminjam;
    public $seksi;
    public $keperluan;

    protected function rules()
    {
        return [
            'tanggal_booking' => [
                'required',
                'date',
            ],
            'nama_peminjam' => [
                'required',
                'min:4'
            ],
            'seksi' => [
                'nullable',
                'min:4'
            ],
            'keperluan' => [
                'required',
                'min:20'
            ]

        ];
    }

    public function with()
    {
        return [
            'katalog' => Kategori::with(['asets' => function ($query) {
                $query->where('is_ready', true);
            }])->get(),
        ];
    }

    public function openModalCekTgl($id)
    {
        $this->modal('cekTanggal')->show();
        $aset = Aset::findOrFail($id);
        $this->asetId = $aset->id;
        $this->nama_aset = $aset->nama_aset;
        $this->nomor_identifikasi_aset = $aset->nomor_identifikasi_aset;
        $this->kategori_aset = $aset->kategori->nama;
    }

    public function pesan()
    {
        $check = $this->validate();

        $first = Booking::create([
            'nama_peminjam' => $this->nama_peminjam,
            'seksi' => $this->seksi,
            'keperluan' => $this->keperluan,
            'tanggal_booking' => $this->tanggal_booking,
            'kode_booking' => 'BOOK-' . now()->format('YmdHis') . '-' . Str::random(10),
            'aset_id' => $this->asetId,
            'user_id' => Auth::user()->id
        ]);

        $this->modal('cekTanggal')->close();

        // $this->dispatch('reloadAset');
    }
}; ?>

<div class="text-neutral-700 dark:text-neutral-200">
    <flux:heading size="xl"><span class="font-bold">Katalog Aset</span></flux:heading>
    @if (count($katalog) == 0)
    <div>Belum Ada Aset Yang Tersedia</div>
    @endif

    @isset($katalog)
    <div class="flex flex-col space-y-4 mt-8">
        @foreach ($katalog as $k)
        @if ($k->asets->count() > 0)
        <flux:heading size="lg">
            <span class="font-bold">
                {{ strtoupper($k->nama) }}
            </span>
        </flux:heading>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @foreach ($k->asets as $s)
            <div
                class="relative aspect-auto overflow-hidden rounded-md shadow-xs border cursor-pointer {{ $s->is_ready ? 'bg-cyan-50 hover:bg-cyan-100 dark:bg-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-600' : 'bg-rose-100 hover:bg-rose-200 dark:bg-rose-950 dark:hover:bg-rose-900' }} border-neutral-200 dark:border-neutral-700"
                wire:click="openModalCekTgl({{ $s->id }})">
                <div class="px-4 py-4">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-base">{{ $s->nama_aset }}</span>
                        <flux:badge color="{{ $s->is_ready ? 'cyan' : 'rose' }}" size="sm">{{ $s->kategori->nama }}
                        </flux:badge>
                    </div>
                    <div class="mt-4">
                        <div class="text-sm">
                            Nomor Identifikasi Aset : {{ $s->nomor_identifikasi_aset }}
                        </div>
                        @isset($s->keterangan)
                        <div class="mt-2 text-xs text-zinc-400 italic">{{ $s->keterangan }}</div>
                        @endisset
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
    </div>
    @endisset


    <!-- Modal Cek Apakah Sesi Tersedia Atau Tidak -->
    <flux:modal name="cekTanggal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Rencana Booking</flux:heading>
                <flux:subheading>Kapan anda berencana menggunakan aset ini?</flux:subheading>
            </div>
            <div class="space-y-2">
                <flux:input readonly variant="filled" value="{{$nama_aset}}" label="Nama Aset" />
                <flux:input readonly variant="filled" value="{{$nomor_identifikasi_aset}}" label="Nomor Identifikasi" />
                <flux:input readonly variant="filled" value="{{strtoupper($kategori_aset)}}" label="Jenis Aset" />
                <flux:input type="date" max="2999-12-31" label="Date" wire:model="tanggal_booking" />
                <flux:input label="Nama Peminjam" placeholder="Cth : Boaz Salosa" wire:model="nama_peminjam" />
                <flux:input label="Seksi" placeholder="Cth : Boaz Salosa" wire:model="seksi" />
                <flux:textarea rows="auto" wire:model="keperluan" label="Keperluan" />
            </div>


            <!-- <flux:input name="nama" label="Nama Kategori" placeholder="Cth : Toyota Avanza"
                wire:model.live="namaKategori" /> -->

            <div class="flex">
                <flux:spacer />

                <flux:button type="button" variant="primary" wire:click="pesan">Update</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
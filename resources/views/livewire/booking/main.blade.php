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
    public $jam_mulai = "06:00:00";
    public $jam_selesai = "06:59:00";

    protected function rules()
    {
        return [
            'tanggal_booking' => [
                'required',
                'date',
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i:s',
                'before_or_equal:23:59:00',
                function ($attribute, $value, $fail) {
                    $this->validateJamMulai($fail);
                }
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i:s',
                'before_or_equal:23:59:00',
                function ($attribute, $value, $fail) {
                    $this->validateJamSelesai($fail);
                }
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
            'histori' => Booking::where('jam_selesai', '>', '11:00')->get()
        ];
    }

    private function validateJamMulai($fail)
    {
        $conflict = Booking::where('tanggal_booking', $this->tanggal_booking)
            ->where('aset_id', $this->asetId)
            ->where(function ($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function ($query) {
                        $query->where('jam_mulai', '<', $this->jam_mulai)
                            ->where('jam_selesai', '>', $this->jam_selesai);
                    });
            })
            ->exists();

        if ($conflict) {
            $fail('Jam booking bertabrakan dengan jadwal yang sudah ada.');
        }
    }

    private function validateJamSelesai($fail)
    {
        if ($this->jam_selesai <= $this->jam_mulai) {
            $fail('Jam selesai harus setelah jam mulai.');
        }
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
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kode_booking' => 'BOOK-' . now()->format('YmdHis') . '-' . Str::random(10),
            'aset_id' => $this->asetId,
            'user_id' => Auth::user()->id
        ]);

        $this->modal('cekTanggal')->close();

        session()->flash('status', 'Data Booking Berhasil Ditambahkan');

        $this->redirect('/histori');
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
                <flux:select size="sm" placeholder="Choose industry..." wire:model="jam_mulai" label="Jam Mulai">
                    <flux:select.option value="06:00:00">06:00</flux:select.option>
                    <flux:select.option value="07:00:00">07:00</flux:select.option>
                    <flux:select.option value="08:00:00">08:00</flux:select.option>
                    <flux:select.option value="09:00:00">09:00</flux:select.option>
                    <flux:select.option value="10:00:00">10:00</flux:select.option>
                    <flux:select.option value="11:00:00">11:00</flux:select.option>
                    <flux:select.option value="12:00:00">12:00</flux:select.option>
                    <flux:select.option value="13:00:00">13:00</flux:select.option>
                    <flux:select.option value="14:00:00">14:00</flux:select.option>
                    <flux:select.option value="15:00:00">15:00</flux:select.option>
                    <flux:select.option value="16:00:00">16:00</flux:select.option>
                    <flux:select.option value="17:00:00">17:00</flux:select.option>
                </flux:select>
                <flux:select size="sm" placeholder="Choose industry..." wire:model="jam_selesai" label="Jam Selesai">
                    <flux:select.option value="06:59:00">07:00</flux:select.option>
                    <flux:select.option value="07:59:00">08:00</flux:select.option>
                    <flux:select.option value="08:59:00">09:00</flux:select.option>
                    <flux:select.option value="09:59:00">10:00</flux:select.option>
                    <flux:select.option value="10:59:00">11:00</flux:select.option>
                    <flux:select.option value="11:59:00">12:00</flux:select.option>
                    <flux:select.option value="12:59:00">13:00</flux:select.option>
                    <flux:select.option value="13:59:00">14:00</flux:select.option>
                    <flux:select.option value="14:59:00">15:00</flux:select.option>
                    <flux:select.option value="15:59:00">16:00</flux:select.option>
                    <flux:select.option value="16:59:00">17:00</flux:select.option>
                    <flux:select.option value="17:59:00">18:00</flux:select.option>
                </flux:select>
                <flux:input label="Nama Peminjam" placeholder="Cth : Boaz Salosa" wire:model="nama_peminjam" />
                <flux:input label="Seksi" placeholder="Cth : Boaz Salosa" wire:model="seksi" />
                <flux:textarea rows="auto" wire:model="keperluan" label="Keperluan" />
            </div>
            <div class="flex">
                <flux:spacer />

                <flux:button type="button" variant="primary" wire:click="pesan">Simpan</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
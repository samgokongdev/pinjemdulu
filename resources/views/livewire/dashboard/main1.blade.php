<?php

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public $today;

    public function mount()
    {
        $this->today = Carbon::today()->toDateString();
    }

    public function with()
    {
        return [
            'histori' => Booking::where('tanggal_booking', $this->today)
                ->orderBy('tanggal_booking', 'ASC')
                ->orderBy('jam_mulai', 'ASC')
                ->get(),
        ];
    }
}; ?>

<div>
    <div class="mb-2">
        <flux:heading size="lg">Kendaraan terbooking hari ini</flux:heading>
    </div>
    <flux:separator />
    <div class="mt-4">
        <table class="table-auto w-full border border-gray-300 text-sm">
            <thead>
                <tr class="bg-neutral-100 dark:bg-neutral-700">
                    <th class="border px-4 py-2 w-4/12">Aset</th>
                    <th class="border px-4 py-2 w-4/12">Seksi</th>
                    <th class=" border px-4 py-2 w-4/12"">Waktu Peminjaman</th>
        </tr>
      </thead>
      <tbody>
        @if (count($histori) === 0)
          <tr>
            <td class=" border px-4 py-2 text-center italic" colspan="3">
                        tidak ada histori booking
                        </td>
                </tr>
                @endif
                @foreach ($histori as $h)
                <tr>
                    <td class="border px-4 py-2 text-center">
                        {{ $h->aset->nama_aset }}-{{ $h->aset->nomor_identifikasi_aset }}
                    </td>

                    <td class="border px-4 py-2 text-center">
                        {{ $h->seksi }}
                    </td>

                    <td class=" border px-4 py-2 text-center">
                        Tanggal {{ \Carbon\Carbon::parse($h->tanggal_booking)->format('d/m/Y') }} Pukul : {{ \Carbon\Carbon::parse($h->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($h->jam_selesai)->format('H:i') }}
                    </td>

                </tr>
                @endforeach
                </tbody>
        </table>
    </div>
</div>
<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
  public function with()
  {
    return [
      'histori' => Booking::where('user_id', '=', Auth::user()->id)
        ->orderBy('tanggal_booking', 'ASC')
        ->orderBy('jam_mulai', 'ASC')
        ->get(),
    ];
  }

  public function bookingDetail($id)
  {
    $this->redirectRoute('bookingdetail', ['id' => $id]);
  }
}; ?>

<div>
  <div class="p-4">
    <flux:heading size="xl">Histori Booking</flux:heading>
    <flux:subheading>berikut daftar booking yang pernah anda pesan</flux:subheading>
  </div>
  <div class="p-4">
    <table class="table-auto w-full border border-gray-300 text-sm">
      <thead>
        <tr class="">
          <th class="border px-4 py-2 w-2/12">Kode Booking</th>
          <th class="border px-4 py-2 w-1/12">Jenis Aset</th>
          <th class="border px-4 py-2 w-1/12">Nomor Identifikasi</th>
          <th class="border px-4 py-2 w-1/12">Nama Peminjam</th>
          <th class="border px-4 py-2 w-2/12">Seksi</th>
          <th class="border px-4 py-2 w-1/12"">Tanggal Peminjaman</th>
          <th class=" border px-4 py-2 w-1/12"">Pukul Peminjaman</th>
          <th class=" border px-4 py-2 w-1/12"">Status</th>
        </tr>
      </thead>
      <tbody>
        @if (count($histori) === 0)
          <tr>
            <td class=" border px-4 py-2 text-center italic" colspan="8">
            tidak ada histori booking
            </td>
        </tr>
        @endif
        @foreach ($histori as $h)
        <tr>
          <td class=" border px-4 py-2 cursor-pointer hover:font-bold text-blue-700 dark:text-blue-500"
            wire:click="bookingDetail({{ $h->id }})">
            {{ $h->kode_booking }}
          </td>
          <td class="border px-4 py-2 text-center">
            {{ $h->aset->kategori->nama }}
          </td>
          <td class="border px-4 py-2 text-center">
            {{ $h->aset->nama_aset }}-{{ $h->aset->nomor_identifikasi_aset }}
          </td>
          <td class="border px-4 py-2">
            {{ $h->nama_peminjam }}
          </td>
          <td class="border px-4 py-2">
            {{ $h->seksi }}
          </td>
          <td class="border px-4 py-2 text-center">
            {{ \Carbon\Carbon::parse($h->tanggal_booking)->format('d-m-Y') }}
          </td>
          <td class="border px-4 py-2 text-center">
            {{ \Carbon\Carbon::parse($h->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($h->jam_selesai)->format('H:i') }}
          </td>
          <td class="border px-4 py-2 italic">
            {{ $h->is_done ? 'Selesai' : 'Menunggu Persetujuan' }}
          </td>
        </tr>
        @endforeach
        </tbody>
    </table>
  </div>
</div>
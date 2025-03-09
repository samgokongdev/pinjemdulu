<?php

use App\Models\Booking;
use App\Models\Sesi;
use App\Models\Sesibooking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public $id; // Properti untuk menangkap parameter

    public $list_sesi_default;
    public $booking_datas;
    public $selected_sesi;
    public $edited_sesi;

    public function mount()
    {

        $this->booking_datas = Booking::where('id', '=', $this->id)->first();
        if ($this->booking_datas->user_id != Auth::user()->id) {
            // $this->redirectRoute('histori');
            abort(403, 'ANDA SIAPA HAH???');
        }
    }

    public function with()
    {
        return [
            'booking_data' => Booking::where('id', '=', $this->id)->first(),
        ];
    }
}; ?>

<div>
    <div class="p-4 text-neutral-700 dark:text-neutral-200">
        <div class="mb-4 mt-2 flex items-center justify-between">
            <flux:heading size="xl">Data Rincian Peminjaman {{$id}}</flux:heading>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="py-2">
                    <flux:heading>Kode Booking</flux:heading>
                    <flux:subheading>{{$booking_data->kode_booking}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Nama Peminjam</flux:heading>
                    <flux:subheading>{{$booking_data->nama_peminjam}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>User ID</flux:heading>
                    <flux:subheading>{{$booking_data->user_id}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Seksi Peminjam</flux:heading>
                    <flux:subheading>{{$booking_data->seksi}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Status</flux:heading>
                    <flux:subheading>{{ $booking_data->is_done ? 'Disetujui' : 'Menunggu Persetujuan' }}</flux:subheading>
                </div>
                @if ($booking_data->is_done)
                <div class="py-2">
                    <flux:heading>Status</flux:heading>
                    <flux:subheading>{{ $booking_data->is_app ? 'Disetujui' : 'Tidak Disetujui' }}</flux:subheading>
                </div>
                @endif
            </div>
            <div>
                <div class="py-2">
                    <flux:heading>Jenis Aset</flux:heading>
                    <flux:subheading>{{strtoupper($booking_data->aset->kategori->nama)}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Nama Aset</flux:heading>
                    <flux:subheading>{{$booking_data->aset->nama_aset}} ({{$booking_data->aset->nomor_identifikasi_aset}})</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Tanggal Peminjaman</flux:heading>
                    <flux:subheading>{{$booking_data->tanggal_booking}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Pukul</flux:heading>
                    <flux:subheading>{{ \Carbon\Carbon::parse($booking_data->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($booking_data->jam_selesai)->format('H:i') }} WIB</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Keperluan</flux:heading>
                    <flux:subheading>{{$booking_data->keperluan}}</flux:subheading>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

use App\Models\Booking;
use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public $id; // Properti untuk menangkap parameter

    public function mount($id)
    {
        $this->id = $id;
    }

    public function with()
    {
        return [
            'booking_data' => Booking::find($this->id)->first(),
        ];
    }
}; ?>

<div>
    <div class="p-4 text-neutral-700 dark:text-neutral-200">
        <div class="mb-4 mt-2 flex items-center justify-between">
            <flux:heading size="xl">Data Rincian Peminjaman</flux:heading>
            <flux:button variant="primary">Simpan</flux:button>
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
                    <flux:heading>Seksi Peminjam</flux:heading>
                    <flux:subheading>{{$booking_data->seksi}}</flux:subheading>
                </div>
                <div class="py-2">
                    <flux:heading>Status</flux:heading>
                    <flux:subheading>{{ $booking_data->is_app ? 'Disetujui' : 'Menunggu Persetujuan' }}</flux:subheading>
                </div>
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
                    <flux:heading>Keperluan</flux:heading>
                    <flux:subheading>{{$booking_data->keperluan}}</flux:subheading>
                </div>
            </div>
        </div>

        <div class="mb-4 mt-2 flex items-center justify-between">
            <flux:heading size="xl">Sesi</flux:heading>
        </div>
    </div>
</div>
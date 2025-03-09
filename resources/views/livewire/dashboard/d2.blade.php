<?php

use App\Models\Aset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public $content;
    public $today;

    public function mount()
    {
        $this->today = Carbon::today()->toDateString();
        $this->content = DB::table('bookings')->whereDate('tanggal_booking', $this->today)->distinct('aset_id')->count('aset_id'); // Hitung jumlah aset unik
    }
}; ?>

<div>
    <flux:subheading>Jumlah Kendaraan Sudah Booking Hari ini</flux:subheading>
    <div class="text-4xl font-semibold mt-4">
        <span>{{ $content }}</span>
    </div>
</div>
<?php

use App\Models\Aset;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public $content;

    public function mount()
    {
        $this->content = Aset::count();
    }
};
?>
<div>
    <flux:subheading>Jumlah Kendaraan Terdaftar di Aplikasi</flux:subheading>
    <div class="text-4xl font-semibold mt-4">
        <span>{{ $content }}</span>
    </div>
</div>
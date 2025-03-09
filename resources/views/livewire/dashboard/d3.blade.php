<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public $content;

    public function mount()
    {
        $this->content = User::where('is_admin', false)->count(); // Hitung jumlah aset unik
    }
}; ?>

<div>
    <flux:subheading>Jumlah User Terdaftar</flux:subheading>
    <div class="text-4xl font-semibold mt-4">
        <span>{{ $content }}</span>
    </div>
</div>
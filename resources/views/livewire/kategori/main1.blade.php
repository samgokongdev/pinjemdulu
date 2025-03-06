<?php

use App\Models\Kategori;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public $kategoriId;
    public $namaKategori;

    public function with()
    {
        return [
            'kategori' => Kategori::all(),
        ];
    }

    protected function rules()
    {
        return [
            'namaKategori' => ['required', 'min:4', 'lowercase', Rule::unique('kategoris')->ignore($this->kategoriId)],
        ];
    }

    public function openModal($id)
    {
        $this->modal('editKategori')->show();
        $kategori = Kategori::findOrFail($id);
        $this->kategoriId = $kategori->id;
        $this->namaKategori = $kategori->nama;
    }

    public function update()
    {
        $check = $this->validate();

        $aset = Kategori::findOrFail($this->kategoriId);
        $aset->update([
            'nama' => $this->namaKategori,
        ]);

        $this->modal('editKategori')->close();
        $this->dispatch('reloadKategori');
    }

    #[\Livewire\Attributes\On('reloadKategori')]
    public function reloadKategori() {}
}; ?>

<div>
  <div class="grid auto-rows-min gap-4 md:grid-cols-3">
    @if (count($kategori) == 0)
      <div>Tidak Ada Data Kategori</div>
    @endif

    @isset($kategori)
      @foreach ($kategori as $k)
        <div wire:click="openModal({{ $k->id }})"
          class="cursor-pointer hover:bg-neutral-200 text-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-700 dark:hover:bg-neutral-200 relative aspect-auto overflow-hidden rounded-md shadow-xs border border-neutral-200 dark:border-neutral-700">
          <div class="px-4 py-4">
            <div class="flex justify-between items-center">
              <span class="font-bold ">{{ $k->nama }}</span>
            </div>
          </div>
        </div>
      @endforeach
    @endisset
  </div>

  <flux:modal name="editKategori" variant="flyout">
    <div class="space-y-6">
      <div>
        <flux:heading size="lg">Edit Kategori</flux:heading>
        <flux:subheading>Lengkapi form di bawah ini.</flux:subheading>
      </div>

      <flux:input name="nama" label="Nama Kategori" placeholder="Cth : Toyota Avanza"
        wire:model.live="namaKategori" />

      <div class="flex">
        <flux:spacer />

        <flux:button type="button" variant="primary" wire:click="update">Update</flux:button>
      </div>
    </div>
  </flux:modal>

</div>

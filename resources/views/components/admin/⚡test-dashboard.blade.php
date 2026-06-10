<?php

use Livewire\Component;

new class extends Component {
    public $message = "Hello dari Livewire 4!";
    
    public function updateMessage()
    {
        $this->message = "Tombol diklik! Livewire 4 bekerja!";
    }
    
    // HAPUS method render()! Di SFC Livewire 4, view otomatis adalah sisa file ini.
};

?>

<div class="p-6 bg-white rounded shadow">
    <h3 class="text-lg font-bold mb-4">Testing Livewire 4</h3>
    <p class="mb-4">{{ $message }}</p>
    <button wire:click="updateMessage" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Klik Saya!
    </button>
</div>
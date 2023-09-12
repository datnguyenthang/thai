<?php

namespace App\Http\Livewire\Frontend\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SlideWrapper extends Component
{
    public $folderBanner = 'appearance/banner/';
    public $bannerImages = [];

    public function mount(){
        $files = Storage::disk('public')->allFiles($this->folderBanner);

        foreach ($files as $file) { 
            $url = Storage::disk('public')->url($file);
            //$path = Storage::disk('public')->path($file);
            
            $this->bannerImages[] = [
                'url' => $url,
                'path' => $file,
                'name' => basename($file),
                'size' => Storage::disk('public')->size($file),
                'extension' => Storage::disk('public')->mimeType($file),
            ];
        }
    }

    public function render() {
        return view('livewire.frontend.layout.slide-wrapper');
    }
}

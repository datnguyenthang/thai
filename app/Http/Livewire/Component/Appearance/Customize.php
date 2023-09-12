<?php

namespace App\Http\Livewire\Component\Appearance;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Customize extends Component
{
    use WithFileUploads;

    public $folderBanner = 'appearance/banner/';
    public $bannerImages = [];
    public $bannerFiles;

    public $counting = 1;

    public function mount(){
        $this->loadBannerfiles();
    }

    //Loading slide images
    public function loadBannerfiles() {
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

    public function deleteFile($filePath) {

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $this->bannerFiles = null;
        $this->bannerImages = null;

        $this->loadBannerfiles();
    }

    public function upload() {
        //use disk
        $disk = 'public';

        if (!Storage::exists($this->folderBanner)) {
            Storage::disk($disk)->makeDirectory($this->folderBanner, 0777, true, true);
        }

        foreach ($this->bannerFiles as $bannerFile) {
            $bannerFile->storeAs($this->folderBanner, $bannerFile->getClientOriginalName(), $disk);
        }

        $this->counting++;
        $this->bannerFiles = null;
    }

    public function save(){
        if ($this->bannerFiles) $this->upload();

        // Reset input fields
        $this->bannerFiles = '';
        return redirect()->route('customizeHomepage');
    }

    public function render(){
        $user = auth()->user();

        switch ($user->role) {
            case 'manager':
                return view('livewire.component.appearance.customize')
                            ->layout('manager.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}

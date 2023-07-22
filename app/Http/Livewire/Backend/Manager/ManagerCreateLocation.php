<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Location;

class ManagerCreateLocation extends Component
{
    use WithFileUploads;

    public $locationId;
    public $name;
    public $nameOffice;
    public $googleMapUrl;
    public $status;

    public $nextLocationId;
    public $locationFile = [];
    public $files;
    public $folderName = 'location/';

    public $counting = 1;
    public $locationFiles = [];

    public function mount($locationId = 0)
    {
        $this->locationId = $locationId;
        
        if ($locationId > 0) {
            //set folder name
            //$this->folderName = $this->folderName.''.$locationId.'/';
            $location = Location::find($locationId);

            $this->name = $location->name;
            $this->nameOffice = $location->nameOffice;
            $this->googleMapUrl = $location->googleMapUrl;
            $this->status = $location->status;
        } else {
            $this->nextLocationId = Location::max('id') + 1;
            //$this->folderName = $this->folderName.''.$this->nextLocationId.'/';

        }

        $this->loadFiles();
    }

    //Loading photo images
    public function loadFiles() {
        $this->locationFiles = null;
        if(!$this->locationId) return $this->locationFiles = null;

        $this->folderName = $this->folderName.''.$this->locationId.'/';

        $files = Storage::disk('public')->allFiles($this->folderName);

        foreach ($files as $file) { 
            $url = Storage::disk('public')->url($file);
            //$path = Storage::disk('public')->path($file);
            
            $this->locationFiles[] = [
                'url' => $url,
                'path' => $file,
                'name' => basename($file),
                'size' => Storage::disk('public')->size($file),
                'extension' => Storage::disk('public')->mimeType($file),
            ];
        }
    }

    //Delete files -- NOT USE
    public function deleteFile($filePath) {

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $this->loadFiles();
    }

    //Upload proof images -- NOT USE
    public function uploadFile($locationId) {
        $this->validate([
           'files' => 'required|file|mimes:png,jpg,pdf,doc,docx,csv|max:5120',
        ]);

        //creating folder inside storage folder
        $disk = 'public';
        $this->folderName = $this->folderName.''.$locationId.'/';

        if (!Storage::exists($this->folderName)) {
            Storage::disk($disk)->makeDirectory($this->folderName, 0777, true, true);
        }

        if ($this->files) {
            $this->files->storeAs($this->folderName, $this->files->getClientOriginalName(), 'public');
        }

        $this->counting++;
        $this->files = null;

        //get image upload again
        $this->loadFiles();
    }

    public function upload($locationId) {
        //use disk
        $disk = 'public';

        if (!empty($this->locationFiles)){
            
            //Delete exist files, allow 1 file only
            foreach ($this->locationFiles as $file){
                if (Storage::disk($disk)->exists($file['path'])) {
                    Storage::disk($disk)->delete($file['path']);
                }
            }
        }
        
        //creating folder inside storage folder
        $this->folderName = $this->folderName.''.$locationId.'/';

        if (!Storage::exists($this->folderName)) {
            Storage::disk($disk)->makeDirectory($this->folderName, 0777, true, true);
        }

        if ($this->files) {
            $this->files->storeAs($this->folderName, $this->files->getClientOriginalName(), $disk);
        }
    }

    public function save(){
        $this->validate([
            'name' => 'required|unique:locations,name,' . $this->locationId,
            'nameOffice' => 'required',
            'googleMapUrl' => 'required|url',
            'files' => ['nullable',
                        'file',
                        'mimes:pdf',
                        'max:5120',
                                Rule::requiredIf(function () {
                                    if (empty($this->locationFiles) && $this->locationId) return true;
                                    if (!$this->locationId) return true;
                                    return false;
                                }),
                        ],
        ]);

        if ($this->locationId > 0){ // update location

            $location = Location::find($this->locationId);
            $location->name = $this->name;
            $location->nameOffice = $this->nameOffice;
            $location->googleMapUrl = $this->googleMapUrl;
            $location->status = intVal($this->status);
            $location->save();

            session()->flash('success', 'Location updated successfully!');
            
        } else { // create location
           
            $location = Location::create([
                'name' => $this->name,
                'nameOffice' => $this->nameOffice,
                'googleMapUrl' => $this->googleMapUrl,
                'status' => intVal($this->status),
            ]);

            session()->flash('success', 'Location created successfully!');
        }

        if ($this->files) $this->upload($location->id);

        // Reset input fields
        $this->name = '';
        return redirect()->route('managerLocation');
    }

    public function render(){
        return view('livewire.backend.manager.manager-create-location')
        ->layout('manager.layouts.app');
    }
}

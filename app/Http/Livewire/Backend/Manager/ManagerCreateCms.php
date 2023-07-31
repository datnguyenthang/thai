<?php

namespace App\Http\Livewire\Backend\Manager;

use MSA\LaravelGrapes\Models\Page;
use MSA\LaravelGrapes\Services\GenerateFrontEndService;

use Livewire\Component;

class ManagerCreateCms extends Component
{
    public $pageId;
    public $name;
    public $slug;

    public function mount($pageId = 0){

        if ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->name = $page->name;
            $this->slug = $page->slug;
        }
    }

    public function save(){
        $this->validate([
            'name' => 'required|unique:pages,name,' . $this->pageId,
            'slug' => 'required|unique:pages,slug,' . $this->pageId,
        ]);

        $generate_frontend_service = new GenerateFrontEndService();

        if ($this->pageId > 0){

            $page = Page::findOrFail($this->pageId );

            $old_slug = $page->slug;
            $page->update(['name' => $this->name, 'slug' => $this->slug]);
            $generate_frontend_service->updateRouteName($old_slug, $page->slug);

            session()->flash('success', 'Page updated successfully!');
            
        } else {

            $page = Page::create(['name' => $this->name, 'slug' => $this->slug]);

            session()->flash('success', 'Page created successfully!');
        }
        // Reset input fields

        return redirect()->route('managerCms');
    }

    public function render(){
        return view('livewire.backend.manager.manager-create-cms')
                    ->layout('manager.layouts.app');
    }
}

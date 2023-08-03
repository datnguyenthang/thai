<?php

namespace App\Http\Livewire\Backend\Manager;

use MSA\LaravelGrapes\Models\Page;
use MSA\LaravelGrapes\Services\GenerateFrontEndService;

use Livewire\Component;

class ManagerCms extends Component
{
    public $pages;
    public $url;

    public function mount(){
        $this->pages = Page::select('id', 'name', 'slug')->get();
    }

    public function deletePage($pageId){
        $generate_frontend_service = new GenerateFrontEndService();

        $page = Page::findOrFail($pageId);
        $page->delete();
        $generate_frontend_service->destroyPage($page);

        $this->pages = Page::select('id', 'name', 'slug')->get();

        session()->flash('success', 'Page deleted successfully!');
    }

    public function render(){
        return view('livewire.backend.manager.managercms')->layout('manager.layouts.app');
    }
}

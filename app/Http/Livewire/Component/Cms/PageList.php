<?php

namespace App\Http\Livewire\Component\Cms;

use MSA\LaravelGrapes\Models\Page;
use MSA\LaravelGrapes\Services\GenerateFrontEndService;

use Livewire\Component;
use Livewire\WithPagination;

class PageList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

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

        session()->flash('deletePage', 'Page deleted successfully!');
    }

    public function render(){
        $user = auth()->user();

        switch ($user->role) {
            case 'manager':
                return view('livewire.component.cms.page-list')->layout('manager.layouts.app');
                break;
            case 'creator':
                return view('livewire.component.cms.page-list')->layout('creator.layouts.app');
                break;
            case 'moderator':
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
            case 'agent':
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}

<?php

namespace App\Http\Livewire\Backend\Manager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

use Livewire\Component;

class ManagerCms extends Component
{
    public $pages;
    public $url;

    public function mount(){
        $builderPrefix = Config::get('lg.builder_prefix');

        $currentUrl = Request::root().'/';

        $this->url = $currentUrl .= $builderPrefix ? $builderPrefix.'/front-end-builder' : '/front-end-builder';
   
        $endpoint = $this->url.'/all-pages';

        $request = Request::create($endpoint, 'GET');
        $response = Route::dispatch($request);
        $this->pages = json_decode($response->getContent(), true);
    }

    public function render(){
        return view('livewire.backend.manager.managercms')->layout('manager.layouts.app');
    }
}

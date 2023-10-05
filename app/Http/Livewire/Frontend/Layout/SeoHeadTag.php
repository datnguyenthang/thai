<?php

namespace App\Http\Livewire\Frontend\Layout;

use Livewire\Component;
use App\Models\Setting;

class SeoHeadTag extends Component
{
    public $headTagSeo;
    public $bodyTagSeo;

    public function mount(){

        $headTagSeo = Setting::where('name', HEAD_TAG_SEO)->first();
        $this->headTagSeo = $headTagSeo->value ?? '';

        $bodyTagSeo = Setting::where('name', BODY_TAG_SEO)->first();
        $this->bodyTagSeo = $bodyTagSeo->value ?? '';
    }

    public function render(){
        return view('livewire.frontend.layout.seo-head-tag');
    }
}

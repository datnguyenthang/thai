<?php

namespace App\Http\Livewire\Component\Seo;

use Livewire\Component;
use App\Models\Setting;

class SeoSetting extends Component
{
    public $headTagSeo;
    public $bodyTagSeo;

    public function mount(){
        $headTagSeo = Setting::where('name', HEAD_TAG_SEO)->first();
        $this->headTagSeo = $headTagSeo->value ?? '';

        $bodyTagSeo = Setting::where('name', BODY_TAG_SEO)->first();
        $this->bodyTagSeo = $bodyTagSeo->value ?? '';
    }

    public function save() {

        $headTagSeo = Setting::where('name', HEAD_TAG_SEO)->first();
        if (empty($headTagSeo)) {
            Setting::create([
                'name' => HEAD_TAG_SEO,
                'value' => $this->headTagSeo,
            ]);
        } else {
            $headTagSeo->value = $this->headTagSeo;
            $headTagSeo->save();
        }

        $bodyTagSeo = Setting::where('name', BODY_TAG_SEO)->first();
        if (empty($bodyTagSeo)) {
            Setting::create([
                'name' => BODY_TAG_SEO,
                'value' => $this->bodyTagSeo,
            ]);
        } else {
            $bodyTagSeo->value = $this->bodyTagSeo;
            $bodyTagSeo->save();
        }

        session()->flash('success', 'Setting updated successfully!');
        return redirect()->route('settingSeo');
    }

    public function render(){
        $user = auth()->user();

        switch ($user->role) {
            case 'admin':
                return view('livewire.component.seo.seo-setting')->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.seo.seo-setting')->layout('manager.layouts.app');
                break;
            case 'creator':
                return view('livewire.component.seo.seo-setting')->layout('creator.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}

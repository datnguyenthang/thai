<?php

namespace App\Http\Livewire\Component\Cms;

use MSA\LaravelGrapes\Models\Page;
use MSA\LaravelGrapes\Services\GenerateFrontEndService;

use Livewire\Component;

class CreatePage extends Component
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
            'slug' => [
                'required',
                'unique:pages,slug,' . $this->pageId,
                'regex:/^[a-zA-Z0-9-]+$/',
                'not_in:reserved-slugs', // Replace with actual reserved slugs if needed
            ],
        ], [
            'slug.regex' => 'The slug must only contain letters, numbers, and hyphens.',
            'slug.not_in' => 'The slug is reserved and cannot be used.',
        ]);

        $generate_frontend_service = new GenerateFrontEndService();

        if ($this->pageId > 0){

            $page = Page::findOrFail($this->pageId );

            $old_slug = $page->slug;
            $page->update(['name' => $this->name, 'slug' => $this->slug]);
            $generate_frontend_service->updateRouteName($old_slug, $page->slug);

            session()->flash('success', 'Page updated successfully!');
            
        } else {

            $html_string = '{"gjs-html":"<br/><br/><div id=\"front_tabs\"><div class=\"container-fluid\"><div class=\"tabs_wrapper tabs1_wrapper\"><div class=\"tabs tabs1 ui-tabs ui-widget ui-widget-content ui-corner-all\"><div class=\"tabs_tabs tabs1_tabs\"><ul role=\"tablist\" class=\"ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all\"><li role=\"tab\" tabindex=\"0\" aria-controls=\"tabs-1\" aria-labelledby=\"ui-id-1\" aria-selected=\"true\" aria-expanded=\"true\" class=\"active flights ui-state-default ui-corner-top ui-tabs-active ui-state-active\"><a href=\"#tabs-1\" role=\"presentation\" tabindex=\"-1\" id=\"ui-id-1\" class=\"ui-tabs-anchor\">Booking trip<\/a><\/li><\/ul><\/div><div class=\"tabs_content tabs1_content\"><div id=\"tabs-1\" aria-labelledby=\"ui-id-1\" role=\"tabpanel\" aria-hidden=\"false\" class=\"ui-tabs-panel ui-widget-content ui-corner-bottom\"><form action=\"\" class=\"form1\"><div class=\"row\"><div class=\"col-lg-2 col-sm-2\"><div class=\"rdio rdio-primary\"><input type=\"radio\" id=\"roundtrip\" name=\"tripType\"\/><label for=\"roundtrip\">Round trip<\/label><\/div><\/div><div class=\"col-lg-2 col-sm-2\"><div class=\"rdio rdio-primary\"><input type=\"radio\" id=\"one-way\" name=\"tripType\"\/><label for=\"one-way\">One way<\/label><\/div><\/div><\/div><div class=\"row\"><div class=\"col-lg-2 col-sm-12\"><div class=\"select1_wrapper\"><label>Departure<\/label><div class=\"select1_inner\"><select id=\"fromLocation\" name=\"fromLocation\" required placeholder=\"pick up\" class=\"form-control\"><option value=\"\"><\/option><\/select><\/div><\/div><\/div><div class=\"col-lg-2 col-sm-12\"><div class=\"select1_wrapper\"><label>Destination<\/label><div class=\"select1_inner\"><select id=\"toLocation\" name=\"toLocation\" required placeholder=\"drop off\" class=\"form-control\"><option value=\"\"><\/option><\/select><\/div><\/div><\/div><div class=\"col-lg-2 col-sm-12\"><div class=\"input1_wrapper\"><label>Departure date<\/label><div><input id=\"departureDate\" name=\"departureDate\" type=\"date\" required class=\"form-control\"\/><\/div><\/div><\/div><div class=\"col-lg-2 col-sm-12\"><div class=\"input1_wrapper\"><label>Returndate<\/label><div><input id=\"returnDate\" name=\"returnDate\" type=\"date\" required class=\"form-control\"\/><\/div><\/div><\/div><div class=\"col-lg-2 col-sm-12\"><div class=\"select1_wrapper\"><label>Adults<\/label><div class=\"input-group\"><span class=\"input-group-btn\"><button role=\"button\" type=\"button\" class=\"btn bg_own_color btn-number\"><i class=\"fas fa-minus\"><\/i><\/button><\/span><input type=\"text\" id=\"adults\" name=\"adults\" min=\"1\" max=\"100\" required class=\"form-control input-number text-center\"\/><span class=\"input-group-btn\"><button role=\"button\" type=\"button\" class=\"btn bg_own_color btn-number\"><i class=\"fas fa-plus\"><\/i><\/button><\/span><\/div><\/div><\/div><div class=\"col-lg-2 col-sm-12\"><div class=\"select1_wrapper\"><label>Children<\/label><div class=\"input-group\"><span class=\"input-group-btn\"><button role=\"button\" type=\"button\" class=\"btn bg_own_color btn-number\"><i class=\"fas fa-minus\"><\/i><\/button><\/span><input type=\"text\" id=\"children\" name=\"children\" min=\"1\" max=\"100\" required class=\"form-control input-number text-center\"\/><span class=\"input-group-btn\"><button role=\"button\" type=\"button\" class=\"btn bg_own_color btn-number\"><i class=\"fas fa-plus\"><\/i><\/button><\/span><\/div><\/div><\/div><\/div><div class=\"row\"><div class=\"col-lg-12 col-sm-12\"><div class=\"button1_wrapper\"><button type=\"submit\" class=\"btn-default btn-form1-submit\">Search<\/button><\/div><\/div><\/div><\/form><\/div><\/div><\/div><\/div><\/div><\/div>","gjs-components":"[{\"attributes\":{\"id\":\"front_tabs\"},\"components\":[{\"type\":\"container\",\"classes\":[\"container-fluid\"],\"components\":[{\"classes\":[\"tabs_wrapper\",\"tabs1_wrapper\"],\"components\":[{\"type\":\"tabs\",\"classes\":[\"tabs\",\"tabs1\",\"ui-tabs\",\"ui-widget\",\"ui-widget-content\",\"ui-corner-all\"],\"components\":[{\"classes\":[\"tabs_tabs\",\"tabs1_tabs\"],\"components\":[{\"tagName\":\"ul\",\"classes\":[\"ui-tabs-nav\",\"ui-helper-reset\",\"ui-helper-clearfix\",\"ui-widget-header\",\"ui-corner-all\"],\"attributes\":{\"role\":\"tablist\"},\"components\":[{\"tagName\":\"li\",\"classes\":[\"active\",\"flights\",\"ui-state-default\",\"ui-corner-top\",\"ui-tabs-active\",\"ui-state-active\"],\"attributes\":{\"role\":\"tab\",\"tabindex\":\"0\",\"aria-controls\":\"tabs-1\",\"aria-labelledby\":\"ui-id-1\",\"aria-selected\":\"true\",\"aria-expanded\":\"true\"},\"components\":[{\"type\":\"link\",\"classes\":[\"ui-tabs-anchor\"],\"attributes\":{\"href\":\"#tabs-1\",\"role\":\"presentation\",\"tabindex\":\"-1\",\"id\":\"ui-id-1\"},\"components\":[{\"type\":\"textnode\",\"content\":\"Booking trip\"}]}]}]}]},{\"classes\":[\"tabs_content\",\"tabs1_content\"],\"components\":[{\"classes\":[\"ui-tabs-panel\",\"ui-widget-content\",\"ui-corner-bottom\"],\"attributes\":{\"id\":\"tabs-1\",\"aria-labelledby\":\"ui-id-1\",\"role\":\"tabpanel\",\"aria-hidden\":\"false\"},\"components\":[{\"tagName\":\"form\",\"type\":\"form\",\"classes\":[\"form1\"],\"attributes\":{\"action\":\"\"},\"components\":[{\"type\":\"row\",\"classes\":[\"row\"],\"components\":[{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-2\"],\"components\":[{\"classes\":[\"rdio\",\"rdio-primary\"],\"components\":[{\"type\":\"radio\",\"void\":true,\"attributes\":{\"type\":\"radio\",\"id\":\"roundtrip\",\"name\":\"tripType\"}},{\"type\":\"label\",\"attributes\":{\"for\":\"roundtrip\"},\"components\":[{\"type\":\"textnode\",\"content\":\"Round trip\"}]}]}]},{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-2\"],\"components\":[{\"classes\":[\"rdio\",\"rdio-primary\"],\"components\":[{\"type\":\"radio\",\"void\":true,\"attributes\":{\"type\":\"radio\",\"id\":\"one-way\",\"name\":\"tripType\"}},{\"type\":\"label\",\"attributes\":{\"for\":\"one-way\"},\"components\":[{\"type\":\"textnode\",\"content\":\"One way\"}]}]}]}]},{\"type\":\"row\",\"classes\":[\"row\"],\"components\":[{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-12\"],\"components\":[{\"classes\":[\"select1_wrapper\"],\"components\":[{\"type\":\"label\",\"components\":[{\"type\":\"textnode\",\"content\":\"Departure\"}]},{\"classes\":[\"select1_inner\"],\"components\":[{\"type\":\"select\",\"classes\":[\"form-control\"],\"attributes\":{\"id\":\"fromLocation\",\"name\":\"fromLocation\",\"required\":true,\"placeholder\":\"pick up\"},\"components\":[{\"tagName\":\"option\",\"attributes\":{\"value\":\"\"}}]}]}]}]},{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-12\"],\"components\":[{\"classes\":[\"select1_wrapper\"],\"components\":[{\"type\":\"label\",\"components\":[{\"type\":\"textnode\",\"content\":\"Destination\"}]},{\"classes\":[\"select1_inner\"],\"components\":[{\"type\":\"select\",\"classes\":[\"form-control\"],\"attributes\":{\"id\":\"toLocation\",\"name\":\"toLocation\",\"required\":true,\"placeholder\":\"drop off\"},\"components\":[{\"tagName\":\"option\",\"attributes\":{\"value\":\"\"}}]}]}]}]},{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-12\"],\"components\":[{\"classes\":[\"input1_wrapper\"],\"components\":[{\"type\":\"label\",\"components\":[{\"type\":\"textnode\",\"content\":\"Departure date\"}]},{\"components\":[{\"type\":\"input\",\"void\":true,\"classes\":[\"form-control\"],\"attributes\":{\"id\":\"departureDate\",\"name\":\"departureDate\",\"type\":\"date\",\"required\":true}}]}]}]},{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-12\"],\"components\":[{\"classes\":[\"input1_wrapper\"],\"components\":[{\"type\":\"label\",\"components\":[{\"type\":\"textnode\",\"content\":\"Returndate\"}]},{\"components\":[{\"type\":\"input\",\"void\":true,\"classes\":[\"form-control\"],\"attributes\":{\"id\":\"returnDate\",\"name\":\"returnDate\",\"type\":\"date\",\"required\":true}}]}]}]},{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-12\"],\"components\":[{\"classes\":[\"select1_wrapper\"],\"components\":[{\"type\":\"label\",\"components\":[{\"type\":\"textnode\",\"content\":\"Adults\"}]},{\"classes\":[\"input-group\"],\"components\":[{\"tagName\":\"span\",\"classes\":[\"input-group-btn\"],\"components\":[{\"tagName\":\"button\",\"type\":\"button\",\"classes\":[\"btn\",\"bg_own_color\",\"btn-number\"],\"attributes\":{\"role\":\"button\",\"type\":\"button\"},\"components\":[{\"tagName\":\"i\",\"classes\":[\"fas\",\"fa-minus\"]}]}]},{\"type\":\"input\",\"void\":true,\"classes\":[\"form-control\",\"input-number\",\"text-center\"],\"attributes\":{\"type\":\"text\",\"id\":\"adults\",\"name\":\"adults\",\"min\":\"1\",\"max\":\"100\",\"required\":true}},{\"tagName\":\"span\",\"classes\":[\"input-group-btn\"],\"components\":[{\"tagName\":\"button\",\"type\":\"button\",\"classes\":[\"btn\",\"bg_own_color\",\"btn-number\"],\"attributes\":{\"role\":\"button\",\"type\":\"button\"},\"components\":[{\"tagName\":\"i\",\"classes\":[\"fas\",\"fa-plus\"]}]}]}]}]}]},{\"type\":\"column\",\"classes\":[\"col-lg-2\",\"col-sm-12\"],\"components\":[{\"classes\":[\"select1_wrapper\"],\"components\":[{\"type\":\"label\",\"components\":[{\"type\":\"textnode\",\"content\":\"Children\"}]},{\"classes\":[\"input-group\"],\"components\":[{\"tagName\":\"span\",\"classes\":[\"input-group-btn\"],\"components\":[{\"tagName\":\"button\",\"type\":\"button\",\"classes\":[\"btn\",\"bg_own_color\",\"btn-number\"],\"attributes\":{\"role\":\"button\",\"type\":\"button\"},\"components\":[{\"tagName\":\"i\",\"classes\":[\"fas\",\"fa-minus\"]}]}]},{\"type\":\"input\",\"void\":true,\"classes\":[\"form-control\",\"input-number\",\"text-center\"],\"attributes\":{\"type\":\"text\",\"id\":\"children\",\"name\":\"children\",\"min\":\"1\",\"max\":\"100\",\"required\":true}},{\"tagName\":\"span\",\"classes\":[\"input-group-btn\"],\"components\":[{\"tagName\":\"button\",\"type\":\"button\",\"classes\":[\"btn\",\"bg_own_color\",\"btn-number\"],\"attributes\":{\"role\":\"button\",\"type\":\"button\"},\"components\":[{\"tagName\":\"i\",\"classes\":[\"fas\",\"fa-plus\"]}]}]}]}]}]}]},{\"type\":\"row\",\"classes\":[\"row\"],\"components\":[{\"type\":\"column\",\"classes\":[\"col-lg-1\",\"col-sm-12\"],\"components\":[{\"classes\":[\"button1_wrapper\"],\"components\":[{\"tagName\":\"button\",\"type\":\"text\",\"classes\":[\"btn-default\",\"btn-form1-submit\"],\"attributes\":{\"type\":\"submit\"},\"components\":[{\"type\":\"textnode\",\"content\":\"Search\"}]}]}]}]}]}]}]}]}]}]}]}]","gjs-assets":"[]","gjs-css":"* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin:0;}*{box-sizing:border-box;}body{margin:0;}html{font-size:14px;}.card-deck .card{min-width:220px;}#tabs-1{display:block;}@media (min-width: 768px){html{font-size:16px;}}","gjs-styles":"[{\"selectors\":[],\"selectorsAdd\":\"*\",\"style\":{\"box-sizing\":\"border-box\"}},{\"selectors\":[],\"selectorsAdd\":\"body\",\"style\":{\"margin\":\"0\"}},{\"selectors\":[],\"selectorsAdd\":\"*\",\"style\":{\"box-sizing\":\"border-box\"}},{\"selectors\":[],\"selectorsAdd\":\"body\",\"style\":{\"margin\":\"0\"}},{\"selectors\":[],\"selectorsAdd\":\"html\",\"style\":{\"font-size\":\"14px\"}},{\"selectors\":[\"container\"],\"style\":{\"max-width\":\"960px\"}},{\"selectors\":[\"pricing-header\"],\"style\":{\"max-width\":\"700px\"}},{\"selectors\":[],\"selectorsAdd\":\".card-deck .card\",\"style\":{\"min-width\":\"220px\"}},{\"selectors\":[],\"selectorsAdd\":\"html\",\"style\":{\"font-size\":\"16px\"},\"mediaText\":\"(min-width: 768px)\",\"atRuleType\":\"media\"},{\"selectors\":[\"#tabs-1\"],\"style\":{\"display\":\"block\"}}]"}';


            $page = Page::create(['name' => $this->name, 'slug' => $this->slug, 'page_data' => $html_string]);

            session()->flash('success', 'Page created successfully!');
        }
        // Reset input fields

        return redirect()->route('pageList');
    }

    public function render() {

        $user = auth()->user();

        switch ($user->role) {
            case 'admin':
                return view('livewire.component.cms.create-page')->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.cms.create-page')->layout('manager.layouts.app');
                break;
            case 'creator':
                return view('livewire.component.cms.create-page')->layout('creator.layouts.app');
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

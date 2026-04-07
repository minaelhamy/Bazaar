<?php

namespace App\Http\Controllers\addons\included;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $getlanguages = Languages::get();
        if ($request->code == "") {
            foreach ($getlanguages as $firstlang) {
                $currantLang = Languages::where('code', $firstlang->code)->first();
                break;
            }
        } else {
            $currantLang = Languages::where('code', $request->code)->first();
        }
        if ($request->has('lang')) {
            if ($request->lang != "" && $request->lang != null) {
                $settingdata = Settings::where('vendor_id', 1)->first();
                $settingdata->default_language = $request->lang;
                $settingdata->update();
            }
        }
        if (empty($currantLang)) {
            $dir = base_path() . '/resources/lang/' . 'en';
        } else {
            $dir = base_path() . '/resources/lang/' . $currantLang->code;
        }
        if (!is_dir($dir)) {
            $dir = base_path() . '/resources/lang/en';
        }
        $arrLabel   = json_decode(file_get_contents($dir . '/' . 'labels.json'));
        $arrMessage   = json_decode(file_get_contents($dir . '/' . 'messages.json'));
        $arrLanding   = json_decode(file_get_contents($dir . '/' . 'landing.json'));
        return view('admin.included.language.index', compact('getlanguages', 'currantLang', 'arrLabel', 'arrMessage', 'arrLanding'));
    }
    public function add()
    {
        return view('admin.included.language.add');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'layout' => 'required',
            'name' => 'required_with:code',
            'image.*' => 'mimes:jpeg,png,jpg',
        ], [
            "code.required" => trans('messages.language_required'),
            "layout.required" => trans('messages.layout_required'),
            "name.required_with" => trans('messages.wrong'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $path = base_path('resources/lang/' . $request->code);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            File::copyDirectory(base_path() . '/resources/lang/en', base_path() . '/resources/lang/' . $request->code);
            $language = new Languages();
            $language->code = $request->code;
            $language->name = $request->name;
            $language->layout = $request->layout;
            if ($request->has('image')) {
                $flagimage = 'flag-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(storage_path('app/public/admin-assets/images/language/'), $flagimage);
                $language->image = $flagimage;
            }
            $language->is_available = 1;
            $language->save();
            return redirect('admin/language-settings')->with('success', trans('messages.success'));
        }
    }

    public function storeLanguageData(Request $request)
    {
        $langFolder = base_path() . '/resources/lang/' . $request->currantLang;
        if (!is_dir($langFolder)) {
            mkdir($langFolder);
            chmod($langFolder, 0777);
        }
        if (isset($request->file) == "label") {
            if (isset($request->label) && !empty($request->label)) {
                $content = "<?php return [";
                $contentjson = "{";
                foreach ($request->label as $key => $data) {
                    $content .= '"' . $key . '" => "' . str_replace('\\', '', addslashes($data)) . '",';
                    $contentjson .= '"' . $key . '":"' . $data . '",';
                }
                $content .= "];";
                $contentjson .= "}";

                file_put_contents($langFolder . "/labels.php", $content);
                file_put_contents($langFolder . "/labels.json", str_replace(",}", "}", $contentjson));
            }
        }
        if (isset($request->file) == "message") {
            if (isset($request->message) && !empty($request->message)) {
                $content = "<?php return [";
                $contentjson = "{";
                foreach ($request->message as $key => $data) {
                    $content .= '"' . $key . '" => "' . str_replace('\\', '', addslashes($data)) . '",';
                    $contentjson .= '"' . $key . '":"' . $data . '",';
                }
                $content .= "];";
                $contentjson .= "}";
                file_put_contents($langFolder . "/messages.php", $content);
                file_put_contents($langFolder . "/messages.json", str_replace(",}", "}", $contentjson));
            }
        }
        if (isset($request->file) == "landing") {

            if (isset($request->landing) && !empty($request->landing)) {
                $content = "<?php return [";
                $contentjson = "{";
                foreach ($request->landing as $key => $data) {
                    $content .= '"' . $key . '" => "' . str_replace('\\', '', addslashes($data)) . '",';
                    $contentjson .= '"' . $key . '" : "' . $data . '",';
                }
                $content .= "];";
                $contentjson .= "}";
                file_put_contents($langFolder . "/landing.php", $content);
                file_put_contents($langFolder . "/landing.json", str_replace(",}", "}", $contentjson));
            }
        }

        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function delete(Request $request)
    {
        try {
            $language = Languages::find($request->id);
            $getdefault = Settings::get();
            $setactive = Languages::where('code', 'en')->first();
            $setactive->is_available = 1;
            $setactive->update();
            foreach ($getdefault as $default) {
                $code = explode('|', $default->languages);
                $key = array_search($language->code, $code);
                if ($key !== false) {
                    unset($code[$key]);
                }
                Settings::where('vendor_id', $default->vendor_id)->update(array('languages' => implode('|', $code)));
                Settings::where('default_language', $language->code)->update(array('default_language' => "en"));
            }
            $path = base_path('resources/lang/' . $language->code);
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }
            if (file_exists(env('ASSETPATHURL') . 'admin-assets/images/language/' . $language->image)) {
                unlink(env('ASSETPATHURL') . 'admin-assets/images/language/' . $language->image);
            }
            $language->delete();
            return redirect('admin/language-settings')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect('admin/language-settings')->with('error', trans('messages.wrong'));
        }
    }
    public function edit($id)
    {
        $getlanguage = Languages::where('id', $id)->first();
        return view('admin.included.language.edit', compact('getlanguage'));
    }
    public function update(Request $request, $id)
    {
        try {
            $default = 2;
            if ($request->default == 1) {
                Languages::where('is_default', '1')->update(array('is_default' => 2));
                $default = $request->default;
            }
            $language = Languages::where('id', $id)->first();
            $language->layout = $request->layout;
            $language->is_default = @$default;
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
                ], [
                    'image.max' => trans('messages.image_size_message'),
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
                }
                if (file_exists(env('ASSETPATHURL') . 'admin-assets/images/language/' . $language->image)) {
                    unlink(env('ASSETPATHURL') . 'admin-assets/images/language/' . $language->image);
                }
                $flagimage = 'flag-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(storage_path('app/public/admin-assets/images/language/'), $flagimage);
                $language->image = $flagimage;
            }
            $language->update();
            return redirect('admin/language-settings')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect('admin/language-settings')->with('error', trans('messages.wrong'));
        }
    }

    public function status(Request $request)
    {

        $language = Languages::find($request->id);
        if ($language->code == helper::appdata('')->default_language) {
            return redirect()->back()->with('error', trans('messages.remove_default'));
        }
        $language->is_available = $request->status;
        $language->save();
        if ($language) {
            return redirect()->back()->with('success', trans('messages.success'));
        } else {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
    public function languagestatus(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $settingdata = Settings::where('vendor_id', $vendor_id)->first();
        if ($request->status == 2) {
            $code = explode('|', helper::appdata($vendor_id)->languages);
            if (count($code) == 1) {
                return redirect()->back()->with('error', trans('messages.language_required_msg'));
            }
            if ($request->code == helper::appdata($vendor_id)->default_language) {
                return redirect()->back()->with('error', trans('messages.remove_default'));
            }
            $key = array_search($request->code, $code);
            if ($key !== false) {
                unset($code[$key]);
                $settingdata->languages = implode('|', $code);
            }
        }
        if ($request->status == 1) {
            if (helper::appdata($vendor_id)->languages != "") {
                $code = explode('|', helper::appdata($vendor_id)->languages);
                array_push($code, $request->code);
                $settingdata->languages = implode('|', $code);
            } else {
                $settingdata->languages = $request->code;
            }
        }
        $settingdata->update();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function setdefault(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $settingdata = Settings::where('vendor_id', $vendor_id)->first();
        if (in_array($request->code, explode('|', $settingdata->languages))) {
            $settingdata->default_language = $request->code;
            $settingdata->update();
            return redirect()->back()->with('success', trans('messages.success'));
        } else {
            return redirect()->back()->with('error', trans('messages.not_available'));
        }
    }

    public function change(Request $request)
    {
        $layout = Languages::select('name', 'layout', 'image')->where('code', $request->lang)->first();
        App::setLocale($request->lang);
        session()->put('locale', $request->lang);
        session()->put('language', $layout->name);
        session()->put('flag', $layout->image);
        session()->put('direction', $layout->layout);
        return redirect()->back();
    }
}

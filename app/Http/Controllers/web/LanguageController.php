<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $language = $request->input('language');

        // Set the selected language in the session
        Session::put('locale', $language);

        App::setLocale($language);

        return redirect()->back();
    }
}


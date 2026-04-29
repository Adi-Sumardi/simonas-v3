<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function reloadCaptcha()
    {
        return response()->json(['captcha'=>captcha_img('flat')]);
    }

    public function reloadCaptchaLogin()
    {
        return response()->json(['captcha'=>captcha_img('math')]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketingMTController extends Controller
{
    public function popcronPd()
    {
        return view('marketing_page.popcron_pd_page');
    }

    public function popcronPdKakao()
    {
        return view('marketing_page.popcron_pd');
    }

    // 팝콘 수학 소개 페이지
    public function popcronPdMath()
    {
        return view('marketing_page.popcron_pd_page_math');
    }

    public function popcronPdHanja()
    {
        return view('marketing_page.popcron_pd_page_hanja');
    }   

    public function popcronPdEnglish()
    {
        return view('marketing_page.popcron_pd_page_en');
    }
}

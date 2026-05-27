<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.homepage-main');
    }

    public function integrated()
    {
        $objectsArray = [
        (object)[
            'showName' => 'Diameter',
            'nameId' => 'diameter',
            'messagePopUp' => 'Diameter',
            'iconName' => 'location-pin',
            'iconColor' => '#F49D1A'
        ],
        (object)[
            'showName' => 'SIP',
            'nameId' => 'sip',
            'messagePopUp' => 'SIP',
            'iconName' => 'phone',
            'iconColor' => '#344D67'
        ],
        (object)[
            'showName' => 'SS7 MAP',
            'nameId' => 'ss7map',
            'messagePopUp' => 'SS7 MAP',
            'iconName' => 'tag',
            'iconColor' => '#EB6440'
        ],
        (object)[
            'showName' => 'SS7 CAP',
            'nameId' => 'ss7cap',
            'messagePopUp' => 'SS7 CAP',
            'iconName' => 'chart-pie',
            'iconColor' => '#42855B'
        ],
        (object)[
            'showName' => 'GTP',
            'nameId' => 'gtp',
            'messagePopUp' => 'GTP',
            'iconName' => 'speedometer',
            'iconColor' => '#e55353'
        ],
        (object)[
            'showName' => 'SMPP',
            'nameId' => 'smpp',
            'messagePopUp' => 'SMPP',
            'iconName' => 'speech',
            'iconColor' => '#2eb85c'
        ],
        (object)[
            'showName' => 'HTTP',
            'nameId' => 'http',
            'messagePopUp' => 'HTTP',
            'iconName' => 'globe-alt',
            'iconColor' => '#B01E68'
        ],
        (object)[
            'showName' => 'HTTP-OCS',
            'nameId' => 'http-ocs',
            'messagePopUp' => 'HTTP-OCS',
            'iconName' => 'globe-alt',
            'iconColor' => '#1e84b0'
        ],
        (object)[
            'showName' => 'HTTP-SS7',
            'nameId' => 'http-ss7',
            'messagePopUp' => 'HTTP-SS7',
            'iconName' => 'globe-alt',
            'iconColor' => '#651eb0'
        ],
        ];

        

        return view('dashboard.integrated-diagram',['objectsArray'=>$objectsArray],);
    }

    public function charts()
    {
        return view('dashboard.charts');
    }

}

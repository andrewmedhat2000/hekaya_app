<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Report;

class ReportController extends Controller
{
    protected $mainUrl = 'dashboard.report.';
    protected $exceptionUrl = 'home';


    public function index(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'streamer_admin');
        });

        $data['reports'] = Report::query()->with(['reporter:id,name', 'reported:id,name'])->paginate(10);
        return view($this->mainUrl . 'index', $data);
    }

}

<?php

namespace App\Exports;

use App\Models\CurrencyWorth;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StreamersExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function view(): View
    {
        $diamonds_per_dollar = ($ratio = CurrencyWorth::first()) ? $ratio->diamond / $ratio->price : 1;

        return view('dashboard.streameradminreporting.partials.streamers_table', [
            'users' => $this->users,
            'diamonds_per_dollar' => $diamonds_per_dollar,
            'links' => false,
        ]);
    }

    // public function collection()
    // {
    //     //returns Data with User data, all user data, not restricted to start/end dates
    //     // return User::whereHas('roles', function ($query) {
    //     //     $query->where('name', 'streamer');
    //     // })->get();
    //     return $this->users;
    // }

    // public function map($user): array
    // {
    //     return [
    //         $user->id,
    //         $user->user_name,
    //         $user->name,
    //         $user->email,
    //         $user->phone,
    //         optional($user->currentStreamerAdminsPivot)->streamerAdmin->name,
    //         $user->getMonthlyAdminCommission(),
    //         $user->getMonthlyHours(),
    //         $user->getMonthlyLevel(),
    //         $user->getMonthlyGolds(),
    //         $user->getMonthlyBasicSalary(),
    //         $user->getMonthlyMoney(),

    //     ];
    // }

    // public function headings(): array
    // {
    //     return [
    //         '#id',
    //         'User Name',
    //         'Name',
    //         'Email',
    //         'Phone',
    //         'Agent',
    //         'Agent Commission',
    //         'Hours',
    //         'Level',
    //         'Diamonds Gained',
    //         'Basic Salary',
    //         'Income worth',
    //     ];
    // }
}

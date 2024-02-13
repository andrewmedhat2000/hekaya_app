<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StreamerResource;
use App\Models\User;
use App\Models\UserStreamer;
use App\Models\UserStreamerInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StreamerController extends BaseController
{
    public function AgentReport(Request $request)
    {
        try {

            $streamer_pivots = UserStreamer::where('streamer_admin_id', auth()->id())
            ->whereBetween('created_at', [Carbon::now()->subMonth(2), Carbon::now()])
            ->get();

            $first_day_of_month = config('streamers.first_day_of_month', 1);

            $months = UserStreamerInfo::whereIn('user_streamer_id', $streamer_pivots->pluck('id'))
            ->selectRaw("date(created_at) AS date")->distinct('date')->get()
            ->map(function($info) use($first_day_of_month) {
                return Carbon::parse($info->date)->addDays(1 - $first_day_of_month)->format('Y-m');
            })->unique()->values()->toArray();

            $date = $request->filled('month')
                ? Carbon::parse($request->month)->addDays($first_day_of_month - 1)
                : today();

            $start_of_month = $date->day >= $first_day_of_month
                ? $date->setDay($first_day_of_month)
                : $date->addMonths(-1)->setDay($first_day_of_month);

            $info = UserStreamerInfo::whereIn('user_streamer_id', $streamer_pivots->pluck('id'))
                ->selectRaw('user_streamer_id, DATE(created_at) as date, SUM(mins) as mins, SUM(gold) as gold')
                ->groupBy('user_streamer_id', 'date')
                ->having('date', '>=', $start_of_month)->having('date', '<', $start_of_month->clone()->addMonth())
                ->with('streamer.streamer', 'streamer.streamerAdmin')
                ->get();

            $users = $info->groupBy('user_streamer_id')->map(function($group) {
                $streamer = $group->first()->streamer;
                $user = clone $streamer->streamer;
                $streamer->setRelation('streamerInfoThisMonth', $group);
                return $user->setRelation('currentStreamerAdminsPivot', $streamer);
            })->sortByDesc(function($user) {
                return $user->getMonthlyGolds();
            });

            $totals = [
                'salaries' => round($users->sum->getMonthlyMoney(), 2),
                'commissions' => round($users->sum->getMonthlyAdminCommission(), 2),
            ];

            return $this->apiResponse([
                'months' => $months,
                'current_month' => $start_of_month->format('Y-m'),
                'users' => StreamerResource::collection($users),
                'totals' => $totals,
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

    public function getStreamerProgress()
    {
        try {

            /** @var User $user */
            $user = auth()->user();

            $streamer_pivot = UserStreamer::where('streamer_id', $user->id)
                ->where('ends_at', '>', today()->addDays(-3)->toDateString())
                ->latest()->first();

            if (!$streamer_pivot) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'لا يوجد وكالة',
                ], 409);
            }

            $user->setRelation('currentStreamerAdminsPivot', $streamer_pivot);

            return $this->apiResponse([
                'user' => new StreamerResource($user),
            ]);

        } catch (\Exception $e) {
            return $this->internalServerError($e);
        }
    }

}

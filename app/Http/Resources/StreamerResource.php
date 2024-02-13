<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StreamerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $minutes = $this->getMonthlyMinutes();

        return [
            'id'                        => $this->id,
            'profile_pic_url'           => $this->profile_pic_url,
            'name'                      => $this->name,
            'diamonds'                  => $this->getMonthlyGolds(),
            'level'                     => $this->getMonthlyLevel(),
            'progress'                  => $this->getTargetProgress(),
            'basic_salary'              => $this->getMonthlyBasicSalary(),
            'days'                      => $this->getMonthlyDaysCount(),
            'days_target_completed'     => $this->daysTargetCompleted(),
            'hours'                     => floor($minutes/60) .'h'. $minutes%60 .'m',
            'hours_target_completed'    => $this->hoursTargetCompleted(),
            'reward'                    => $this->getHoursReward(),
            'salary'                    => $this->getMonthlyMoney(),
            'commission'                => $this->getMonthlyAdminCommission(),
            'agent'                     => $this->whenLoaded('currentStreamerAdminsPivot',
                fn() => new UserResource($this->currentStreamerAdminsPivot->streamerAdmin)
            ),
            'valid_until'               => $this->whenLoaded('currentStreamerAdminsPivot',
                fn() => $this->currentStreamerAdminsPivot->ends_at,
            ),
        ];
    }
}

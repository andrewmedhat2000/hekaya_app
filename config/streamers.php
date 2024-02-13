<?php

return [
    /** the day at which the progress of the streamers resets */
    'first_day_of_month' => 1,

    /** precent of hours_price earned if the streamer hasn't achieved the hours monthly target */
    'target_hours_factor' => [
        '0.4' => 0.33,
        '0.7' => 0.66,
    ],

    /** dollars deducted from reward with each day less than the required days for the target */
    'deduction_per_day' => 3,

    /** percent of gift diamonds added to a streamer's points when other people in the room send gifts */
    'host_bonus_percent' => 0.09,
];

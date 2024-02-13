<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserStreamer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class StreamerImport implements ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.0' => 'nullable|exists:users,id',
        ])->validate();

        $users = User::whereIn('id', $rows->map(fn($row) => $row[0])->filter())->get();
        // UserStreamer::whereIn('streamer_id', $users->pluck('id'))->delete();

        foreach($rows->filter(fn($row) => !empty($row[0])) as $row) {
            $user = $users->find($row[0]);
            $user->syncRoles(['streamer']);

            UserStreamer::create([
                'streamer_id' => $user->id,
                'streamer_admin_id' => request()->input('streamer_admin_id'),
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::paginate(10);
        return view('dashboard.settings.index', compact('settings'));
    }

    public function edit(Settings $setting)
    {
        return view('dashboard.settings.edit', compact('setting'));
    }

    public function update(Request $request, Settings $setting)
    {
        $validated = $request->validate([
            'value' => 'required|integer',
        ]);
        Settings::find($setting->id)->update(['value' => $request->value]);
        return redirect('dashboard/settings');
    }

    public function prohibitedWords()
    {
        $words = DB::table('prohibited_words')->pluck('word', 'word')->toArray();

        return view('dashboard.settings.prohibited-words', compact('words'));
    }

    public function updateProhibitedWords(Request $request)
    {
        $words = DB::table('prohibited_words')->pluck('word', 'id')->toArray();

        $list = $request->list;

        $deletes = array_diff($words, $list);
        DB::table('prohibited_words')->whereIn('id', array_keys($deletes))->delete();

        $inserts = array_map(fn($word) => ['word' => $word], array_diff($list, $words));
        DB::table('prohibited_words')->insert($inserts);

        return redirect()->back();
    }
}

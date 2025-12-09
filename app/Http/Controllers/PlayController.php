<?php
namespace App\Http\Controllers;

use App\Models\Typing;
use App\Models\TypingLevel;
use App\Models\TypingScore;
use Illuminate\Http\Request;
use App\Models\TypingFreestyle;
use App\Models\TypingScoreFreestyle;

class PlayController extends Controller
{
    public function index()
    {
        $rows    = file_get_contents(base_path('public/assets/json/keyboard/rows.json'));
        $rows    = json_decode($rows);
        $actions = file_get_contents(base_path('public/assets/json/keyboard/actions.json'));
        $actions = json_decode($actions);
        $shifts  = file_get_contents(base_path('public/assets/json/keyboard/shifts.json'));
        $shifts  = json_decode($shifts);

        $level = TypingLevel::has('typing')->get();

        $freestyle = TypingFreestyle::all();

        $player      = @\Auth::user()->player;
        $typingScore = TypingScore::where('player_id', @$player->id)
            ->whereDate('created_at', now()->toDateString())
            ->first();
        $typingScore = $typingScore ? $typingScore->score : 0;
        return view('play.index', compact('rows', 'actions', 'shifts', 'level', 'freestyle', 'typingScore'));
    }

    public function getLevel(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
            ]);

            $typings = Typing::where('level_id', $request->id)->orderBy('order', 'asc')->get();

            if (count($typings) <= 0) {
                abort(500, 'Not Found Levels');
            }
            return [
                'status'  => true,
                'message' => 'Success',
                'data'    => $typings,
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }

    public function saveScore(Request $request)
    {
        try {

            $request->validate([
                'score' => 'required',
            ]);

            $user = \Auth::user();
            if (! $user) {
                abort(500, 'You must login to save your score');
            }

            if (! $user->player) {
                abort(500, 'Player not found');
            }

            $typingScore = TypingScore::where('player_id', $user->player->id)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if (! $typingScore) {
                $typingScore            = new TypingScore();
                $typingScore->player_id = $user->player->id;
            }

            $typingScore->score = $request->score;
            $typingScore->save();

            return [
                'status'  => true,
                'message' => 'Score saved successfully',
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => 'Failed',
                'error'   => $th->getMessage(),
            ];
        }
    }

    public function saveScoreFreestyle(Request $request)
    {
        try {

            $request->validate([
                'score' => 'required',
            ]);

            $user = \Auth::user();
            if (! $user) {
                abort(500, 'You must login to save your score');
            }

            if (! $user->player) {
                abort(500, 'Player not found');
            }

            $typingScoreFreestyle            = new TypingScoreFreestyle();
            $typingScoreFreestyle->player_id = $user->player->id;
            $typingScoreFreestyle->time      = now();

            $typingScoreFreestyle->score = $request->score;
            $typingScoreFreestyle->save();

            return [
                'status'  => true,
                'message' => 'Score Freestyle saved successfully',
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => 'Failed',
                'error'   => $th->getMessage(),
            ];
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use Alert;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Country;
use App\Models\GuessGame;
use App\Models\GuessShape;
use App\Models\GuessPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GuessGameResource;
use Illuminate\Support\Facades\Validator;
use App\Events\GuessGames;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GuessGameController extends BaseController
{

    public function getShapesAndPackages()
    {
        try {

            $shapes = GuessShape::select(['id', 'name', 'image'])->get();
            $packages = GuessPackage::select(['id', 'name', 'value'])->get();

            return response()->json([
                'status_code' => 200,
                'shapes' => $shapes,
                'packages' => $packages,
            ], 200);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }



    public function startGame(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'room_id' => 'required|exists:rooms,id',
                'shape_id' => 'required|exists:guess_shapes,id',
                'package_id' => 'required|exists:guess_packages,id',
            ]);
            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first());
            }

            $user = auth()->user();
            if(GuessPackage::find($request->package_id)->value > $user->diamonds){
                return $this->apiErrorResponse('Your diamonds not enough to start the game!');
            }

            DB::transaction(function () use($request, $user) {
                $game = GuessGame::create([
                    'room_id' => $request->room_id,
                    'challenger_id' => $user->id,
                    'challenger_shape_id' => $request->shape_id,
                    'package_id' => $request->package_id,
                ]);

                broadcast(new GuessGames($game));
            }, 3);

            return $this->apiResponse([], 'Game started successfuly!');

        } catch (\Exception $ex) {

            return $this->internalServerError($ex);
        }
    }



    public function acceptGame(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'game_id' => 'required|exists:guess_games,id',
                'shape_id' => 'required|exists:guess_shapes,id',
            ]);
            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first());
            }

            DB::transaction(function () use($request) {

                $challenged = auth()->user();
                $challenged_id = auth()->user()->id;
                $game = GuessGame::sharedLock()->find($request->game_id);


                if($game->challenger_id == $challenged_id){
                    throw new HttpException(409, 'Sorry You cannot play against yourself!');
                }

                if($game->package->value > $challenged->diamonds){
                    throw new HttpException(409, 'Your diamonds not enough to accept the game!');
                }

                if($game->challenged_id != null){
                    throw new HttpException(409, 'The game just have been played with some one else!');
                }

                if($game->package->value > $game->challenger->diamonds){
                    throw new HttpException(409, 'There aren\'t enough diamonds with challenger!');
                }


                $game->load(['challenger', 'challenger_shape', 'challenged', 'challenged_shape'])->first();
                // Calculate result of the game
                $data['challenger_shape'] = $game->challenger_shape->name;
                $data['challenged_shape'] = GuessShape::find($request->shape_id)->name;
                $winner = $this->result($data);
                $diamonds = $game->package->value;

                if($winner == 'challenger'){

                    $winner_id = $game->challenger_id;

                    $challenger = User::find($game->challenger_id);
                    $challenger_diamonds = $challenger->diamonds + $diamonds;
                    $challenger->update(['diamonds' => $challenger_diamonds]);

                    $challenged = User::find($challenged_id);
                    $challenged_diamonds = $challenged->diamonds - $diamonds;
                    $challenged->update(['diamonds' => $challenged_diamonds]);
                }elseif($winner == 'challenged'){

                    $winner_id = $challenged_id;

                    $challenged = User::find($challenged_id);
                    $challenged_diamonds = $challenged->diamonds + $diamonds;
                    $challenged->update(['diamonds' => $challenged_diamonds]);

                    $challenger = User::find($game->challenger_id);
                    $challenger_diamonds = $challenger->diamonds - $diamonds;
                    $challenger->update(['diamonds' => $challenger_diamonds]);
                }elseif($winner == 'same'){
                    $winner_id = 0;
                }

                $game->update([
                    'challenged_id' => $challenged_id,
                    'challenged_shape_id' => $request->shape_id,
                    'winner_id' => $winner_id,
                ]);

                broadcast(new GuessGames($game));
            }, 3);

            return $this->apiResponse([], 'Game played successfuly!');

        } catch(HttpException $e){
            return $this->apiErrorResponse($e->getMessage(), $e->getStatusCode());
        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }




    protected function result($data)
    {
        extract($data);

        if($challenger_shape == 'rock'){

            if($challenged_shape == 'rock'){
                return 'same';
            }elseif($challenged_shape == 'paper'){
                return 'challenged';
            }elseif($challenged_shape == 'scissor'){
                return 'challenger';
            }

        }elseif($challenger_shape == 'paper'){

            if($challenged_shape == 'rock'){
                return 'challenger';
            }elseif($challenged_shape == 'paper'){
                return 'same';
            }elseif($challenged_shape == 'scissor'){
                return 'challenged';
            }

        }elseif($challenger_shape == 'scissor'){

            if($challenged_shape == 'rock'){
                return 'challenged';
            }elseif($challenged_shape == 'paper'){
                return 'challenger';
            }elseif($challenged_shape == 'scissor'){
                return 'same';
            }

        }

    }





}

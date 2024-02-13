<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\TwilioRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Twilio\Rest\Client;

class ChannelController extends Controller
{
    protected $mainUrl = 'dashboard.channels.';
    protected $exceptionUrl = 'home';

    protected $twilio_repo;

    //  implement repository pattern
    public function __construct(TwilioRepositoryInterface $twilio_repo)
    {
        $this->twilio_repo = $twilio_repo;
    }

    public function index(Request $request)
    {
        try {

            //  implement repository pattern
            $rooms = $this->twilio_repo->getChannels();

            $data['channels'] = $this->paginate($rooms, 10);

            return view($this->mainUrl . 'index', $data);
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
            );

            $data['channels'] = [];
            return view($this->mainUrl . 'index', $data);
        }
    }

    public function show(Request $request)
    {
        try {

            $channel_resource = $this->twilio_repo->showChannels($request->sid);
            $data['channel'] = $channel_resource;

            return view($this->mainUrl . 'show', $data);
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
            );

            $data['messages'] = [];
            return view($this->mainUrl . 'show', $data);
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $messages = $this->twilio_repo->getMessages($request->sid);
            $data['messages'] = $this->paginate($messages, 20);
            $data['messages']->appends(['sid' => $request->sid]);

            return view($this->mainUrl . 'messages', $data);
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
            );

            $data['messages'] = [];
            return view($this->mainUrl . 'messages', $data);
        }
    }

    public function getMembers(Request $request)
    {

        try {

            $members = $this->twilio_repo->getMembers($request->sid);

            $data['members'] = $this->paginate($members, 10);
            return view($this->mainUrl . 'members', $data);
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
            );

            $data['members'] = [];
            return view($this->mainUrl . 'members', $data);
        }
    }

    public function getMedia(Request $request)
    {

        try {
            $response = $this->twilio_repo->getMedia($request->get('sid'));

            if ($response['error']) {
                return response()->json([
                    'status_code' => 409,
                    'data' => $response['error_type'],
                ], 409);
            } else {
                $url = $response['url'];

                return Redirect::to($url);
            }
        } catch (\Exception $ex) {

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }

    function paginate($items, $perPage)
    {
        $pageStart           = request('page', 1);
        $offSet              = ($pageStart * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, TRUE);

        return new LengthAwarePaginator(
            $itemsForCurrentPage,
            count($items),
            $perPage,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );
    }
}

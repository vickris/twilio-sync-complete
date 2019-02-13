<?php

namespace App\Http\Controllers;

use App\Candidate;
use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\SyncGrant;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // This function should be held in a more secure class in your code
    protected function getToken() {
        // Create yourself an identity for your token
        $identity = "SuperUser123";
        // Creates an access token, which we will then serialize and send to the client
        $token = new AccessToken(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_SYNC_API_KEY'),
            env('TWILIO_SYNC_API_SECRET'),
            3600,
            $identity
        );
        // Grant access to Sync
        $syncGrant = new SyncGrant();
        $syncGrant->setServiceSid(env('TWILIO_SYNC_SERVICE_SID'));
        $token->addGrant($syncGrant);
        return $token->toJWT();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function incrementVotes(Candidate $candidate)
    {
        $candidate->increment('votes_count');
        return $this->candidates();
    }

    public function candidates()
    {
        return response()->json([
            'data' => Candidate::all()
        ]);
    }
}

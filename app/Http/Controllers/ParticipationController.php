<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participation;
use Illuminate\Support\Facades\Auth;
use Validator;

class ParticipationController extends Controller
{
    /**
     * Display all participation of logged user
     */
    public function index()
    {
        return response()->json(
            Participation::select('*')->where('user_id', Auth::user()->id)->get()
        );
    }
    /**
     * Join to a campaign.
     */
    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        } 

        $participation = Participation::create(
            array_merge(
                $validator->validated(),
                ['user_id' => Auth::user()->id]
            )
        );

        return response()->json([
            'message' => 'Campaign joined successfully',
            'participation' => $participation
        ], 201);
    }

    /**
     * Leave from a campaign.
     */
    public function leave(Participation $participation)
    {
        $participation->delete();
        return response()->json([
            "success" => true,
            "message" => "Campaign left successfully.",
            "data" => $participation
        ]);
    }
}
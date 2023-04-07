<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Campaign::select('*')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'budget' => 'required|numeric',
            'limit' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $campaign = Campaign::create(
            array_merge(
                $validator->validated(),
                ['owner_id' => Auth::user()->id]
            )
        );
        return response()->json([
            'message' => 'Campaign successfully created',
            'campaign' => $campaign
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        return response()->json(
            $campaign->load('owner')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {     
        if (Auth::id() !== $campaign->owner_id) {
            abort(403, 'Unauthorized action.');
        } 
 
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'budget' => 'required|numeric',
            'limit' => 'required|string',
        ]); 

        if ($validator->fails()) { 
            return response()->json($validator->errors()->toJson(), 400);
        } 

        $uCampaign = $campaign->update($validator->validated());

        return response()->json([
            'message' => 'Campaign updated successfully',
            'campaign' => $uCampaign
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return response()->json([
            "success" => true,
            "message" => "Campaign deleted successfully.",
            "data" => $campaign
        ]);
    }

    /**
     * Change campign status like toggling.
     */
    public function changeStatus(Campaign $campaign)
    { 
        if($campaign->status == 'Ongoing'){
            $campaign->status = 'Completed';
        }else{
            $campaign->status = 'Ongoing';
        }
        $uCampign = $campaign->save();

        return response()->json([
            "success" => true,
            "message" => "Campaign status changed successfully.",
            "data" => $uCampign
        ]);
    }
}
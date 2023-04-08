<?php

namespace App\Http\Controllers;
 
use App\Http\Requests\campaign\StoreCampaignRequest;
use App\Http\Requests\campaign\UpdateCampaignRequest;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;

class CampaignController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendSuccess(Campaign::query()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request)
    {   
        $attributes = $request->validated();
        $attributes['owner_id'] = auth()->id(); 
        $campaign = Campaign::create($attributes); 
        return $this->sendSuccess($campaign, 'Campaign successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    { 
        return $this->sendSuccess($campaign->load('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    { 
        if (Auth::id() !== $campaign->owner_id) {
            abort(403, 'Unauthorized action.');
        } 
        $uCampaign = $campaign->update($request->validated());
        return $this->sendSuccess($uCampaign, 'Campaign updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete(); 
        return $this->sendSuccess($campaign, 'Campaign deleted successfully');
    }

    /**
     * Change campign status like toggling.
     */
    public function changeStatus(Campaign $campaign)
    {
        if ($campaign->status == 'Ongoing') {
            $campaign->status = 'Completed';
        } else {
            $campaign->status = 'Ongoing';
        }
        $uCampign = $campaign->save();
        return $this->sendSuccess($uCampign, 'Campaign status changed successfully');
    }
}
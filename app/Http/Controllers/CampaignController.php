<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatus;
use App\Http\Requests\campaign\StoreCampaignRequest;
use App\Http\Requests\campaign\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Services\Campaigns\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CampaignController extends BaseController
{
    public function __construct(
        protected CampaignService $service
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $campaigns = Campaign::query()->get(); 

        if ($campaigns) {
            return $this->sendSuccess(
                data: $campaigns
            );
        }
        return $this->sendFailed();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $attributes['owner_id'] = auth()->id();
        $campaign = $this->service->store($attributes);

        if ($campaign) {
            return $this->sendSuccess(
                message: __('messages.campaign.store') 
            );
        }
        return $this->sendFailed();
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign): JsonResponse
    {
        return $this->sendSuccess(data: $campaign->load('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign): JsonResponse
    {
        if (auth()->id() !== $campaign->owner_id) {
            abort(403, 'Unauthorized action.');
        } 

        $campaign = $this->service
            ->update($request->validated(), $campaign); 

        if ($campaign) {
            return $this->sendSuccess( 
                message: __('messages.campaign.update') 
            );
        }
        return $this->sendFailed();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        $campaign->delete();
        return $this->sendSuccess(message: __('messages.campaign.delete') );
    }

    /**
     * Change campign status like toggling.
     */
    public function changeStatus(Campaign $campaign): JsonResponse
    {
        if ($campaign->status == CampaignStatus::ONGOING->value) {
            $campaign->status = CampaignStatus::COMPLETED->value;
        } else {
            $campaign->status = CampaignStatus::ONGOING->value;
        }
        $campaign->save();
        return $this->sendSuccess(message: __('messages.campaign.status'));
    }
}
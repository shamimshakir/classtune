<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;

class CampaignService
{  
    public function store(array $campaignData): Campaign
    {
        return Campaign::create($campaignData);  
    }
 
    public function update(array $campaignData, Campaign $campaign)
    { 
        return $campaign->update($campaignData); 
    }
}

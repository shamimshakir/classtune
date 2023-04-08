<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;

class CampaignService
{  
    public function store(array $attributes): Campaign
    {
        return Campaign::query()->create($attributes);  
    }
 
    public function update(array $attributes, Campaign $campaign)
    { 
        return $campaign->update($attributes); 
    }
}

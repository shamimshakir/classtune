<?php

namespace App\Http\Controllers;

use App\Http\Requests\participator\JoinParticipatorRequest;
use Illuminate\Http\Request;
use App\Models\Participator;
use Illuminate\Support\Facades\Auth;
use Validator;

class ParticipatorController extends BaseController
{
    /**
     * Display all participation of logged user
     */
    public function index()
    { 
        return $this->sendSuccess(
            Participator::where('user_id', auth()->id())->get()
        );
    }
    /**
     * Join to a campaign.
     */
    public function join(JoinParticipatorRequest $request)
    { 
        $attributes = $request->validated();
        $attributes['user_id'] = auth()->id(); 
        $participator = Participator::create($attributes); 
        return $this->sendSuccess($participator, 'Campaign joined successfully');
    }

    /**
     * Leave from a campaign.
     */
    public function leave(Participator $participator)
    {
        $participator->delete(); 
        return $this->sendSuccess($participator, 'Campaign left successfully');
    }
}
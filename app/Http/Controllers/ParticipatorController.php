<?php

namespace App\Http\Controllers;

use App\Http\Requests\participator\JoinParticipatorRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Participator;
use Illuminate\Support\Facades\Auth;
use Validator;

class ParticipatorController extends BaseController
{
    /**
     * Display all participation of logged user
     */
    public function index(): JsonResponse
    { 
        $participators = Participator::query()->where('user_id', auth()->id())->get();
        return $this->sendSuccess(data: $participators);
    }
    /**
     * Join to a campaign.
     */
    public function join(JoinParticipatorRequest $request): JsonResponse
    { 
        $attributes = $request->validated();
        $attributes['user_id'] = auth()->id(); 
        $participator = Participator::query()->create($attributes);  

        if ($participator) {
            return $this->sendSuccess(
                message: __('messages.participator.join') 
            );
        }
        return $this->sendFailed();
    }

    /**
     * Leave from a campaign.
     */
    public function leave(Participator $participator): JsonResponse
    {
        $participator->delete();  
        return $this->sendSuccess(message: __('messages.participator.leave') );
    }
}
<?php

namespace App\Http\Resources;

use App\Models\Twit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'username' => $this->resource->username,
            'bio' => $this->resource->bio,
            'profile' => $this->getMedia($request->user()),
            'followers' => $this->resource->followers->count(),
            'followings' => $this->resource->followings->count(),
            'is_followed' => $this->resource->isFollowed->count(),
            'is_blocked' => $this->isInBlockList(),
            'is_muted' => $this->isInMuteList(),
            'is_restricted' => $this->isInRestrictList(),
            'created_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'twits' => $this->getTwits(),
        ];
    }

    private function getMedia(User $user)
    {
        return Storage::disk('avatar')->url($user->id . '/' . $this->resource->profile);
    }

    private function getTwits()
    {
        return $this->isInBlockList() ? null : TwitResource::collection(Twit::query()->where('user_id', $this->resource->id)->paginate(10));
    }

    private function isInBlockList()
    {
        $blocks = auth()->user()->getBlocks();
        return in_array($this->resource->id, json_decode($blocks)) !== false;
    }

    private function isInMuteList()
    {
        $mutes = auth()->user()->getMutes();
        return in_array($this->resource->id, json_decode($mutes)) !== false;
    }

    private function isInRestrictList()
    {
        $restricts = auth()->user()->getRestricts();
        return in_array($this->resource->id, json_decode($restricts)) !== false;
    }
}

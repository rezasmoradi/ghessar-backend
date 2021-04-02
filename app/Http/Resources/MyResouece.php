<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class MyResouece extends JsonResource
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
            'mobile' => $this->resource->mobile,
            'bio' => $this->resource->bio,
            'email' => $this->resource->email,
            'created_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'avatars' => $this->getAvatars(),
            'followers' => $this->resource->followers->count(),
            'followings' => $this->resource->followings->count(),
            'twits' => $request->user()->twits()->take(10),
        ];
    }

    private function getAvatars()
    {
        return array_map(function ($item) {
            return Storage::disk('avatar')->url($item);
        }, Storage::disk('avatar')->allFiles($this->resource->id));
    }
}

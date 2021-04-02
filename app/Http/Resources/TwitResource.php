<?php

namespace App\Http\Resources;

use App\Models\Like;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TwitResource extends JsonResource
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
            'body' => $this->resource->body,
            'media' => is_null($this->resource->media) ? null : $this->getMedia($request->user()),
            'created_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'likes_count' => $this->resource->likes_count,
            'is_liked' => $this->resource->is_liked_count,
            'retwits_count' => $this->resource->retwits_count,
            'is_retwitted' => $this->resource->is_retwitted_count,
            'comments_count' => $this->resource->comments_count,
            'user' => $this->user($this->resource->user),
        ];
    }

    public function user(User $user)
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'avatar' => $user->avatar,
        ];
    }

    private function getMedia(User $user)
    {
        return Storage::disk('media')->url($user->id . '/' . $this->resource->media);
    }
}

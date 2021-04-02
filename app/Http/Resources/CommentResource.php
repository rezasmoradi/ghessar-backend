<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    public static $wrap = '';

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
            'user' => $this->getUser(),
        ];
    }

    private function getMedia(User $user)
    {
        return Storage::disk('media')->url($user->id . '/' . $this->resource->media);
    }

    private function getUser()
    {
        $user = User::query()->find($this->resource->user_id);
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'profile' => $user->profile,
        ];
    }
}

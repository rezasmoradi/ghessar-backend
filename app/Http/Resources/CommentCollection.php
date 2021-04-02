<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class CommentCollection extends ResourceCollection
{
    public static $wrap = '';

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->groupBy('reply_to'),
/*            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'next_page_url' => $this->nextPageUrl(),*/
//            'replies' => CommentResource::collection($this->resource),
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

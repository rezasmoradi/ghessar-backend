<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TwitCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'twits' => $this->collection,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'key' => 'value'
            ]
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourceListing extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'summary' => $this->summary,
            'slug' => $this->slug,
            'userId' => $this->userId,
            'userName' => $this->userName
        ];
    }

    public function with($request)
    {
        return [
            'message' => 'ok',
            'success' => true
        ];
    }
}

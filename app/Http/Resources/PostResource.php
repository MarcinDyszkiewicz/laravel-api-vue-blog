<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'body' => $this->body,
            'meta_title' => $this->meta_title,
            'meta_body' => $this->meta_body,
            'image' => $this->image,
            'summary' => $this->summary,
            'slug' => $this->slug,
            'user' => [
                $this->user
            ]
        ];
    }
}

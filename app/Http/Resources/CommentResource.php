<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user_id' => $this->id,
            'comment_parent_id' => $this->comment_parent_id,
            'body' => $this->body,
            'is_spoiler' => $this->is_spoiler,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
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

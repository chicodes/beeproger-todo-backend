<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

//        return [
//            "status"  => "Succesful",
//            "code"    => '200',
//            "data" => $this->collection,
//            'links' => [
//                'self' => 'link-value',
//            ],
//        ];

    }
}

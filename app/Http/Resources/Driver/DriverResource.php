<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'image' => $this->image,
            'phone' => $this->phone,
            'salary' => $this->salary,
            'iban' => $this->iban,
            'identity_number' => $this->identity_number,
            'identity_image' => $this->identity_image,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}

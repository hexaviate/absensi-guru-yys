<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'instansi' => $this->instansi->name,
            'hari' => $this->hari,
            'datang' => $this->datang,
            "pulang" => $this->pulang
        ];
    }
}

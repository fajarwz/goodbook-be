<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// use App\Enums\Role;
use Carbon\Carbon;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('F j, Y'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('F j, Y'),
            // 'role' => [
            //     'id' => $this->role_id,
            //     'name' => Role::from($this->role_id)->name,
            // ],
        ];
    }
}

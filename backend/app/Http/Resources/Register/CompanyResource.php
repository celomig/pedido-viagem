<?php

namespace App\Http\Resources\Register;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cnpj' => $this->cnpj,
            'company_type' => $this->company_type,
            'responsible_name' => $this->responsible_name,
            'responsible_cpf' => $this->responsible_cpf,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')), // Relacionamento com Address
        ];
    }
}

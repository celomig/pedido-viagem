<?php

namespace App\Services\Register;

use App\Models\Register\Company;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function createCompany(array $data): Company
    {
        return DB::transaction(function () use ($data) {
            $company = Company::create($data);

            if (isset($data['addresses'])) {
                foreach ($data['addresses'] as $addressData) {
                    $company->addresses()->create($addressData);
                }
            }

            return $company;
        });
    }

    public function updateCompany(Company $company, array $data): Company
    {
        return DB::transaction(function () use ($company, $data) {
            $company->update($data);

            if (isset($data['addresses'])) {
                foreach ($data['addresses'] as $addressData) {
                    if (isset($addressData['id'])) {
                        $address = Address::find($addressData['id']);
                        $address?->update($addressData);
                    } else {
                        $company->addresses()->create($addressData);
                    }
                }
            }

            return $company;
        });
    }
}

<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Models\Register\Company;
use App\Http\Requests\Register\StoreCompanyRequest;
use App\Http\Requests\Register\UpdateCompanyRequest;
use App\Http\Resources\Register\CompanyResource;
use App\Services\Register\CompanyService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $companyService) {}

    /**
     * List all companies.
     *
     * @group Company Management
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Company Name",
     *       "cnpj": "12345678000195",
     *       "company_type": "Limited",
     *       "responsible_name": "John Doe",
     *       "responsible_cpf": "12345678901",
     *       "addresses": [
     *         {
     *           "id": 1,
     *           "street": "Main Street",
     *           "city": "Springfield",
     *           "state": "IL",
     *           "postal_code": "62704"
     *         }
     *       ]
     *     }
     *   ],
     *   "message": {
     *     "text": "Lista de empresas recuperada com sucesso.",
     *     "type": "info"
     *   }
     * }
     */
    public function index(): ResourceCollection
    {
        return CompanyResource::collection(Company::with('addresses')->get())
            ->additional([
                'message' => [
                    'text' => 'Lista de empresas recuperada com sucesso.',
                    'type' => 'info',
                ],
            ]);
    }

    /**
     * Create a new company.
     *
     * @group Company Management
     *
     * @bodyParam name string required The name of the company. Example: Company Name
     * @bodyParam cnpj string required The CNPJ of the company. Example: 12345678000195
     * @bodyParam company_type string required The type of the company. Example: Limited
     * @bodyParam responsible_name string required The name of the person responsible. Example: John Doe
     * @bodyParam responsible_cpf string required The CPF of the person responsible. Example: 12345678901
     * @bodyParam addresses array An array of address objects.
     * @bodyParam addresses[].street string required The street of the address. Example: Main Street
     * @bodyParam addresses[].city string required The city of the address. Example: Springfield
     * @bodyParam addresses[].state string required The state of the address. Example: IL
     * @bodyParam addresses[].postal_code string required The postal code of the address. Example: 62704
     *
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "name": "Company Name",
     *     "cnpj": "12345678000195",
     *     "company_type": "Limited",
     *     "responsible_name": "John Doe",
     *     "responsible_cpf": "12345678901",
     *     "addresses": [
     *       {
     *         "id": 1,
     *         "street": "Main Street",
     *         "city": "Springfield",
     *         "state": "IL",
     *         "postal_code": "62704"
     *       }
     *     ]
     *   },
     *   "message": {
     *     "text": "Empresa criada com sucesso.",
     *     "type": "success"
     *   }
     * }
     */
    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = $this->companyService->createCompany($request->validated());
        return CompanyResource::make($company)
            ->additional([
                'message' => [
                    'text' => 'Empresa criada com sucesso.',
                    'type' => 'success',
                ],
            ]);
    }

    /**
     * Show a specific company.
     *
     * @group Company Management
     *
     * @urlParam id int required The ID of the company. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Company Name",
     *     "cnpj": "12345678000195",
     *     "company_type": "Limited",
     *     "responsible_name": "John Doe",
     *     "responsible_cpf": "12345678901",
     *     "addresses": [
     *       {
     *         "id": 1,
     *         "street": "Main Street",
     *         "city": "Springfield",
     *         "state": "IL",
     *         "postal_code": "62704"
     *       }
     *     ]
     *   },
     *   "message": {
     *     "text": "Empresa recuperada com sucesso.",
     *     "type": "info"
     *   }
     * }
     */
    public function show(int $id): CompanyResource
    {
        $company = Company::with('addresses')->findOrFail($id);
        return CompanyResource::make($company)
            ->additional([
                'message' => [
                    'text' => 'Empresa recuperada com sucesso.',
                    'type' => 'info',
                ],
            ]);
    }

    /**
     * Update a company.
     *
     * @group Company Management
     *
     * @urlParam id int required The ID of the company. Example: 1
     * @bodyParam name string The updated name of the company. Example: Updated Company Name
     * @bodyParam cnpj string The updated CNPJ of the company. Example: 98765432000195
     * @bodyParam company_type string The updated type of the company. Example: Corporation
     * @bodyParam responsible_name string The updated name of the responsible person. Example: Jane Doe
     * @bodyParam responsible_cpf string The updated CPF of the responsible person. Example: 98765432100
     * @bodyParam addresses array An array of updated address objects.
     * @bodyParam addresses[].id int The ID of the address (if updating an existing address). Example: 1
     * @bodyParam addresses[].street string The updated street. Example: Elm Street
     * @bodyParam addresses[].city string The updated city. Example: Metropolis
     * @bodyParam addresses[].state string The updated state. Example: NY
     * @bodyParam addresses[].postal_code string The updated postal code. Example: 12345
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Company Name",
     *     "cnpj": "98765432000195",
     *     "company_type": "Corporation",
     *     "responsible_name": "Jane Doe",
     *     "responsible_cpf": "98765432100",
     *     "addresses": [
     *       {
     *         "id": 1,
     *         "street": "Elm Street",
     *         "city": "Metropolis",
     *         "state": "NY",
     *         "postal_code": "12345"
     *       }
     *     ]
     *   },
     *   "message": {
     *     "text": "Empresa atualizada com sucesso.",
     *     "type": "success"
     *   }
     * }
     */
    public function update(UpdateCompanyRequest $request, int $id): CompanyResource
    {
        $company = Company::findOrFail($id);
        $updatedCompany = $this->companyService->updateCompany($company, $request->validated());
        return CompanyResource::make($updatedCompany)
            ->additional([
                'message' => [
                    'text' => 'Empresa atualizada com sucesso.',
                    'type' => 'success',
                ],
            ]);
    }

    /**
     * Delete a company.
     *
     * @group Company Management
     *
     * @urlParam id int required The ID of the company. Example: 1
     *
     * @response 204 {
     *   "message": {
     *     "text": "Empresa excluída com sucesso.",
     *     "type": "success"
     *   }
     * }
     */
    public function destroy(int $id): JsonResponse
    {
        $company = Company::findOrFail($id);

        $company->addresses()->delete();
        $company->delete();

        return response()->json([
            'message' => [
                'text' => 'Empresa excluída com sucesso.',
                'type' => 'success',
            ],
        ], 204);
    }
}

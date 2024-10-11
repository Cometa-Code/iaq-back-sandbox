<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Http\Requests\Contracts\CreateContractRequest;
use App\Models\Cbos;
use App\Models\CompanyData;
use App\Models\Contracts;
use App\Models\User;
use App\Models\YoungApprenticeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractsController extends Controller
{
    public function store(CreateContractRequest $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getYoungApprentice = User::where('id', $request->young_apprentice_id)->first();

        if (!$getYoungApprentice) {
            return Responses::NOTFOUND('Não foi possível encontrar esse jovem aprendiz!');
        }

        $getCompany = User::where('id', $request->company_id)->first();

        if (!$getCompany) {
            return Responses::NOTFOUND('Não foi possível encontrar essa empresa!');
        }

        $getCbo = Cbos::where('id', $request->cbo_id)->first();

        if (!$getCbo) {
            return Responses::NOTFOUND('Não foi possível encontrar esse CBO!');
        }

        $data = $request->all();

        $createContract = Contracts::create($data);

        if (!$createContract) {
            return Responses::BADREQUEST('Ocorreu um erro durante a criação do contrato!');
        }

        return Responses::CREATED('Contrato criado com sucesso!');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $itemsPerPage = $request->query('items_per_page', 10);
        $termsFilter = $request->query('terms_filter', '');

        $listContracts = Contracts::where('contract_number', 'LIKE', "%$termsFilter%")
                                    ->with('young_apprentice.young_apprentice_data')
                                    ->with('company.company_data')
                                    ->with('cbo')
                                    ->orderBy('id', 'DESC')
                                    ->paginate($itemsPerPage);

        return Responses::OK('', $listContracts);
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $contract = Contracts::where('id', $id)
                                    ->with('young_apprentice.young_apprentice_data')
                                    ->with('company.company_data')
                                    ->with('cbo')
                                    ->first();

        if (!$contract) {
            return Responses::NOTFOUND('Não foi possível encontrar o contrato especificado!');
        }

        return Responses::OK('', $contract);
    }

    public function get_data_to_create_contract()
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getYoungApprentices = User::select('id', 'name')->where('role', 'youngapprentice')->orderBy('name', 'ASC')->get();

        $getCompanies = CompanyData::select('user_id', 'social_reason_company')->orderBy('social_reason_company', 'ASC')->get();

        $getCBOS = Cbos::select('id', 'code')->orderBy('code', 'ASC')->get();

        $data = [
            "young_apprentices" => $getYoungApprentices,
            "companies" => $getCompanies,
            "cbos" => $getCBOS
        ];

        return Responses::OK('', $data);
    }

    public function get_full_infos_to_make_contract($apprentice_id, $company_id, $cbo_id)
    {
        if (!$apprentice_id || !$company_id || !$cbo_id) {
            return Responses::BADREQUEST('Informações insuficientes para montar o contrato!');
        }

        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getYoungApprentice = User::where('id', $apprentice_id)->with('young_apprentice_data')->first();

        $getCompany = User::where('id', $company_id)->with('company_data')->first();

        $getCbo = Cbos::where('id', $cbo_id)->first();

        $lastContractId = Contracts::orderBy('id', 'DESC')->first();

        if ($lastContractId) {
            $data = [
                "young_apprentice" => $getYoungApprentice,
                "company" => $getCompany,
                "cbo" => $getCbo,
                "last_contract_id" => $lastContractId->id
            ];
        }
        else {
            $data = [
                "young_apprentice" => $getYoungApprentice,
                "company" => $getCompany,
                "cbo" => $getCbo,
                "last_contract_id" => 0
            ];
        }

        return Responses::OK('', $data);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getContract = Contracts::where('id', $id)->first();

        if (!$getContract) {
            return Responses::BADREQUEST('Contrato não localizado!');
        }

        $getContract->update($request->all());
        $getContract->save();

        return Responses::OK('Contrato atualizado com sucesso!');
    }
}

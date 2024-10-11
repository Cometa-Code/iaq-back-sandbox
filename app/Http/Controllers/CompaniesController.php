<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Http\Requests\Companies\CreateCompaniesRequest;
use App\Models\CompanyData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $itemsPerPage = $request->query('items_per_page', 10);
        $termsFilter = $request->query('terms_filter', '');

        $listCompanies = User::where(function($query) use ($termsFilter) {
            $query->where('name', 'LIKE', "%$termsFilter%")
                ->orWhere('email', 'LIKE', "%$termsFilter%")
                ->orWhere('principal_document', 'LIKE', "%$termsFilter%");
            })
            ->where('role', '=', 'company')
            ->with('company_data')
            ->orderBy('id', 'DESC')
            ->paginate($itemsPerPage);

        return Responses::OK('', $listCompanies);
    }

    public function store(CreateCompaniesRequest $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getUser = User::where('email', $request->email_youth_supervisor)
                        ->orWhere('principal_document', $request->cnpj_company)
                        ->first();

        if ($getUser) {
            return Responses::BADREQUEST('Usuário já cadastrado com os dados informados!');
        }

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}|:<>?-=[]\;,./';

        $scrambledCharacters = str_shuffle($characters);

        $password = substr($scrambledCharacters, 0, 10);

        $createUser = User::create([
            "name" => $request->name_legal_representative,
            "email" => $request->email_youth_supervisor,
            "principal_document" => $request->cnpj_company,
            "role" => "company",
            "is_active" => true,
            "password" => $password
        ]);

        if (!$createUser) {
            return Responses::BADREQUEST('Ocorreu um erro durante a criação do usuário.');
        }

        $data = $request->all();
        $data['user_id'] = $createUser->id;

        $createCompany = CompanyData::create($data);

        if (!$createCompany) {
            return Responses::BADREQUEST('Ocorreu um erro durante a criação dos dados do usuário');
        }

        return Responses::CREATED('Empresa adicionada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $getUser = User::where('id', $id)->first();

        if (!$getUser) {
            return Responses::NOTFOUND('Usuário não encontrado!');
        }

        if ($getUser->id != $user->id && ($user->role != 'admin' && $user->role != 'superadmin')) {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $updateUser = $getUser->update($request->all());

        if (!$updateUser) {
            return Responses::BADREQUEST('Ocorreu um erro durante a atualização do empresa!');
        }

        $updateData = $getUser->company_data->update($request->all());

        if (!$updateData) {
            return Responses::BADREQUEST('Ocorreu um erro durante a atualização do empresa!');
        }

        return Responses::OK('Empresa atualizada com sucesso!');
    }
}

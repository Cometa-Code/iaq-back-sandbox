<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Http\Requests\YoungApprentices\CreateYoungApprenticesRequest;
use App\Models\User;
use App\Models\YoungApprenticeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YoungApprenticesController extends Controller
{
    public function store(CreateYoungApprenticesRequest $request)
    {

        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getUser = User::where('email', $request->email)
                        ->orWhere('principal_document', $request->document_cpf)
                        ->first();

        if ($getUser) {
            return Responses::BADREQUEST('Usuário já cadastrado com os dados informados!');
        }

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+{}|:<>?-=[]\;,./';

        $scrambledCharacters = str_shuffle($characters);

        $password = substr($scrambledCharacters, 0, 10);

        $createUser = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "principal_document" => $request->document_cpf,
            "role" => "youngapprentice",
            "is_active" => true,
            "password" => $password
        ]);

        if (!$createUser) {
            return Responses::BADREQUEST('Ocorreu um erro durante a criação do usuário.');
        }

        $data = $request->all();
        $data['user_id'] = $createUser->id;

        $createYoungData = YoungApprenticeData::create($data);

        if (!$createYoungData) {
            return Responses::BADREQUEST('Ocorreu um erro durante a criação dos dados do usuário');
        }

        return Responses::CREATED('Jovem aprendiz adicionado com sucesso!');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $itemsPerPage = $request->query('items_per_page', 10);
        $termsFilter = $request->query('terms_filter', '');

        $listYoungApprentices = User::where(function($query) use ($termsFilter) {
                                    $query->where('name', 'LIKE', "%$termsFilter%")
                                        ->orWhere('email', 'LIKE', "%$termsFilter%")
                                        ->orWhere('principal_document', 'LIKE', "%$termsFilter%");
                                    })
                                    ->where('role', '=', 'youngapprentice')
                                    ->with('young_apprentice_data')
                                    ->orderBy('id', 'DESC')
                                    ->paginate($itemsPerPage);

        return Responses::OK('', $listYoungApprentices);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $getUser = User::where('id', $id)->first();

        if (!$getUser) {
            return Responses::NOTFOUND('Usuário não encontrado!');
        }

        if (($getUser->id != $user->id) && ($user->role != 'admin' && $user->role != 'superadmin')) {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $updateUser = $getUser->update($request->all());

        if (!$updateUser) {
            return Responses::BADREQUEST('Ocorreu um erro durante a atualização do jovem aprendiz!');
        }

        $updateData = $getUser->young_apprentice_data->update($request->all());

        if (!$updateData) {
            return Responses::BADREQUEST('Ocorreu um erro durante a atualização do jovem aprendiz!');
        }

        return Responses::OK('Jovem atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getUser = User::where('id', $id)->first();

        if (!$getUser) {
            return Responses::NOTFOUND('Usuário não localizado!');
        }

        $getUser->delete();

        return Responses::OK('Jovem removido com sucesso!');
    }
}

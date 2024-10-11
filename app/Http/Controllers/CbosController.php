<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Http\Requests\CBOS\CreateCboResquest;
use App\Models\Cbos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CbosController extends Controller
{
    public function store(CreateCboResquest $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $createCbo = Cbos::create($request->all());

        if (!$createCbo) {
            return Responses::BADREQUEST('Ocorreu um erro durante a criação do CBO!');
        }

        return Responses::CREATED('CBO criado com sucesso!');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $itemsPerPage = $request->query('items_per_page', 10);
        $termsFilter = $request->query('terms_filter', '');

        $listCbos = Cbos::where('code', 'LIKE', "%$termsFilter%")
                        ->orderBy('id', 'DESC')
                        ->paginate($itemsPerPage);

        return Responses::OK('', $listCbos);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role != 'admin' && $user->role != 'superadmin') {
            return Responses::BADREQUEST('Apenas usuários permitidos podem executar essa ação!');
        }

        $getCbo = Cbos::where('id', $id)->first();

        if (!$getCbo) {
            return Responses::NOTFOUND('Não foi possível encontrar o CBO especificado!');
        }

        $data = $request->all();

        $getCbo->update($data);

        return Responses::OK('CBO atualizado com sucesso!');
    }
}

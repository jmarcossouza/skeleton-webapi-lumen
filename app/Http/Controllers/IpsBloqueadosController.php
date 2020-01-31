<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\IpsBloqueados;
use DateTime;
use Illuminate\Http\Request;

class IpsBloqueadosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getAll(Request $request)
    {
        return IpsBloqueados::paginate($request->input('itens_per_page', 20));
    }

    public function get(Request $request)
    {
        $this->validate($request, ["ip" => "required|max:45"]);
        return IpsBloqueados::findOrFail($request->ip)->toJson();
    }

    public function create(Request $request)
    {
        $this->validate($request, IpsBloqueados::$regras_validacao);
        $ip_bloqueado = new IpsBloqueados($request->all());
        $ip_bloqueado->save();

        return response($ip_bloqueado, 201);
    }

    public function update(Request $request)
    {
        $this->validate($request, IpsBloqueados::$regras_validacao);
        $ip_bloqueado = IpsBloqueados::findOrFail($request->ip);

        if ($ip_bloqueado->expira_em == null) {
            throw new InvalidRequestException("Você não pode alterar um bloqueio permanente.");
        }

        if (new DateTime($ip_bloqueado->expira_em) < new DateTime($request->expira_em)) {
            throw new InvalidRequestException("Você diminuir o tempo de expiração de um bloqueio.");
        }

        $ip_bloqueado->motivo = $request->motivo;
        $ip_bloqueado->expira_em = $request->expira_em;
        $ip_bloqueado->save();

        return response($ip_bloqueado, 200);
    }

    public function destroy(Request $request)
    {
        $this->validate($request, ['ip' => 'required|max:45']);
        $item = IpsBloqueados::findOrFail($request->ip);
        $item->delete();

        return response(null, 200);
    }
}

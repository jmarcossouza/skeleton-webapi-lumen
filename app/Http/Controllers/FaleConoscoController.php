<?php

namespace App\Http\Controllers;

use App\Models\FaleConosco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaleConoscoController extends Controller
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
        $fale_conosco_list = new FaleConosco;
        if ($request->has('filtros')) {
            $filtro_visualizado_em = $request->input('filtros.visualizado_em');

            if ($filtro_visualizado_em === true) {
                $fale_conosco_list = $fale_conosco_list->whereNotNull('visualizado_em');
            } else if ($filtro_visualizado_em === false) {
                $fale_conosco_list = $fale_conosco_list->whereNull('visualizado_em');
            }
        }

        return $fale_conosco_list->paginate($request->input('itens_per_page', 20));
    }

    public function get($id)
    {
        return FaleConosco::findOrFail($id);
    }

    public function marcarVisualizado($id)
    {
        $fale_conosco = FaleConosco::findOrFail($id);
        $fale_conosco->marcarVisualizado();
        return $fale_conosco->toJson();
    }

    public function desmarcarVisualizado($id)
    {
        $fale_conosco = FaleConosco::findOrFail($id);
        $fale_conosco->desmarcarVisualizado();
        return $fale_conosco->toJson();
    }

    public function create(Request $request)
    {
        $this->validate($request, FaleConosco::$regras_validacao);

        $fale_conosco = new FaleConosco($request->all());
        $fale_conosco->save();

        return $fale_conosco->toJson();
    }

    public function getAssuntos()
    {
        return DB::table('assuntos_fale_conosco')->get();
    }
}

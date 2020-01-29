<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class FaleConosco extends Model
{
    public $table = 'fale_conosco';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nome', 'email', 'assuntos_fale_conosco_id', 'mensagem'];

    const UPDATED_AT = null;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['visualizado_em', 'created_at'];

    public static $regras_validacao = [
        'nome'         => 'nullable|max:60|min:4',
        'email'         => 'nullable|max:60|min:4',
        'mensagem'          => 'required|max:8000',
        'visualizado_em'     => 'nullable|date'
    ];

    public function marcarVisualizado(): void
    {
        $this->visualizado_em = new DateTime('now');
        $this->save();
    }

    public function desmarcarVisualizado(): void
    {
        $this->visualizado_em = null;
        $this->save();
    }
}

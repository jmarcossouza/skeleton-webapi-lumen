<?php

namespace App\Models;

use App\Exceptions\InvalidRequestException;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IpsBloqueados extends Model
{
    protected $table = 'ips_bloqueados';

    protected $primaryKey = 'ip';
    public $incrementing = false;

    /**
     * Campos que podem ser preenchidos com mass assigment
     *
     * @var array
     */
    protected $fillable = ['ip', 'motivo', 'expira_em'];

    /**
     * Regras de validação
     *
     * @var array
     */
    public static $regras_validacao = [
        'ip' => 'required|max:45',
        'motivo' => 'nullable|max:200',
        'expira_em' => 'nullable|date'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *  Indica quais campos são datas, que aí o Laravel já adianta uns bagui.
     * @var array
     */
    protected $dates = ['expira_em', 'created_at'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if ($registro_encontrado = self::find($model->ip) != null) {
                $expira_em = $model->expira_em;
                //PAREI AQUI: ta dando algum erro nessa porra de if...
                if ($expira_em == null) { //Se estiver criando pra permanente, vai atualizar a do banco idependente de como está lá.
                    // $registro_encontrado->expira_em = null;
                    // $registro_encontrado->save();
                    $balbla= "sdff";
                } elseif (new DateTime($model->expira_em) > new DateTime($registro_encontrado->expira_em)) { //Se a data de bloqueio for maior do que a do registro do banco, vai atualizá-la.
                    $registro_encontrado->expira_em = $model->expira_em;
                    $registro_encontrado->save();
                }
                return false;
            }
        });
    }
}

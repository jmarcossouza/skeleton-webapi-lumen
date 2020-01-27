<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exemplo extends Model
{

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'exemplo';

    /**
     * Nome do campo que é a primary key.
     * Aceita também um array ['campo1', 'campo2']
     *
     * @var mixed
     */
    protected $primaryKey = 'campo_pk';

    /**
     * Se mudou o primaryKey e não é ID autoIncrement, precisa por isso aqui.
     * DEVE ser public
     */
    public $incrementing = false;

    /**
     * Campos que podem ser preenchidos com mass assigment
     *
     * @var array
     */
    protected $fillable = ['email', 'senha', 'nome', 'ultimo_login'];

    /**
     * Campos a serem escondidos no GET.
     *
     * @var array
     */
    protected $hidden = ['senha', 'ultimo_login'];

    /**
     * Regras de validação
     *
     * @var array
     */
    public $regras_validacao = [
        'email' => 'bail|required|unique:usuario|max:60',
        'senha' => 'bail|required|min:6|max:60',
        'nome' => 'bail|required|min:4|max:60',
        'ultimo_login' => 'nullable',
    ];

    /**
     * Conversões de tipos dos campos.
     *
     * @var array
     */
    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Definir valores default para os campos.
     *
     * @var array
     */
    protected $attributes = [
        'ativo' => true,
    ];

    /**
     * Se há um campo que deve registrar a data de criação e tem outro nome, deve ser setado o nme aqui.
     */
    const CREATED_AT = 'criado_em';

    /**
     * Caso não queria registrar quando é feito o update, basta setar como nulo.
     */
    const UPDATED_AT = null;

    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *  Indica quais campos são datas, que aí o Laravel já adianta uns bagui.
     * @var array
     */
    protected $dates = ['exp_redefinir_senha'];

    /**
     * Só são chamados quando alterando as properties do objeto e depois usando o save(). Ex: no $this->update() do Eloquent, isso aqui não é chamado.
     * Então, para isso funcionar, é o correto usar sempre os modelos para fazer as operações.
     * Funciona no $this->create()
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            // ... code here
        });
        self::created(function ($model) {
            // ... code here
        });
        self::updating(function ($model) {
            // ... code here
        });
        self::updated(function ($model) {
            // ... code here
        });
        self::deleting(function ($model) {
            // ... code here
        });
        self::deleted(function ($model) {
            // ... code here
        });
    }
}

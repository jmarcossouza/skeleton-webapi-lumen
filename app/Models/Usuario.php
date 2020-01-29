<?php

namespace App\Models;

use App\Jobs\ConfirmarEmailJob;
use DateTime;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'senha', 'nome', 'sobrenome', 'token_redefinir_senha', 'exp_redefinir_senha', 'token_confirmar_email'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['senha', 'token_confirmar_email', 'token_redefinir_senha', 'exp_redefinir_senha'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['exp_redefinir_senha'];

    public $regras_validacao = [
        'email'         => 'required|unique:usuarios|max:60',
        'senha'         => 'required|min:6|max:64',
        'nome'          => 'required|min:4|max:20',
        'sobrenome'     => 'required|min:4|max:40'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->senha = self::hashSenha($model->senha);

            if (config('defaults.usuario_confirmar_email') == true) {
                $model->token_confirmar_email = self::tokenUnico();
            } else {
                $model->token_confirmar_email = null;
            }
        });
        self::created(function ($model) {
            Log::newLog(1, $model->id);
            if ($model->token_confirmar_email != null) { //Aqui, ao invés de verificar se config('defaults.usuario_confirmar_email') == true. É melhor verificar se o campo do token do cara está nulo. Porque pode acontecer de eu mudar a configuração enquanto alguém está criando um usuário, aí o código cairá aqui e não enviará o e-mail de confirmação.
                dispatch(new ConfirmarEmailJob(Usuario::findOrFail($model->id))); //Enviar o e-mail de confirmação por queue (em segundo plano).
                //Mail::to($model->email)->send(new ConfirmarEmailMail(Usuario::findOrFail($model->id))); //Enviar o e-mail de confirmação imediatamente.
            }
        });
        self::updating(function ($model) {

        });
    }

    /**
     * Irá criar o Hash da senha a partir da senha passada no parâmetro.
     *
     * @param string $senha
     * @return string hash da senha enviada.
     */
    public static function hashSenha(string $senha): string
    {
        return Hash::make($senha);
    }

    /**
     * Verifica se a senha passada bate com a senha do usuário.
     *
     * @param string $senha senha a ser verificada.
     * @return boolean Retorna true se as senhas baterem. false caso contrário.
     */
    public function verificarSenha(string $senha): bool
    {
        return Hash::check($senha, $this->senha);
    }

    public static function verificarSenhaStatic(string $senha, string $hash): bool
    {
        return Hash::check($senha, $hash);
    }

    /**
     * Cria o token do usuário e já atualiza o mesmo.
     *
     * @return string Retornará o token necessário para recuperar a senha.
     */
    public function esqueciMinhaSenha(): string
    {
        $token = Usuario::tokenUnico();
        $this->timestamps = false; //Pra não atualizar o updated_at
        $this->token_redefinir_senha = $token;
        $string_minutos = "+" . config('defaults.exp.redefinir_senha') . " minutes";
        $this->exp_redefinir_senha = (new DateTime($string_minutos));
        $this->save();

        return $token;
    }

    /**
     * Criará um token único com o SHA256
     *
     * @return string Retorna um SHA256 de 64 caractéres
     */
    public static function tokenUnico(): string
    {
        return hash('sha256', date('Y-m-d H:i:s') . rand(0, 100000) . rand(0, 100000) . rand(0, 100000) . rand(0, 100000) . rand(0, 100000));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'nome' => $this->nome,
            'sobrenome' => $this->sobrenome,
            'email' => $this->email,
            'admin' => $this->admin
        ];
    }
    /**
     * Sobrescrita do método getAuthPassword do JWT pra trocar o nome da coluna 'password'
     * @return void
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }
}

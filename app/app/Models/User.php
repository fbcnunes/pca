<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Perfil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'perfil_usuario')
            ->withPivot(['unidade_id', 'ativo'])
            ->withTimestamps();
    }

    public function temPermissao(string $chave): bool
    {
        return $this->perfis()
            ->wherePivot('ativo', true)
            ->where('perfis.ativo', true)
            ->whereHas('permissoes', function ($query) use ($chave) {
                $query->where('permissoes.ativo', true)->where('permissoes.chave', $chave);
            })
            ->exists();
    }

    public function temPerfil(string $slug): bool
    {
        return $this->perfis()
            ->wherePivot('ativo', true)
            ->where('perfis.slug', $slug)
            ->exists();
    }
}

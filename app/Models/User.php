<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

// app/Models/User.php

    public static function getUniqueSigns()
    {
        return User::select('sign')
            ->whereNotNull('sign')
            ->get()
            ->map(function ($u) {

                // Decodificar el JSON
                $arr = json_decode($u->sign, true);

                // Asegurar formato ["XX","archivo.png"]
                if (is_array($arr) && count($arr) === 2) {
                    return [
                        'initials' => $arr[0],
                        'file'     => $arr[1],
                    ];
                }

                return null;
            })
            ->filter()              // quitar null
            ->unique('initials')    // que no se repitan las iniciales
            ->values();
    }

}

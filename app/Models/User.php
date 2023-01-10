<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * | Meta Lists
     */
    public function metaEmployee()
    {
        return DB::table('users as u')
            ->select(
                'u.id',
                'u.name',
                'u.email',
                'u.mobile',
                'u.gram_panchayat_id',
                'g.gram_panchayat_name'
            )
            ->leftJoin('gram_panchayat_mstrs as g', 'g.id', '=', 'u.gram_panchayat_id');
    }

    /**
     * | Get all Employees
     */
    public function getAllEmployees()
    {
        return $this->metaEmployee()
            ->where('u.is_admin', 0)
            ->orderByDesc('u.id')
            ->get();
    }

    /**
     * | Employee Dtls
     */
    public function employeeDtls($id)
    {
        return $this->metaEmployee()
            ->where('u.id', $id)
            ->first();
    }
}

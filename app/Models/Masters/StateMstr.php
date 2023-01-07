<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateMstr extends Model
{
    use HasFactory;

    public function retrieveAll()
    {
        return StateMstr::orderByDesc('id')
            ->get();
    }
}

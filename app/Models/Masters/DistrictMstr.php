<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictMstr extends Model
{
    use HasFactory;

    public function retrieveAll()
    {
        return DistrictMstr::orderByDesc('id')
            ->get();
    }
}

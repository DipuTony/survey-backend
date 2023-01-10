<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionMstr extends Model
{
    use HasFactory;

    public function getAllQuestions()
    {
        return QuestionMstr::all();
    }
}

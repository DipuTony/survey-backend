<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionMstr extends Model
{
    use HasFactory;

    public function getAllQuestions()
    {
        return QuestionMstr::select('id', 'question')
            ->get();
    }

    public function getHindiQuestions()
    {
        return QuestionMstr::select('id', 'question_hindi')
            ->get();
    }
}

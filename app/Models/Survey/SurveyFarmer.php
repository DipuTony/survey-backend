<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyFarmer extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * | Meta Requests for storation and updation
     */
    public function metaReqs($req)
    {
        return [
            'question_id' => $req->questionId,
            'answer' => $req->answer
        ];
    }

    /**
     * | Add New Survey Record
     */
    public function store($farmerId, $req)
    {
        $metaReqs = array_merge(
            $this->metaReqs($req),
            [
                'farmer_id' => $farmerId,
                'created_by' => auth()->user()->id
            ]
        );
        SurveyFarmer::create($metaReqs);
    }
}

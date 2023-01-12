<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            'village_id' => $req->villageId,
            'question_id' => $req->questionId,
            'answer' => $req->answer
        ];
    }

    /**
     * | Add New Survey Record
     */
    public function store($farmerId, $req, $villageId)
    {
        $metaReqs = array_merge(
            $this->metaReqs($req),
            [
                'village_id' => $villageId,
                'farmer_id' => $farmerId,
                'created_by' => auth()->user()->id
            ]
        );
        SurveyFarmer::create($metaReqs);
    }

    /**
     * | Get Survey Farmers
     */
    public function listSurvey()
    {
        return DB::table('survey_farmers as sf')
            ->select(
                'sf.*',
                'f.name_of_head',
                'f.age',
                'f.no_of_dependencies',
                'v.village_name',
                'g.gram_panchayat_name',
                'd.name as district_name'
            )
            ->join('farmers as f', 'f.id', '=', 'sf.farmer_id')
            ->join('village_mstrs as v', 'v.id', '=', 'sf.village_id')
            ->join('gram_panchayat_mstrs as g', 'g.id', '=', 'v.gram_panchayat_id')
            ->join('district_mstrs as d', 'd.id', '=', 'g.district_id')
            ->get();
    }
}
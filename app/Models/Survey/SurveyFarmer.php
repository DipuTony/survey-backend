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
     * | Meta Listings
     */
    public function metaLists()
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
            ->join('district_mstrs as d', 'd.id', '=', 'g.district_id');
    }
    /**
     * | Get Survey Farmers
     */
    public function listSurvey()
    {
        return $this->metaLists()
            ->get();
    }

    /**
     * | Get Surveys by employee id
     */
    public function getSurveyByEmpId($empId)
    {
        return $this->metaLists()
            ->get()
            ->groupBy('farmer_id');
    }

    /**
     * 
     */
    public function getSurveyByVillage($villageId)
    {
        $query = "SELECT
                    s.village_id,
                    s.farmer_id,
                    f.name_of_head,
                    v.village_name,
                    GROUP_CONCAT(s.answer) AS answer,
                    GROUP_CONCAT(q.question) AS questions
                    FROM survey_farmers s
                    JOIN question_mstrs q ON q.id=s.question_id
                    JOIN farmers f ON f.id=s.farmer_id
                    JOIN village_mstrs v ON v.id=s.village_id
                    WHERE s.village_id=$villageId
                GROUP BY s.farmer_id,s.village_id";
        return DB::select($query);
    }
}

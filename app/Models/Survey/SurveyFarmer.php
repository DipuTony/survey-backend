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
                'f.farmer_id as farmer_code',
                'f.name_of_head',
                'f.age',
                'f.no_of_dependencies',
                'f.longitude',
                'f.latitude',
                'v.village_name',
                'g.gram_panchayat_name',
                'd.name as district_name',
                'u.name as employee_name'
            )
            ->join('farmers as f', 'f.id', '=', 'sf.farmer_id')
            ->join('village_mstrs as v', 'v.id', '=', 'sf.village_id')
            ->join('gram_panchayat_mstrs as g', 'g.id', '=', 'v.gram_panchayat_id')
            ->join('district_mstrs as d', 'd.id', '=', 'g.district_id')
            ->join('users as u', 'u.id', '=', 'sf.created_by');
    }
    /**
     * | Get Survey Farmers
     */
    public function listSurvey()
    {
        return DB::table('farmers as f')
            ->select(
                'f.*',
                'f.farmer_id as farmer_code',
                'v.village_name',
                'd.name as district_name',
                'g.gram_panchayat_name',
                'u.name as employee_name',
            )
            ->join('village_mstrs as v', 'v.id', '=', 'f.village_id')
            ->join('gram_panchayat_mstrs as g', 'g.id', '=', 'v.gram_panchayat_id')
            ->join('district_mstrs as d', 'd.id', '=', 'g.district_id')
            ->join('users as u', 'u.id', '=', 'f.created_by')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * | Get Surveys by employee id
     */
    public function getSurveyByEmpId($empId)
    {
        return $this->metaLists()
            ->where('sf.created_by', $empId)
            ->orderByDesc('id')
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
                    u.name AS employee_name,
                    s.farmer_id,
                    f.name_of_head,
                    f.farmer_id AS farmer_code,
                    f.longitude,
                    f.latitude,
                    v.village_name,
                    GROUP_CONCAT(s.answer) AS answer,
                    GROUP_CONCAT(q.question) AS questions
                    FROM survey_farmers s
                    JOIN question_mstrs q ON q.id=s.question_id
                    JOIN farmers f ON f.id=s.farmer_id
                    JOIN village_mstrs v ON v.id=s.village_id
                    JOIN users u ON u.id=s.created_by
                    WHERE s.village_id=$villageId
                GROUP BY s.farmer_id,s.village_id,u.name";
        return DB::select($query);
    }
}

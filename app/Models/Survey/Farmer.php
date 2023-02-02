<?php

namespace App\Models\Survey;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Farmer extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * | Meta Requests for Store and Update
     */
    public function metaReqs($req)
    {
        return [
            'village_id' => $req->villageId,
            'name_of_head' => $req->nameOfHead,
            'age' => $req->age,
            'marital_status' => $req->maritalStatus,
            'no_of_dependencies' => $req->noOfDependencies,
            'longitude' => $req->longitude,
            'latitude' => $req->latitude
        ];
    }

    /**
     * | Store New Record
     */
    public function store($req)
    {
        $metaReqs = array_merge(
            $this->metaReqs($req),
            [
                'created_by' => auth()->user()->id
            ]
        );
        $farmer = Farmer::create($metaReqs);
        return $farmer;
    }

    /**
     * | Get Surveys by employee id
     */
    public function getSurveyByEmpId($empId)
    {
        return DB::table('farmers as f')
            ->select(
                'f.*',
                'f.farmer_id as farmer_code',
                'f.name_of_head',
                'f.age',
                'f.no_of_dependencies',
                'v.village_name',
                'g.gram_panchayat_name',
                'd.name as district_name'
            )
            ->join('village_mstrs as v', 'v.id', '=', 'f.village_id')
            ->join('gram_panchayat_mstrs as g', 'g.id', '=', 'v.gram_panchayat_id')
            ->join('district_mstrs as d', 'd.id', '=', 'g.district_id')
            ->where('created_by', $empId)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * | Get Total Farmers or Surveys
     */
    public function getTotalSurveys()
    {
        return Farmer::count();
    }

    /**
     * | Today Survey List
     */
    public function getTodaySurveysList()
    {
        $today = Carbon::now()->format('Y-m-d');
        return Farmer::whereDate('created_at', $today)
            ->count();
    }
}

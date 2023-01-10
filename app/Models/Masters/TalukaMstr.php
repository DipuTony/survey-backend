<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TalukaMstr extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function metaReqs($req)
    {
        return [
            'state_id' => $req->stateId,
            'district_id' => $req->districtId,
            'taluka_name' => $req->taluka_name
        ];
    }

    /**
     * | Store Function
     */
    public function store($req)
    {
        $metaReqs = $this->metaReqs($req);
        TalukaMstr::create($metaReqs);
    }

    /**
     * | Edit Taluka
     */
    public function edit($req)
    {
        $metaReqs = $this->metaReqs($req);
        $statusReq = ['status' => $req->status];    // Adding Extras Requests
        $metaReqs = array_merge($metaReqs, $statusReq);
        $taluka = TalukaMstr::find($req->id);
        $taluka->update($metaReqs);
    }

    /**
     * | Meta Taluka Lists
     */
    public function metaLists()
    {
        return  DB::table('taluka_mstrs')
            ->select(
                'taluka_mstrs.*',
                's.state_name',
                'd.name as district_name'
            )
            ->leftJoin('state_mstrs as s', 's.id', '=', 'taluka_mstrs.state_id')
            ->leftJoin('district_mstrs as d', 'd.id', '=', 'taluka_mstrs.district_id');
    }

    /**
     * | Show by id
     */
    public function show($id)
    {
        return $this->metaLists()
            ->where('taluka_mstrs.id', $id)
            ->first();
    }

    /**
     * | Get All Taluka list
     */
    public function retrieveAll()
    {
        return $this->metaLists()
            ->orderByDesc('taluka_mstrs.id')
            ->get();
    }

    /**
     * | Get Taluka by district id
     */
    public function getTalukaByDistrict($districtId)
    {
        return TalukaMstr::where('district_id', $districtId)
            ->where('status', 1)
            ->get();
    }
}

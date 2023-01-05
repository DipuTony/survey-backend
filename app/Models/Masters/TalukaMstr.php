<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * | Show by id
     */
    public function show($id)
    {
        return TalukaMstr::find($id);
    }

    /**
     * | Get All Taluka list
     */
    public function retrieveAll()
    {
        return TalukaMstr::orderByDesc('id')
            ->get();
    }
}

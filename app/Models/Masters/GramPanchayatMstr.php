<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GramPanchayatMstr extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * | Helpers make requests 
     */
    public function metaReqs($req)
    {
        return [
            'gram_panchayat_name' => $req->gram_panchayat_name,
            'taluka_id' => $req->talukaId,
            'state_id' => $req->stateId,
            'district_id' => $req->districtId
        ];
    }

    /**
     * | Add new Gram Panchayat
     */
    public function store($req)
    {
        $metaReqs = $this->metaReqs($req);
        GramPanchayatMstr::create($metaReqs);
    }

    /**
     * | Edit Existing Gram Panchayat
     */
    public function edit($req)
    {
        $metaReqs = $this->metaReqs($req);
        $statusReq = ['status' => $req->status];
        $metaReqs = array_merge($metaReqs, $statusReq);
        $gramPanchayat = GramPanchayatMstr::find($req->id);
        $gramPanchayat->update($metaReqs);
    }

    /**
     * | Meta Gram Panchayats
     */
    public function metaList()
    {
        return DB::table('gram_panchayat_mstrs')
            ->select(
                'gram_panchayat_mstrs.*',
                't.taluka_name',
                's.state_name',
                'd.name as district_name'
            )
            ->leftJoin('taluka_mstrs as t', 't.id', '=', 'gram_panchayat_mstrs.taluka_id')
            ->leftJoin('state_mstrs as s', 's.id', '=', 'gram_panchayat_mstrs.state_id')
            ->leftJoin('district_mstrs as d', 'd.id', '=', 'gram_panchayat_mstrs.district_id');
    }

    /**
     * | Get Gram Panchayat by id
     */
    public function show($id)
    {
        return $this->metaList()
            ->where('gram_panchayat_mstrs.id', $id)
            ->first();
    }

    /**
     * | Get all the Gram Panchayats
     */
    public function retrieve()
    {
        return $this->metaList()
            ->orderByDesc('gram_panchayat_mstrs.id')
            ->get();
    }

    /**
     * | Get Gram Panchayat by Taluka id
     */
    public function getByTaluka($talukaId)
    {
        return GramPanchayatMstr::where('taluka_id', $talukaId)
            ->orderByDesc('id')
            ->get();
    }
}

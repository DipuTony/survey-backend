<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VillageMstr extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function metaReqs($req)
    {
        return [
            'gram_pradhan_name' => $req->gramPradhanName,
            'gram_pradhan_mobile' => $req->gramPradhanMobile,
            'gram_panchayat_id' => $req->gramPanchayatId,
            'village_name' => $req->village_name
        ];
    }

    /**
     * | Add new Village
     */
    public function store($req)
    {
        $metaReqs = $this->metaReqs($req);
        VillageMstr::create($metaReqs);
    }

    /**
     * | Edit Village
     */
    public function edit($req)
    {
        $metaReqs = array_merge(
            $this->metaReqs($req),
            [
                'status' => $req->status
            ]
        );
        $village = VillageMstr::find($req->id);
        $village->update($metaReqs);
    }

    /**
     * | Meta Village Details
     */
    public function metaDetails()
    {
        return DB::table('village_mstrs')
            ->select(
                'village_mstrs.*',
                'g.gram_panchayat_name',
                'g.taluka_id',
                't.taluka_name',
                'g.state_id',
                's.state_name',
                'g.district_id',
                'd.name as district_name'
            )
            ->leftJoin('gram_panchayat_mstrs as g', 'g.id', '=', 'village_mstrs.gram_panchayat_id')
            ->leftJoin('taluka_mstrs as t', 't.id', '=', 'g.taluka_id')
            ->leftJoin('state_mstrs as s', 's.id', '=', 'g.state_id')
            ->leftJoin('district_mstrs as d', 'd.id', '=', 'g.district_id')
            ->where('village_mstrs.status', 1);
    }

    /**
     * | Show
     */
    public function show($id)
    {
        return $this->metaDetails()
            ->where('village_mstrs.id', $id)
            ->first();
    }

    /**
     * | Retrive all 
     */
    public function retrieve()
    {
        return $this->metaDetails()
            ->orderByDesc('id')
            ->get();
    }

    /**
     * | Get Village by panchayat id
     */
    public function getByPanchayatId($panchayatId)
    {
        return VillageMstr::where('gram_panchayat_id', $panchayatId)
            ->get();
    }

    /**
     * | Get Total Villages
     */
    public function getTotalVillages()
    {
        return VillageMstr::count();
    }
}

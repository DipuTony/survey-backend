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
}

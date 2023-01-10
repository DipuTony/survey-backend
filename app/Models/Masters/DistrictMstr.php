<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictMstr extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function retrieveAll()
    {
        return DistrictMstr::orderByDesc('id')
            ->get();
    }

    /**
     * | Meta Requests
     */
    public function metaRequest($req)
    {
        return [
            'name' => $req->name,
            'state_id' => $req->stateId,
        ];
    }

    /**
     * | Add New
     */
    public function store($req)
    {
        $metaReqs = $this->metaRequest($req);
        DistrictMstr::create($metaReqs);
    }

    /**
     * Get District by id
     */
    public function getDistrictByState($stateId)
    {
        return DistrictMstr::where('state_id', $stateId)
            ->orderByDesc('id')
            ->get();
    }
}

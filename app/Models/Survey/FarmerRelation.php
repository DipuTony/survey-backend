<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerRelation extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * | Meta Requests for create and update
     */
    public function metaReqs($req)
    {
        return [
            'relative_name' => $req->name,
            'age' => $req->age,
            'relation' => $req->relation
        ];
    }

    /**
     * | Add a New Record
     */
    public function store($farmerId, $req)
    {
        $metaReqs = array_merge(
            $this->metaReqs($req),
            [
                'farmer_id' => $farmerId
            ]
        );
        FarmerRelation::create($metaReqs);
    }
}

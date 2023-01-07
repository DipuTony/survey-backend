<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return Farmer::create($metaReqs)->id;
    }
}

<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\TalukaMstr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TalukaController extends Controller
{
    /**
     * | Store Taluka in DB
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'stateId' => 'required|integer',
            'districtId' => 'required|integer',
            'taluka_name' => [
                'required',
                'string',
                Rule::unique('taluka_mstrs')
                    ->where('district_id', $req->districtId)
                    ->where('state_id', $req->stateId)
                    ->where('status', 1)
            ]
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $mTalukaMstr = new TalukaMstr();
            $mTalukaMstr->store($req);
            return responseMsg(true, "Successfully Saved The Taluka", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Update
     */
    public function edit(Request $req)
    {
        try {
            dd($req->all);
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Get All Taluka
     */
    public function retrieveAll()
    {
        try {
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

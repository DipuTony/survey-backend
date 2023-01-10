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
    protected $_modelObj;
    public function __construct()
    {
        $this->_modelObj = new TalukaMstr();
    }
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
            $validator = Validator::make($req->all(), [
                'stateId' => 'required|integer',
                'districtId' => 'required|integer',
                'taluka_name' => [
                    'required',
                    'string',
                    Rule::unique('taluka_mstrs')
                        ->where('district_id', $req->districtId)
                        ->where('status', 1)
                        ->ignore($req->id)
                ],
                'status' => 'required|bool',
            ]);
            if ($validator->fails()) {
                return responseMsg(false, $validator->errors(), "");
            }
            $mTalukaMstr = new TalukaMstr();
            $mTalukaMstr->edit($req);
            return responseMsg(true, "Successfully Updated the Taluka", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Get Taluka By id
     */
    public function show(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $mTalukaMstr = $this->_modelObj;
            $taluka = $mTalukaMstr->show($req->id);
            return responseMsg(true, "Taluka Details", remove_null($taluka));
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
            $mTalukaMstr = $this->_modelObj;
            $talukas = $mTalukaMstr->retrieveAll();
            return responseMsg(true, "All Talukas", remove_null($talukas));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Get Taluka List by District Id
     */
    public function getTalukaByDistrict(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'districtId' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $taluka = $this->_modelObj;
            $taluka = $taluka->getTalukaByDistrict($req->districtId);
            return responseMsg(true, "", remove_null($taluka));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\VillageMstr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VillageController extends Controller
{
    //
    protected $_modelObj;
    public function __construct()
    {
        $this->_modelObj = new VillageMstr();
    }

    /**
     * | Store new Village
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'gramPradhanName' => 'required|string',
            'gramPradhanMobile' => 'required|numeric|digits:10',
            'gramPanchayatId' => 'required|integer',
            'village_name' => [
                'required',
                Rule::unique('village_mstrs')
                    ->where('gram_panchayat_id', $req->gramPanchayatId)
                    ->where('status', 1)
            ]
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $village = $this->_modelObj;
            $village->store($req);
            return responseMsg(true, "Successfully Saved The Village", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }


    /**
     * | Edit
     */
    public function edit(Request $req)
    {
        // Validation
        $validator = Validator::make($req->all(), [
            'gramPradhanName' => 'required|string',
            'gramPradhanMobile' => 'required|numeric|digits:10',
            'gramPanchayatId' => 'required|integer',
            'status' => 'required|bool',
            'village_name' => [
                'required',
                Rule::unique('village_mstrs')
                    ->where('gram_panchayat_id', $req->gramPanchayatId)
                    ->where('status', 1)
                    ->ignore($req->id)
            ]
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        try {
            $village = $this->_modelObj;
            $village->edit($req);
            return responseMsg(true, "Successfully Updated The Village", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Get Village by Id
     */
    public function show(Request $req)
    {
        // Validation
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        try {
            $village = $this->_modelObj;
            $details = $village->show($req->id);
            return responseMsg(true, "Village Details", remove_null($details));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Retrieve all Villages
     */
    public function retrieve()
    {
        try {
            $village = $this->_modelObj;
            $details = $village->retrieve();
            return responseMsg(true, "Villages", remove_null($details->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Get Villages by Panchayat id
     */
    public function getByPanchayat(Request $req)
    {
        // Validation
        $validator = Validator::make($req->all(), [
            'panchayatId' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        // Logics
        try {
            $village = $this->_modelObj;
            $villages = $village->getByPanchayatId($req->panchayatId);
            return responseMsg(true, "Villages", remove_null($villages->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

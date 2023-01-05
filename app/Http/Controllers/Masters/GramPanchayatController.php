<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\GramPanchayatMstr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GramPanchayatController extends Controller
{
    protected $_modelObj;
    public function __construct()
    {
        $this->_modelObj = new GramPanchayatMstr();
    }

    /**
     * | Store
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'talukaId' => 'required|integer',
            'districtId' => 'required|integer',
            'stateId' => 'required|integer',
            'gram_panchayat_name' => [
                'required',
                'string',
                Rule::unique('gram_panchayat_mstrs')
                    ->where('district_id', $req->districtId)
                    ->where('state_id', $req->stateId)
                    ->where('taluka_id', $req->talukaId)
                    ->where('status', 1)
            ]
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        try {
            $mGramPanchayat = $this->_modelObj;
            $mGramPanchayat->store($req);
            return responseMsg(true, "Successfully Saved the Gram Panchayat", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Edit Gram Panchayat
     */
    public function edit(Request $req)
    {
        // Validation
        $validator = Validator::make($req->all(), [
            'talukaId' => 'required|integer',
            'districtId' => 'required|integer',
            'stateId' => 'required|integer',
            'status' => 'required|bool',
            'gram_panchayat_name' => [
                'required',
                'string',
                Rule::unique('gram_panchayat_mstrs')
                    ->where('district_id', $req->districtId)
                    ->where('state_id', $req->stateId)
                    ->where('taluka_id', $req->talukaId)
                    ->where('status', 1)
                    ->ignore($req->id)
            ]
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        // Logics
        try {
            $mGramPanchayat = $this->_modelObj;
            $mGramPanchayat->edit($req);
            return responseMsg(true, "Successfully Updated The Gram Panchayat", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Show Gram Panchayat by Id
     */
    public function show(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $mGramPanchayat = $this->_modelObj;
            $gramPanchayat = $mGramPanchayat->show($req->id);
            return responseMsg(true, "Gram Panchayat", remove_null($gramPanchayat));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | retrieve all gram panchayats
     */
    public function retrieve()
    {
        try {
            $mGramPanchayat = $this->_modelObj;
            $gramPanchayats = $mGramPanchayat->retrieve();
            return responseMsg(true, "Gram Panchayats", remove_null($gramPanchayats));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\DistrictMstr;
use App\Models\Masters\StateMstr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DistrictController extends Controller
{
    protected $_modelObj;
    public function __construct()
    {
        $this->_modelObj = new DistrictMstr();
    }

    // Get All States
    public function retriveStates()
    {
        try {
            $state = new StateMstr();
            $states = $state->retrieveAll();
            return responseMsg(true, "", remove_null($states->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    // Get All Districts
    public function retriveAll()
    {
        try {
            $district = $this->_modelObj;
            $districts = $district->retrieveAll();
            return responseMsg(true, "", remove_null($districts->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Add new District
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => [
                'required',
                Rule::unique('district_mstrs')
                    ->where('state_id', $req->stateId)
            ],
            'stateId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        try {
            $district = $this->_modelObj;
            $district->store($req);
            return responseMsg(true, "Successfully Saved the District", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Get District by State
     */
    public function getDistrictByState(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'stateId' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $district = $this->_modelObj;
            $district = $district->getDistrictByState($req->stateId);
            return responseMsg(true, "", remove_null($district->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

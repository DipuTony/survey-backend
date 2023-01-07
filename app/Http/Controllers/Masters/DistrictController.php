<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\DistrictMstr;
use App\Models\Masters\StateMstr;
use Exception;
use Illuminate\Http\Request;

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
}

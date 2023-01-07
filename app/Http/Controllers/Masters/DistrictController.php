<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\DistrictMstr;
use Exception;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    protected $_modelObj;
    public function __construct()
    {
        $this->_modelObj = new DistrictMstr();
    }

    //
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

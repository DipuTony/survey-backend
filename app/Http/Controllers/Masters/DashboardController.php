<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\VillageMstr;
use App\Models\Survey\Farmer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboardData()
    {
        try {
            $mFarmers = new Farmer();
            $mUsers = new User();
            $mVillages = new VillageMstr();
            $data = array();

            $totalSurveys = $mFarmers->getTotalSurveys();
            $todaySurveys = $mFarmers->getTodaySurveysList();
            $noOfEmployees = $mUsers->getTotalEmployees();
            $totalVillages = $mVillages->getTotalVillages();

            $data['totalSurveys'] = collect($totalSurveys)->first();
            $data['todaySurveys'] = $todaySurveys;
            $data['totalEmployees'] =  $noOfEmployees;
            $data['totalVillages'] =  $totalVillages;

            return responseMsg(true, "Dashboard List Data", $data);
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Models\Survey\Farmer;
use App\Models\Survey\FarmerRelation;
use App\Models\Survey\SurveyFarmer;
use Exception;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * | Add New Survey
     */
    public function store(SurveyRequest $req)
    {
        try {
            $farmer = new Farmer();
            $farmerRelation = new FarmerRelation();
            $surveyFarmer = new SurveyFarmer();
            $villageId = $req->villageId;
            $farmerGetId = $farmer->store($req);
            // Add Farmer Relation
            collect($req->relations)->map(function ($relation) use ($farmerGetId, $farmerRelation) {
                $relation = new Request($relation);
                $farmerRelation->store($farmerGetId, $relation);
            });

            // Add Survey Record
            collect($req->questions)->map(function ($question) use ($farmerGetId, $surveyFarmer, $villageId) {
                $question = new Request($question);
                $surveyFarmer->store($farmerGetId, $question, $villageId);
            });
            return responseMsg(
                true,
                "Survey Successfully Done",
                ""
            );
        } catch (Exception $e) {
            return responseMsg(
                false,
                $e->getMessage(),
                ""
            );
        }
    }

    /**
     * | List of Surveys
     */
    public function listSurvey()
    {
        try {
            $mSurveyFarmer = new SurveyFarmer();
            $survey = $mSurveyFarmer->listSurvey();
            return responseMsg(true, "", remove_null($survey));
        } catch (Exception $e) {
            return responseMsg(
                false,
                $e->getMessage(),
                ""
            );
        }
    }

    /**
     * | Get Surveys by Employee ID
     */
    public function getSurveyByEmployee()
    {
        try {
            $mSurveyFarmer = new SurveyFarmer();
            $employeeId = auth()->user()->id;
            $surveys = $mSurveyFarmer->getSurveyByEmpId($employeeId)->values();
            $surveys = $surveys->collapse();
            return responseMsg(true, "", remove_null($surveys));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

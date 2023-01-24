<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Models\Survey\Farmer;
use App\Models\Survey\FarmerRelation;
use App\Models\Survey\SurveyFarmer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    /**
     * | Get all Surveys by Village
     */
    public function getSurveyByVillage(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "villageId" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        try {
            $villageId = $req->villageId;
            $mSurveyFarmer = new SurveyFarmer();
            $surveys = $mSurveyFarmer->getSurveyByVillage($villageId);
            $explode = collect($surveys)->map(function ($survey) {
                $survey->answer = explode(',', $survey->answer);
                $survey->questions = explode(',', $survey->questions);
                return $survey;
            });
            $mergeQueAns = collect($explode)->map(function ($obj) {
                $obj->summary = collect($obj->questions)->combine($obj->answer);
                return $obj;
            });
            return responseMsg(true, "", $mergeQueAns);
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

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

            $farmerGetId = $farmer->store($req);
            // Add Farmer Relation
            collect($req->relations)->map(function ($relation) use ($farmerGetId, $farmerRelation) {
                $relation = new Request($relation);
                $farmerRelation->store($farmerGetId, $relation);
            });

            // Add Survey Record
            collect($req->questions)->map(function ($question) use ($farmerGetId, $surveyFarmer) {
                $question = new Request($question);
                $surveyFarmer->store($farmerGetId, $question);
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
}

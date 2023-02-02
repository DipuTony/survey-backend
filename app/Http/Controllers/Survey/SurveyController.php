<?php

namespace App\Http\Controllers\Survey;

use App\Exports\ExportSurvey;
use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Models\Survey\Farmer;
use App\Models\Survey\FarmerRelation;
use App\Models\Survey\SurveyFarmer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
            DB::beginTransaction();
            $farmerFields = $farmer->store($req);
            $farmerGetId = $farmerFields->id;
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
            DB::commit();
            return responseMsg(
                true,
                "Survey Successfully Done",
                [
                    "id" => $farmerFields->farmer_id
                ]
            );
        } catch (Exception $e) {
            DB::rollBack();
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
            return responseMsg(true, "", remove_null($survey->toArray()));
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
            $mFarmer = new Farmer();
            $employeeId = auth()->user()->id;
            $surveys = $mFarmer->getSurveyByEmpId($employeeId);
            return responseMsg(true, "", remove_null($surveys->toArray()));
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
                return collect($obj)->only([
                    'name_of_head',
                    'farmer_code',
                    'employee_name',
                    'village_name',
                    'longitude',
                    'latitude',
                    'summary'
                ]);
            });
            return responseMsg(true, "", remove_null($mergeQueAns->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Export to Excel
     */
    public function exportToExcel()
    {
        return Excel::download(new ExportSurvey, 'survey.xlsx');
    }
}

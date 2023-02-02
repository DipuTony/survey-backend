<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\QuestionMstr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    //
    protected $_modelObj;
    public function __construct()
    {
        $this->_modelObj = new QuestionMstr();
    }

    /**
     * | Get All Questions
     */
    public function getAllQuestions(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'language' => 'required'
        ]);
        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $question = $this->_modelObj;
            if ($req->language == 'English')
                $questions = $question->getAllQuestions();
            elseif ($req->language == 'Hindi')
                $questions = $question->getHindiQuestions();
            else
                throw new Exception("Language Not Available");
            return responseMsg(true, "", remove_null($questions->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

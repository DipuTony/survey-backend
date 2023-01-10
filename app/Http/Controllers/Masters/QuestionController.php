<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\QuestionMstr;
use Exception;
use Illuminate\Http\Request;

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
    public function getAllQuestions()
    {
        try {
            $question = $this->_modelObj;
            $questions = $question->getAllQuestions();
            return responseMsg(true, "", remove_null($questions->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}

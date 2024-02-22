<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizResult;

class QuizCtrl extends Controller
{
    public function index()
    {
        $quiz = Quiz::with('questions', 'questions.answers')->orderBy('created_at', 'desc')->get();
        return response()->json($quiz, 200);
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions', 'questions.answers')->find($id);
        return response()->json($quiz, 200);
    }

    public function store(Request $request)
    {
        $random6Number = mt_rand(100000, 999999);
        $request->merge(['id' => "APAAN-" . $random6Number]);
        $quiz = Quiz::create($request->all());
        return response()->json([
            'status_code' => 201, // 'status_code' => '201'
            'message' => 'Quiz created successfully',
            'quiz' => $quiz
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        $quiz->update($request->all());
        return response()->json([
            'status_code' => 200, // 'status_code' => '200'
            'message' => 'Quiz updated successfully',
            'quiz' => $quiz
        ], 200);
    }

    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        $quiz->delete();
        return response()->json([
            'status_code' => 200, // 'status_code' => '200'
            'message' => 'Quiz deleted successfully',
            'quiz' => $quiz
        ], 200);
    }

    // QUESTION
    public function createQuestion(Request $request)
    {
        $question = Question::create($request->all());
        return response()->json([
            'status_code' => 201, // 'status_code' => '201'
            'message' => 'Question created successfully',
            'question' => $question
        ], 201);
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        $question->update($request->all());
        return response()->json([
            'status_code' => 200, // 'status_code' => '200'
            'message' => 'Question updated successfully',
            'question' => $question
        ], 200);
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        $question->delete();
        Answer::where('question_id', $id)->delete();
        return response()->json([
            'status_code' => 200, // 'status_code' => '200'
            'message' => 'Question deleted successfully',
            'question' => $question
        ], 200);
    }

    // ANSWER
    public function createAnswer(Request $request)
    {
        $answer = Answer::create($request->all());
        return response()->json([
            'status_code' => 201, // 'status_code' => '201'
            'message' => 'Answer created successfully',
            'answer' => $answer
        ], 201);
    }

    public function updateAnswer(Request $request, $id)
    {
        $answer = Answer::find($id);
        $answer->update($request->all());
        return response()->json([
            'status_code' => 200, // 'status_code' => '200'
            'message' => 'Answer updated successfully',
            'answer' => $answer
        ], 200);
    }

    public function deleteAnswer($id)
    {
        $answer = Answer::find($id);
        $answer->delete();
        return response()->json([
            'status_code' => 200, // 'status_code' => '200'
            'message' => 'Answer deleted successfully',
            'answer' => $answer
        ], 200);
    }

    // QUIZ SUBMIT
    public function submitQuiz(Request $request)
    {
        $answers = $request->answers;
        $questions = $request->questions;
        $correctQuestions = [];
        $incorrectQuestions = [];
        $choosenAnswer = $request->answers;

        if (count($request->questions) > 0 && count($request->answers) > 0) {
            for ($i = 0; $i < count($questions); $i++) {
                $question = $questions[$i];
                $answer = $answers[$i];

                if ($question['correct_answer'] == $answer["id"]) {
                    array_push($correctQuestions, $question);
                } else {
                    array_push($incorrectQuestions, $question);
                }
            }
        }

        $grade = count($correctQuestions) * 2;

        $quizResult = QuizResult::create([
            'user_id' => $request->user_id,
            'quiz_id' => $request->quiz_id,
            'grade' => $grade,
            'correct_questions' => $correctQuestions,
            'wrong_questions' => $incorrectQuestions,
            'choosen_answer' => $choosenAnswer
        ]);

        return response()->json([
            'status_code' => 201, // 'status_code' => '201'
            'message' => 'Quiz has been submitted successfully',
            'quiz_result' => $quizResult
        ], 201);
    }

    // QUIZ RESULT LIST
    public function quizResultList()
    {
        $quizResult = QuizResult::with('quiz', 'user')->get();
        return response()->json($quizResult, 200);
    }
}

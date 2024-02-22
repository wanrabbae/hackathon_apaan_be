<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViolationResult;

class ViolationCtrl extends Controller
{
    public function store(Request $request)
    {
        // check data already exist
        if ($request->quiz_id && $request->user_id) {

            $data = ViolationResult::where('quiz_id', $request->quiz_id)->where('user_id', $request->user_id)->first();

            if ($data) {

                switch ($request->violation_type) {
                    case 'missing_eye_contact':
                        $data->missing_eye_contact = $data->missing_eye_contact + 1;
                        break;
                    case 'missing_participant':
                        $data->missing_participant = $data->missing_participant + 1;
                        break;
                    case 'multiple_participants':
                        $data->multiple_participants = $data->multiple_participants + 1;
                        break;
                    case 'unvisible_state':
                        $data->unvisible_state = $data->unvisible_state + 1;
                        break;

                    default:
                        return response()->json(['error' => 'Violation type not found'], 404);
                        break;
                }
                $data->save();
            } else {

                // create new data
                $data = new ViolationResult;
                $data->quiz_id = $request->quiz_id;
                $data->user_id = $request->user_id;
                switch ($request->violation_type) {
                    case 'missing_eye_contact':
                        $data->missing_eye_contact = 1;
                        break;
                    case 'missing_participant':
                        $data->missing_participant = 1;
                        break;
                    case 'multiple_participants':
                        $data->multiple_participants = 1;
                        break;
                    case 'unvisible_state':
                        $data->unvisible_state = 1;
                        break;

                    default:
                        return response()->json(['error' => 'Violation type not found'], 404);
                        break;
                }
                $data->save();
            }
            return response()->json($data, 201);
        } else {
            return response()->json(['error' => 'Data not found'], 404);
        }
    }

    public function show($quiz_id)
    {
        $data = ViolationResult::with('quiz', 'user')->where('quiz_id', $quiz_id)->get();
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Data not found'], 404);
        }
    }
}

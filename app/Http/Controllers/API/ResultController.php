<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Result;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function __construct()
    { 
        $this->middleware('auth:api', ['only' => ['index','show']]);
        $this->middleware('isAdmin', ['only' => ['store','update','destroy']]);
        $this->middleware('isHod', ['only' => ['show','approveResult']]);
        $this->middleware('isDean', ['only' => ['show','approveResult']]);
        $this->middleware('isExam', ['only' => ['publishResult','computeResult']]);
    }

    public function approveResult(Request $request, $id){ 
        $validator = Validator::make($request->all(), [            
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }
        try {
            $result = Result::findOrFail($id);
            $result->fill($request->all());
            $result->save();
    
            $data['status'] = "Success";
            $data['message'] = $request->all();
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    public static function computeResult($id){ 
        
       
    }

    public static function publishResult(Request $request, $id){ 
        
        $validator = Validator::make($request->all(), [            
            'publish' => 'required|string'
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }
        try {
            $result = Result::findOrFail($id);
            $result->fill($request->all());
            $result->save();
    
            $data['status'] = "Success";
            $data['message'] = $request->all();
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Result::all();

        return response()->json($results->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'submission' => 'required|string',
            'course_id' => 'required|numeric',
            'student_id' => 'required|numeric',    
            'score' => 'required|numeric',
            'grade' => 'required|string|max:2',
            'grade_point' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
           $data['status'] = "Failed";
           $data['message'] = $validator->errors();
           return response()->json($data);
        }
       
        /*Result::create([
            'submission' => $request->input('submission')
        ]);*/
        $result = new Result;
        $result->fill($request->all());
        $result->save();

        $data['status'] = "Success";
        $data['message'] = $request->all();

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Result::findOrFail($id);
        $data['status'] = "Success";
        $data['message'] = $result;
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $result = Result::findOrFail($id);
            $result->fill($request->all());
            $result->save();
    
            $data['status'] = "Success";
            $data['message'] = $request->all();
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = Result::findOrFail($id);
            $result->delete();
    
            $data['status'] = "Success";
            $data['message'] = $result;
    
            return response()->json($data);

        } catch (Exception $e) {
            $data['status'] = "Error";
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

   
}

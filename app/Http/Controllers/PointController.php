<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PointController extends Controller
{
    //
    public function add_point(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' =>'required|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }

        $point = Point::create([
            'name'=>$request['name'],
            'latitude'=>$request['latitude'],
            'longitude'=>$request['longitude'],
            'user_id'=>Auth::user()->id,
        ]);
        return json_encode(["msg" => "Point added",'id'=>$point->id]);

    }

    public function map(){

        $points = auth()->user()->points()->get();

        return view('pages.map',compact('points'));
    }

    public function get_points(){

        $points = auth()->user()->points()->get();
        return json_encode(['points'=>$points]);
    }
    public function delete_point($id){

        $point = Point::findOrFail($id);
        if (! Gate::allows('owner-point', $point)) {
            abort(403);
        }
        $point->delete();
        return json_encode(["msg" => "Point deleted","id"=>$id]);
    }
    public function edit_point(Request $request,$id){

        $point = Point::findOrFail($id);
        if (! Gate::allows('owner-point', $point)) {
            abort(403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' =>'required|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }
        $point->update([
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
            'name' => $request['name'],

        ]);
        return json_encode(["msg" => "Saved","id"=>$id]);
    }
}

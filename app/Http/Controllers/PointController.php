<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    //
    public function add_point(Request $request){

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
        $point->delete();
        return json_encode(["msg" => "Point deleted","id"=>$id]);
    }
    public function edit_point(Request $request,$id){

        $point = Point::findOrFail($id);
        $point->update([
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
            'name' => $request['name'],

        ]);
        return json_encode(["msg" => "Saved","id"=>$id]);
    }
}

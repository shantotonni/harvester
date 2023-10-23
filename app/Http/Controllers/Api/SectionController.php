<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Section\SectionStoreRequest;
use App\Http\Requests\Section\SectionUpdateRequest;
use App\Http\Resources\Section\SectionCollection;
use App\Models\Section;
use Illuminate\Support\Facades\Request;

class SectionController extends Controller
{
   public function index(){

       $sections = Section::where('type','Harvester')->orwhere('type','Both')->paginate(15);

       return new SectionCollection($sections);
   }

    public function store(SectionStoreRequest $request)
    {
        $section = new Section();
        $section->name = $request->section_name;
        $section->code = $request->code;
        $section->type = $request->type;
        $section->save();
        return response()->json(['message' => 'Section created Successfully', 200]);
    }


    public function update(SectionUpdateRequest $request, $id)
    {
        $section = Section::where('id', $id)->first();
        $section->name = $request->section_name;
        $section->code = $request->code;
        $section->type = $request->type;
        $section->save();
        return response()->json(['message' => 'Section updated Successfully', 200]);
    }

    public function destroy($id)
    {
        Section::where('id', $id)->delete();
        return response()->json(['message' => 'Section Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new SectionCollection(Section::Where('name', 'like', "%$query%")->latest()->paginate(10));
    }
}

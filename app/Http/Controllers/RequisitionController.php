<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Resources\RequisitionResource;
use App\Item;
use App\ItemType;
use App\Part;
use App\Priority;
use App\Project;
use App\Requisition;
use App\RequisitionType;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'requisitions' => RequisitionResource::collection(Requisition::all()),
            'requisition_types' => RequisitionType::all(),
            'projects' => Project::all(),
            'departments' => Department::all(),
            'item_types' => ItemType::all(),
            'items' => Part::all(),
            'priorities' => Priority::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = Requisition::count() +1;
        $request['req_no'] = 'REQ00'.$req;
        $requisition = Requisition::create($request->all());
        return response()->json(new RequisitionResource($requisition));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        Requisition::find($id)->update($request->except(['date_requested','project','person_requested']));
        return response()->json(new RequisitionResource(Requisition::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Requisition::destroy($id);
    }
}

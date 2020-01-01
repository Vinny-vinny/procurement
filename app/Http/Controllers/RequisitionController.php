<?php

namespace App\Http\Controllers;

use App\Department;
use App\DepartmentBudget;
use Carbon\Carbon;
use App\Http\Resources\DepartmentBudgetResource;
use App\Http\Resources\RequisitionResource;
use App\Machine;
use App\Part;
use App\Priority;
use App\Project;
use App\Requisition;
use App\RequisitionType;
use App\Uom;
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
            'budgets' => DepartmentBudgetResource::collection(DepartmentBudget::all()),           
            'priorities' => Priority::all(),
            'stock_items' => Part::all(),
            'assets' => Machine::all(),
            'uoms' => Uom::all()
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
        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
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

//requistions between dates
    public function customRequisitions(Request $request){
        $from = Carbon::parse($request->from)->startOfDay()->format('Y-m-d H:m:s');
        $to = Carbon::parse($request->to)->endOfDay()->format('Y-m-d H:m:s');    
        $requsitions = RequisitionResource::collection(Requisition::whereBetween('req_date',[$from,$to])->get());
        return response()->json($requsitions);

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
        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
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

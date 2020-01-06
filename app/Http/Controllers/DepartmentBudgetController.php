<?php

namespace App\Http\Controllers;

use App\Department;
use App\DepartmentBudget;
use App\ItemType;
use App\Machine;
use App\Part;
use App\Http\Resources\DepartmentBudgetResource;
use Illuminate\Http\Request;

class DepartmentBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
           'budgets' => DepartmentBudgetResource::collection(DepartmentBudget::all()),
           'item_types' => ItemType::all(),
           'stock_items' => Part::all(),
           'assets' => Machine::all(),
           'departments' => Department::all()
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
        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
        $request['ends_on'] = $request->get('ending_date'); 
        $request['end_date'] = $request->get('fr_end_date'); 
        $budget = DepartmentBudget::create($request->except(['ending_date','fr_end_date']));
        return response()->json(new DepartmentBudgetResource($budget));
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

        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
        $request['ends_on'] = $request->get('ending_date'); 
        $request['end_date'] = $request->get('fr_end_date'); 
        DepartmentBudget::find($id)->update($request->except(['department','start_date_fr','end_date_fr','total','ending_date','fr_end_date']));
        return response()->json(new DepartmentBudgetResource(DepartmentBudget::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DepartmentBudget::destroy($id);
        return response()->json($id);
    }
}

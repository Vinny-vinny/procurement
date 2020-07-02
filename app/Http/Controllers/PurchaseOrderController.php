<?php

namespace App\Http\Controllers;
use App\Http\Resources\PurchaseOrderResource;
use App\Http\Resources\QuotationResource;
use App\PurchaseOrder;
use App\Quotation;
use App\Part;
use App\Machine;
use App\Supplier;
use App\Service;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(PurchaseOrderResource::collection(PurchaseOrder::all()));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $po_no = PurchaseOrder::count()+1;
        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
        $request['item_service'] = json_encode($request->get('item_service'));
        $request['po_no'] = 'LPO00'.$po_no;
        $po = PurchaseOrder::create($request->all());
        return response()->json(new PurchaseOrderResource($po));

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
     $request['item_asset'] = json_encode($request->get('item_asset'));
     $request['item_stock'] = json_encode($request->get('item_stock'));
     $request['item_service'] = json_encode($request->get('item_service'));
     PurchaseOrder::find($id)->update($request->except('date_requested'));
     return response()->json(new PurchaseOrderResource(PurchaseOrder::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PurchaseOrder::destroy($id);
        return response()->json($id);
    }
}

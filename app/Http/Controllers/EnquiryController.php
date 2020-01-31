<?php

namespace App\Http\Controllers;
use App\Enquiry;
use App\Supplier;
use App\Machine;
use App\Part;
use App\Uom;
use App\Service;
use App\Http\Resources\EnquiryResource;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'enquiries' => EnquiryResource::collection(Enquiry::all()),
            'suppliers' => Supplier::all(),
            'assets' => Machine::all(),
            'stocks' => Part::all(),
            'uoms' => Uom::all(),
            'services' => Service::all()
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
        $enquiry_no = Enquiry::count()+1;
        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
        $request['supplier_id'] = json_encode($request->get('supplier_id'));
        $request['item_service'] = json_encode($request->get('item_service'));
         $request['enquiry_no'] = 'RFQ00'.$enquiry_no;
        $enquiry = Enquiry::create($request->all());
        return response()->json(new EnquiryResource($enquiry));
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
         $request['supplier_id'] = json_encode($request->get('supplier_id'));
         $request['item_service'] = json_encode($request->get('item_service'));
         Enquiry::find($id)->update($request->except('date_requested'));
        return response()->json(new EnquiryResource(Enquiry::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Enquiry::destroy($id);
        return response()->json($id);
    }
}

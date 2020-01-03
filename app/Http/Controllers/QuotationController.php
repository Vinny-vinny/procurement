<?php
namespace App\Http\Controllers;
use App\Quotation;
use App\Http\Resources\QuotationResource;
use App\Supplier;
use App\Enquiry;
use App\Http\Resources\EnquiryResource;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'quotations' => QuotationResource::collection(Quotation::all()),
            'suppliers' => Supplier::all(),
            'enquiries' => EnquiryResource::collection(Enquiry::all())
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
        $quotation = Quotation::create($request->all());
        return response()->json(new QuotationResource($quotation));
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
        Quotation::find($id)->update($request->all());
        return response()->json(new QuotationResource(Quotation::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Quotaion::destroy($id);
        return response()->json($id);
    }
}

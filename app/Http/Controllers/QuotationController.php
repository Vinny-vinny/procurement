<?php
namespace App\Http\Controllers;
use App\Quotation;
use App\Http\Resources\QuotationResource;
use App\Supplier;
use App\Enquiry;
use App\Part;
use App\Machine;
use App\Service;
use App\Http\Resources\EnquiryResource;
use App\User;
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
            'filtered_quotations' => QuotationResource::collection(Quotation::whereBetween('created_at',[date('Y').'-01-01',date('Y').'-12-31'])->get())
        ]);
//        return response()->json([
//            'quotations' => QuotationResource::collection(Quotation::all()),
//            'suppliers' => Supplier::all(),
//            'enquiries' => EnquiryResource::collection(Enquiry::all()),
//            'stock_items' => Part::all(),
//            'assets' => Machine::all(),
//            'services' => Service::all(),
//            'filtered_enquiries' => EnquiryResource::collection(Enquiry::whereBetween('created_at',[date('Y').'-01-01',date('Y').'-12-31'])->get()),
//             'filtered_quotations' => QuotationResource::collection(Quotation::whereBetween('created_at',[date('Y').'-01-01',date('Y').'-12-31'])->get())
//        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quote_no = Quotation::count()+1;
        $request['quote_no'] = 'Q00'.$quote_no;
        $request['item_stock'] = json_encode($request->get('item_stock'));
        $request['item_asset'] = json_encode($request->get('item_asset'));
        $request['item_service'] = json_encode($request->get('item_service'));
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
        $request['item_service'] = json_encode($request->get('item_service'));
        Quotation::find($id)->update($request->except(['quotation_date','supplier']));
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

<?php

namespace App\Http\Controllers;
use App\Enquiry;
use App\Supplier;
use App\Machine;
use App\Part;
use App\Uom;
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
            'enquiries' => Enquiry::all(),
            'suppliers' => Supplier::all(),
            'assets' => Machine::all(),
            'stocks' => Part::all(),
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
        $enquiry = Enquiry::create($request->all());
        return response()->json($enquiry);
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
        Enquiry::find($id)->update($request->all());
        return response()->json(Enquiry::find($id));
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

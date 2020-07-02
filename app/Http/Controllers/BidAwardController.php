<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BidAward;
use App\AssetDisposal;
use App\Http\Resources\AssetDisposalResource;
use App\Http\Resources\BiddingResource;
use App\Http\Resources\BidAwardResource;
use App\Bidding;
use App\Machine;
use App\User;

class BidAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(BidAwardResource::collection(BidAward::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['asset_details'] = json_encode($request->get('awarded'));
        $award_no = BidAward::count()+1;
        $request['ref_no'] = 'AWD00'.$award_no;
        $award = BidAward::create($request->except('awarded'));
        AssetDisposal::find($request->get('disposal_id'))->update(['status'=>0]);
        return response()->json($award);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

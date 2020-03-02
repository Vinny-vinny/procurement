<?php

namespace App\Http\Controllers;
use App\Machine;
use App\AssetDisposal;
use App\Bidding;
use App\PaymentMode;
use App\Http\Resources\AssetDisposalResource;
use App\Http\Resources\BiddingResource;
use Illuminate\Http\Request;

class BiddingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'assets' => Machine::all(),
            'disposals' => AssetDisposalResource::collection(AssetDisposal::all()),
            'payment_modes' => PaymentMode::all(),
            'biddings' => BiddingResource::collection(Bidding::all())
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
        $bid_count = Bidding::count()+1;
        $request['bid_no'] = 'Bid00'.$bid_count;
        $request['asset_details'] = json_encode($request->get('asset_details')); 
        $bid = Bidding::create($request->all());
       return response()->json($bid);
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

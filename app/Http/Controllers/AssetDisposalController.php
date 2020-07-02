<?php

namespace App\Http\Controllers;
use App\AssetDisposal;
use App\Machine;
use App\Bidding;
use App\Http\Resources\AssetDisposalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AssetDisposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(AssetDisposalResource::collection(AssetDisposal::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $assets = [];
        foreach ($request->get('asset_details') as $value) {
          if ($value['picture']) {
            $image = $value['picture'];
            $name =  date('s') . gettimeofday()['usec'].'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(public_path('uploads/').$name);
            $assets[] = [
            'asset_id' => $value['asset_id'],
            'amount' => $value['amount'],
            'picture' => $name
            ];
            }
        }

        $disposal_no = AssetDisposal::count()+1;
        $request['asset_details'] = json_encode($assets);
        $request['disposal_no'] = 'Dis00'.$disposal_no;
        $disposal = AssetDisposal::create($request->all());
        return response()->json(new AssetDisposalResource($disposal));
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

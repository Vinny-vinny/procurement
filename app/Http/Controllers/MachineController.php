<?php

namespace App\Http\Controllers;

use App\FuelBalance;
use App\FuelType;
use App\Http\Resources\MachineResource;
use App\Machine;
use App\SageAsset;
use App\TrackBy;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Machine::all());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $m = Machine::create($request->all());
        return response()->json($m);
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
        Machine::find($id)->update($request->all());
        return response()->json(Machine::find($id));
    }
    public function importMachines()
    {
         $assets = SageAsset::join('_btblFAAssetType','_btblFAAsset.iAssetTypeNo','=','_btblFAAssetType.idAssetTypeNo')
            ->join('_btblFAAssetSerialNo','_btblFAAsset.cAssetCode','=','_btblFAAssetSerialNo.cAssetCode')
        ->select("_btblFAAsset.idAssetNo","_btblFAAsset.cAssetCode","_btblFAAsset.cAssetDesc"
,"_btblFAAsset.fInsuredValue",'_btblFAAssetSerialNo.cSerialNo')
       //     ->where("_btblFAAsset.iAssetTypeNo", 2)
       //     ->orWhere("_btblFAAsset.iAssetTypeNo", 7)
            ->get();

        $existing = Machine::get();
        $found_assets = [];
        if ($existing->count() < 1){
          return $this->storeMachines($assets);
        }
        $ids = $existing->map(function($asset){
            return  $asset->asset_no;
        });

        foreach ($assets as $asset){
            if (!in_array($asset->idAssetNo,$ids->all())){
                $found_assets[] = $asset;
            }
        }
        if(!$found_assets){
            return response()->json([]);
        }
        return $this->storeMachines($found_assets);
    }
    function storeMachines($assets){
        $faker = Faker::create();
        $inserted = [];
        foreach ($assets as $asset){
                $inserted[] = Machine::create([
                'code' => $asset->cAssetCode,
                'asset_no' => $asset->idAssetNo,
                'description' => $asset->cAssetDesc
                ]);

        }
           return response()->json($inserted);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Machine::destroy($id);
        return response()->json($id);
    }
}

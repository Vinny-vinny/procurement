<?php

namespace App\Http\Controllers;
use App\Supplier;
use App\Vendor;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Supplier::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier = Supplier::create($request->all());
        return response()->json($supplier);
    }

 public function importSuppliers()
    {
          $suppliers = Vendor::select("Name","Account","Telephone","DCLink","Tax_Number","Physical1","Physical2","EMail")->get();
          $existing = Supplier::get();
          $found_suppliers = [];
          if (!$existing){
             return $this->storeSuppliers($suppliers);
          }
           $accounts = $existing->map(function($account){
           return  $account->dc_link;
        });

       foreach ($suppliers as $supplier){
           if (!in_array($supplier->DCLink,$accounts->all())){
               $found_suppliers[] = $supplier;
           }
       }
       if(!$found_suppliers){
          return response()->json([]);
       }
     return $this->storeSuppliers($found_suppliers);
     }
    function storeSuppliers($suppliers){
        $inserted = [];
        foreach ($suppliers as $supplier){
            $inserted[] = Supplier::create([
                'name' => $supplier->Name,
                'account' => $supplier->Account,
                'phone' => $supplier->Telephone,
                'tax_no' => $supplier->Tax_Number,
                'dc_link' => $supplier->DCLink,
                'physical_address' => $supplier->Physical1 ? $supplier->Physical1 : $supplier->Physical2,
                'email' => $supplier->EMail 
            ]);
        }
        return response()->json($inserted);
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
        Supplier::find($id)->update($request->all());
        return response()->json(Supplier::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supplier::destroy($id);
        return response()->json($id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRatesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class ExchangeRatesController extends Controller 
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['showAll', 'showOneCrypto', 'showStoreProducts']]);
        $this->middleware('admin', ['only' => ['createAssetExchangeRate', 'updateAssetExchangeRate', 'deleteAssetExchangeRate']]);
        
    } 


    public function assets()
    {
        // $this->hasOne('App\Models\');
    }


    /**
     * Show all Exchange Rates for An Asset.
     *
     * @return void
     */

    public function showExchangeRatesForAnAsset(ExchangeRatesModel $ExchangeRatesModel, $id)
    {
        return $ExchangeRatesModel->showExchangeRatesForAnAsset($id);       

    }
    
    
    /**
     * Show all Exchange Rates for An Asset.
     *
     * @return void
     */

    public function showSingleExchangeRate(ExchangeRatesModel $ExchangeRatesModel, $id)
    {
        return $ExchangeRatesModel->showSingleExchangeRate($id);       

    }


    /**
     * Show single exchange rate for single asset.
     *
     * @return void
     */
    public function showOneExchangeRate(Request $request, ExchangeRatesModel $ExchangeRatesModel)
    {
        return $ExchangeRatesModel->showOneExchangeRate($request->id);
    }


    /**
     * Create a new exchange rate for an asset.
     *
     * @return void
     */
    public function createAssetExchangeRate(Request $request, ExchangeRatesModel $ExchangeRatesModel)
    {    
        // return 33;      
        $rules = [
            'asset_id' => 'bail|required|integer|exists:asset_list,id',
            'min_range' => 'bail|required|numeric',
            'max_range' => 'bail|required|numeric',
            'rate' => 'bail|required|numeric',
            'remarks' => 'bail|string',
            'is_active' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
        };
        
        return $ExchangeRatesModel->createAssetExchangeRate($request);
       
    }



    /**
     * Update exchange rate for an asset.
     *
     * @return void
     */
    public function updateAssetExchangeRate(Request $request, ExchangeRatesModel $ExchangeRatesModel, $id)
    {

        // return 33;
            $rules = [
            'asset_id' => 'bail|required|string|exists:asset_list,id',
            'min_range' => 'bail|numeric',
            'max_range' => 'bail|numeric',
            'rate' => 'bail|numeric',
            'remarks' => 'bail|string',
            'is_active' => 'bail|boolean',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };

             return $ExchangeRatesModel->updateAssetExchangeRate($request);

       
    }

    /**
     * Delete a single exchange rate for an asset.
     *
     * @return void
     */
    public function deleteExchangeRate($id)
    {
        
        try {
                ExchangeRatesModel::findOrFail($id)->delete();
                return response()->json([
                    'msg' => 'Exchange rate Deleted successfully!',
                    'statusCode' => 200
                ], 200);
                }catch(\Exception $e){
                    return response()->json([
                        'msg' => 'Delete operation failed!',
                        'err' => $e->getMessage(),
                        'statusCode' => 409
                    ], 409);
            }
        }
    
    
        /**
     * Delete a single exchange rate for an asset.
     *
     * @return void
     */
    public function deleteExchangeRatesForAnAsset($id)
    {
        
        try {
                // return
                $id =
                ExchangeRatesModel::
                where('is_active', '=', 1)
                ->where('asset_id', '=', $id)
                ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
                // ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
                // ->get();
                ->pluck('exchange_rates.id');


                $ids = explode(",", $id);


             return  ExchangeRatesModel::find($id)->each(function($rate, $key){
                   return $key;
                   $key->delete();
               });
                return response()->json([
                    'msg' => 'Exchange rates Deleted for selected asset successfully!',
                    'statusCode' => 200
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Delete operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
        }
    
    
    
   
}

<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRatesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
// use \AmrShawky\LaravelCurrency\Facade\Currency;

class ExchangeRatesController extends Controller 
{
// use Currency;
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
     * Convert between currencies.
     *
     * @return object
     */

    public function currencyConversion(ExchangeRatesModel $ExchangeRatesModel, Request $request)
    {

        $rules = [
            'amount' => 'bail|numeric',
            'to' => 'bail|string|max:3',
            'from' => 'bail|string|required',
            // 'date' => 'bail|date|after_or_equal:2000-01-01',
            
        ];

        $messages = [
            // 'gte' => 'The deposit amount starts from N10,000.00 upwards' ,
            // 'required' => 'The :attribute field is required.',
            // 'file' => 'You are required to upload your proof of payment before proceeding',
            // 'mimes' => 'Only jpeg, png, bmp, pdf, svg, gif & tiff files are allowed.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
        };



        return $ExchangeRatesModel->currencyConversion($request);       

    }
    
    
     /**
     * Convert between currencies.
     *
     * @return object
     */

    public function currencyLatestRates(ExchangeRatesModel $ExchangeRatesModel, Request $request)
    {

        $rules = [
            'amount' => 'bail|numeric',
            'currency' => 'bail|array|max:3',
            'currency.*' => 'bail|string|distinct|max:3',
            // 'date' => 'bail|date|after_or_equal:2000-01-01',
            
        ];

        $messages = [
            // 'gte' => 'The deposit amount starts from N10,000.00 upwards' ,
            // 'required' => 'The :attribute field is required.',
            // 'file' => 'You are required to upload your proof of payment before proceeding',
            // 'mimes' => 'Only jpeg, png, bmp, pdf, svg, gif & tiff files are allowed.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
        };



        return $ExchangeRatesModel->currencyLatestRates($request);       

    }


    
    /**
     * Show all Exchange Rates for An Asset.
     *
     * @return object
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
    
    
        public function convert(){
            // Currency::convert()
            // ->from('USD')
            // ->to('EUR')
            // ->get();
        }
    
   
}

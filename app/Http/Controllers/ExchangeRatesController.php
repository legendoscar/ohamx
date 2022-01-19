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
        $this->middleware('admin', ['only' => ['createCryptoCoin', 'deleteOneCrypto', 'updateCryptoCoin']]);
        
    } 

    public function showAssetExchangeRates(ExchangeRatesModel $ExchangeRatesModel, $id){
        // return 33;

        $query = $ExchangeRatesModel
            ->where('is_active', '=', 1)
            ->where('asset_id', '=', $id)
            ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
            // ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
            ->get();

            return response()->json([
                'msg' => count($query) . ' exchange rates found.',
                'data' => $query, 
                'statusCode' => 200
            ], 200);

    }

    /**
     * Show all crypto instance.
     *
     * @return void
     */

    public function getAllCrypto(ExchangeRatesModel $ExchangeRatesModel)
    { 
       return $ExchangeRatesModel->getAllCrypto();
    }


    /**
     * Show single crypto instance.
     *
     * @return void
     */
    public function showOneCrypto(Request $request, ExchangeRatesModel $ExchangeRatesModel)
    {
        return $ExchangeRatesModel->showOneCrypto($request->id);
    }


    /**
     * Create a new crypto instance.
     *
     * @return void
     */
    public function createCryptoCoin(Request $request, ExchangeRatesModel $ExchangeRatesModel)
    {    
        // return 33;      
        $rules = [
            'asset_title' => 'bail|required|string|unique:asset_list,asset_title',
            'asset_symbol' => 'bail|required|string|unique:asset_list,asset_symbol',
            'asset_image' => 'bail|file',
            'asset_slug' => 'bail|string',
            'asset_tc' => 'bail|string',
            'is_available' => 'bail|boolean',
            'is_new' => 'bail|boolean',
            'is_popular' => 'bail|boolean',
            'is_recommended' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
        };
        
        return $ExchangeRatesModel->createCryptoCoin($request);
       
    }


    public function updateCryptoCoin(Request $request, ExchangeRatesModel $ExchangeRatesModel, $id)
    {

            $rules = [
                'asset_title' => 'bail|string|unique:asset_list,asset_title',
                'asset_symbol' => 'bail|string|unique:asset_list,asset_symbol',
                'asset_slug' => 'bail|string',
                'asset_image' => 'bail|file',
                'asset_tc' => 'bail|string',
                'is_available' => 'bail|boolean',
                'is_new' => 'bail|boolean',
                'is_popular' => 'bail|boolean',
                'is_recommended' => 'bail|boolean',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };

             return $ExchangeRatesModel->updateCryptoCoin($request);

       
    }


    public function deleteOneCrypto($id)
    {
        $ExchangeRatesModelData = ExchangeRatesModel::findOrFail($id);
       
            try {
                $ExchangeRatesModelData->delete();
                return response()->json([
                    'msg' => 'Deleted successfully!',
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


    public function ProductBelongsTo($id){
        try {
            $data = ExchangeRatesModel::find($id)->ProductsCategory;
            return response()->json([
                'msg' => 'Category selection successful!',
                'data' => $data,
                'statusCode' => 200
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'msg' => 'Failed to retrieve data!',
                'err' => $e->getMessage(),
                'statusCode' => 409
            ], 409);
        }
    }
    
    /**
     * Get popular crypto coins.
     *
     * @return void
     */

    public function getPopularCrypto(ExchangeRatesModel $ExchangeRatesModel)
    { 
       return $ExchangeRatesModel->getPopularCrypto();
    }
    
    
    /**
     * Get recommended crypto coins.
     *
     * @return void
     */

    public function getRecommendedCrypto(ExchangeRatesModel $ExchangeRatesModel)
    { 
       return $ExchangeRatesModel->getRecommendedCrypto();
    }
    
    /**
     * Get new crypto coins.
     *
     * @return void
     */

    public function getNewCrypto(ExchangeRatesModel $ExchangeRatesModel)
    { 
       return $ExchangeRatesModel->getNewCrypto();
    }


    /**
     * Get available crypto coins.
     *
     * @return void
     */

    public function getAvailableCrypto(ExchangeRatesModel $ExchangeRatesModel)
    { 
       return $this->getAllCrypto($ExchangeRatesModel);
    }
    
    /**
     * Get unavailable crypto coins.
     *
     * @return void
     */

    public function getUnAvailableCrypto(ExchangeRatesModel $ExchangeRatesModel)
    { 
       return $ExchangeRatesModel->getUnAvailableCrypto($ExchangeRatesModel);
    }
    
   
}

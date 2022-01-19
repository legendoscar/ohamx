<?php

namespace App\Http\Controllers;

use App\Models\CryptoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class CryptoController extends Controller 
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['showAll', 'showOneCrypto', 'showStoreProducts']]);
        $this->middleware('admin', ['only' => ['createCryptoCoin', 'deleteOneCrypto', 'updateCryptoCoin']]);
        
    } 


    /**
     * Show all crypto instance.
     *
     * @return void
     */

    public function getAllCrypto(CryptoModel $CryptoModel)
    { 
       return $CryptoModel->getAllCrypto();
    }


    /**
     * Show single crypto instance.
     *
     * @return void
     */
    public function showOneCrypto(Request $request, CryptoModel $CryptoModel)
    {
        return $CryptoModel->showOneCrypto($request->id);
    }


    /**
     * Create a new crypto instance.
     *
     * @return void
     */
    public function createCryptoCoin(Request $request, CryptoModel $CryptoModel)
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
        
        return $CryptoModel->createCryptoCoin($request);
       
    }


    public function updateCryptoCoin(Request $request, CryptoModel $CryptoModel, $id)
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

             return $CryptoModel->updateCryptoCoin($request);

       
    }


    public function deleteOneCrypto($id)
    {
        $CryptoModelData = CryptoModel::findOrFail($id);
       
            try {
                $CryptoModelData->delete();
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
            $data = CryptoModel::find($id)->ProductsCategory;
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

    public function getPopularCrypto(CryptoModel $CryptoModel)
    { 
       return $CryptoModel->getPopularCrypto();
    }
    
    
    /**
     * Get recommended crypto coins.
     *
     * @return void
     */

    public function getRecommendedCrypto(CryptoModel $CryptoModel)
    { 
       return $CryptoModel->getRecommendedCrypto();
    }
    
    /**
     * Get new crypto coins.
     *
     * @return void
     */

    public function getNewCrypto(CryptoModel $CryptoModel)
    { 
       return $CryptoModel->getNewCrypto();
    }


    /**
     * Get available crypto coins.
     *
     * @return void
     */

    public function getAvailableCrypto(CryptoModel $CryptoModel)
    { 
       return $this->getAllCrypto($CryptoModel);
    }
    
    /**
     * Get unavailable crypto coins.
     *
     * @return void
     */

    public function getUnAvailableCrypto(CryptoModel $CryptoModel)
    { 
       return $CryptoModel->getUnAvailableCrypto($CryptoModel);
    }
    
   
}

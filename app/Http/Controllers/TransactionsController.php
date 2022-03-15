<?php

namespace App\Http\Controllers;

use App\Models\TransactionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class TransactionsController extends Controller 
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['showAll', 'showOneCrypto', 'showStoreProducts']]);
        $this->middleware('admin', ['only' => ['createEwallet', 'deleteOneEwallet', 'updateEwallet']]);
        
    } 


    /**
     * Show all crypto instance.
     *
     * @return void
     */

    public function getAllEwallets(TransactionsModel $TransactionsModel)
    { 
       return $TransactionsModel->getAllEwallets();
    }


    /**
     * Show single crypto instance.
     *
     * @return void
     */
    public function showOneEwallet(Request $request, TransactionsModel $TransactionsModel)
    {
        return $TransactionsModel->showOneEwallet($request->id);
    }


    /**
     * Create a new ewallet instance.
     *
     * @return void
     */
    public function createEwallet(Request $request, TransactionsModel $TransactionsModel)
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
        
        return $TransactionsModel->createEwallet($request);
       
    }


    public function updateEwallet(Request $request, TransactionsModel $TransactionsModel, $id)
    {

            $rules = [
                'asset_title' => 'bail|string|unique:asset_list,asset_title',
                'asset_symbol' => 'bail|string|unique:asset_list,asset_symbol',
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

             return $TransactionsModel->updateCryptoCoin($request);

       
    }


    public function deleteOneEwallet($id)
    {
        $TransactionsModelData = TransactionsModel::findOrFail($id);
       
            try {
                $TransactionsModelData->delete();
                return response()->json([
                    'msg' => $TransactionsModelData->asset_title . ' Deleted successfully!',
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
     * Get popular Ewallets.
     *
     * @return void
     */

    public function getPopularEwallets(TransactionsModel $TransactionsModel)
    { 
       return $TransactionsModel->getPopularEwallets();
    }
    
    
    /**
     * Get recommended crypto coins.
     *
     * @return void
     */

    public function getRecommendedEwallets(TransactionsModel $TransactionsModel)
    { 
       return $TransactionsModel->getRecommendedEwallets();
    }
    
    /**
     * Get new crypto coins.
     *
     * @return void
     */

    public function getNewEwallets(TransactionsModel $TransactionsModel)
    { 
       return $TransactionsModel->getNewEwallets();
    }


    /**
     * Get available crypto coins.
     *
     * @return void
     */

    public function getAvailableEwallets(TransactionsModel $TransactionsModel)
    { 
       return $this->getAllEwallets($TransactionsModel);
    }
    
    /**
     * Get unavailable crypto coins.
     *
     * @return void
     */

    public function getUnAvailableEwallets(TransactionsModel $TransactionsModel)
    { 
       return $TransactionsModel->getUnAvailableEwallets($TransactionsModel);
    }
    
   
}

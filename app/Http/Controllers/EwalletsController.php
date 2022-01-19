<?php

namespace App\Http\Controllers;

use App\Models\EwalletsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class EwalletsController extends Controller 
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

    public function getAllEwallets(EwalletsModel $EwalletsModel)
    { 
       return $EwalletsModel->getAllEwallets();
    }


    /**
     * Show single crypto instance.
     *
     * @return void
     */
    public function showOneEwallet(Request $request, EwalletsModel $EwalletsModel)
    {
        return $EwalletsModel->showOneEwallet($request->id);
    }


    /**
     * Create a new ewallet instance.
     *
     * @return void
     */
    public function createEwallet(Request $request, EwalletsModel $EwalletsModel)
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
        
        return $EwalletsModel->createEwallet($request);
       
    }


    public function updateEwallet(Request $request, EwalletsModel $EwalletsModel, $id)
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

             return $EwalletsModel->updateCryptoCoin($request);

       
    }


    public function deleteOneEwallet($id)
    {
        $EwalletsModelData = EwalletsModel::findOrFail($id);
       
            try {
                $EwalletsModelData->delete();
                return response()->json([
                    'msg' => $EwalletsModelData->asset_title . ' Deleted successfully!',
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

    public function getPopularEwallets(EwalletsModel $EwalletsModel)
    { 
       return $EwalletsModel->getPopularEwallets();
    }
    
    
    /**
     * Get recommended crypto coins.
     *
     * @return void
     */

    public function getRecommendedEwallets(EwalletsModel $EwalletsModel)
    { 
       return $EwalletsModel->getRecommendedEwallets();
    }
    
    /**
     * Get new crypto coins.
     *
     * @return void
     */

    public function getNewEwallets(EwalletsModel $EwalletsModel)
    { 
       return $EwalletsModel->getNewEwallets();
    }


    /**
     * Get available crypto coins.
     *
     * @return void
     */

    public function getAvailableEwallets(EwalletsModel $EwalletsModel)
    { 
       return $this->getAllEwallets($EwalletsModel);
    }
    
    /**
     * Get unavailable crypto coins.
     *
     * @return void
     */

    public function getUnAvailableEwallets(EwalletsModel $EwalletsModel)
    { 
       return $EwalletsModel->getUnAvailableEwallets($EwalletsModel);
    }
    
   
}

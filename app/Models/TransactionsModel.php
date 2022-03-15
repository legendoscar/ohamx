<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class TransactionsModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'asset_list';

    protected $fillable = ['asset_cat_id', 'asset_title', 'asset_symbol', 'asset_image', 'asset_tc']; 

    // public $asset_cat_id = 1;

    public function __construct()
    {
        // $this->asset_cat_id = $asset_cat_id; 
    }

    // asset_cat_id for crypto = 1

    public function getAllEwallets(){
    
        try {
            $query = $this
            ->where('is_available', '=', 1)
            ->where('asset_cat_id', '=', 2)
            ->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Ewallets returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No ewallet coin found!', 
                    'err' => $e->getMessage(),
                    'asset_type' => 'Ewallets',
                    'statusCode' => 409
                ], 409);
            }
    }


    public function showOneEwallet($id){
        try {
            
            $data = $this->findOrFail($id); 
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => $data->asset_title . ' Record returned successfully.',
                    'asset_type' => 'Ewallets',
                    'statusCode' => 200
                ], 200)
                : $ret = response()->json([
                'msg' => 'No Record found for ewallet `' . $data->asset_title . '` with ID: ' . $id,
                'statusCode' => 404
            ], 404);
    
            return $ret;
    
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
        }

        
    public function createCryptoCoin(Request $request){
        try{
            
            $image_name = $request->asset_image;
            if($request->hasFile('asset_image')){
                $image_name = $request->asset_image->getClientOriginalName();

                $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                $destinationPath = app()->basePath($path);
                $request->file('asset_image')->move($destinationPath, $image_name);
            }

            $EwalletsModel = new EwalletsModel;
            // $EwalletsModel->asset_cat_id = 1;

            $EwalletsModel->asset_cat_id = 1;
            $EwalletsModel->asset_title = $request->asset_title;
            $EwalletsModel->asset_symbol = $request->asset_symbol;
            $EwalletsModel->asset_slug = $request->asset_slug;
            $EwalletsModel->asset_image = $request->asset_image;
            $EwalletsModel->asset_tc = $request->asset_tc;
            $EwalletsModel->save();

            return response()->json([
                'data' => $EwalletsModel,
                'msg' => 'New ewallet: `' . $request->asset_title .'` created successfully',
                'statusCode' => 201
            ], 201);
            } catch(\Exception $e){
                return response()->json([
                    'msg' => 'ewallet creation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }

    }


    public function updateCryptoCoin(Request $request){
        try {

            // return $request->all();
            $EwalletsModel = EwalletsModel::findorFail($request->id);
        
            $EwalletsModel->asset_cat_id = 1;
            $EwalletsModel->asset_title = $request->filled('asset_title') ? $request->asset_title : $EwalletsModel->asset_title;
            $EwalletsModel->asset_symbol = $request->filled('asset_symbol') ? $request->asset_symbol : $EwalletsModel->asset_symbol;
            $EwalletsModel->asset_image = $request->filled('asset_image') ? $request->asset_image : $EwalletsModel->asset_image;
            $EwalletsModel->asset_tc = $request->filled('asset_tc') ? $request->asset_tc : $EwalletsModel->asset_tc;
            $EwalletsModel->asset_slug = $request->filled('asset_slug') ? $request->asset_slug : $EwalletsModel->asset_slug;
            $EwalletsModel->is_available = $request->filled('is_available') ? $request->is_available : $EwalletsModel->is_available;
            $EwalletsModel->is_new = $request->filled('is_new') ? $request->is_new : $EwalletsModel->is_new;
            $EwalletsModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $EwalletsModel->is_popular;
            $EwalletsModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $EwalletsModel->is_recommended;

            $EwalletsModel->save();

            return response()->json([
                'data' => $EwalletsModel,
                'msg' => '`' . $EwalletsModel->asset_title . '` details updated successfully.',
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'ewallet update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ], 409);
        }catch(ModelNotFoundException $e){
            return 33;
        };
    }

     /**
     * Get popular ewallets.
     *
     * @return void
     */

    public function getPopularEwallets(){
        try {
            $query = $this->where('asset_cat_id', '=', 2)->where('is_popular', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' ewallets returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No ewallet found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
    
    /**
     * Get recommended ewallets.
     *
     * @return void
     */
    public function getRecommendedEwallets(){
        try {
            $query = $this->where('asset_cat_id', '=', 2)->where('is_recommended', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' ewallets returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No ewallet found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
    
    
    /**
     * Get new ewallets.
     *
     * @return void
     */
    public function getNewEwallets(){
        try {
            $query = $this->where('asset_cat_id', '=', 2)->where('is_new', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' ewallets returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No ewallet found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
   

    /**
     * Get unavailable ewallets.
     *
     * @return void
     */
   
    public function getUnAvailableEwallets(){
        try {
            $query = $this->where('asset_cat_id', '=', 2)->where('is_available', '=', 0)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' ewallets returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No ewallet found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
}
<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class ExchangeRatesModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'exchange_rates';

    protected $fillable = ['asset_id', 'min_range', 'max_range', 'rate', 'remarks']; 

    // public $asset_cat_id = 1;

    public function __construct()
    {
        // $this->asset_cat_id = $asset_cat_id; 
    }

    // asset_cat_id for crypto = 1

    public function showAssetExchangeRates($id){

    }

    public function getAllCrypto(){
        $c = new CryptoModel;

        // return  $c->asset_cat_id;
        try {
            $query = $this
            ->where('is_available', '=', 1)
            ->where('asset_cat_id', '=', 1)
            ->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
    }


    public function showOneCrypto($id){
        try {
            
            $data = $this->findOrFail($id); 
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => $data->asset_title . ' Record returned successfully.',
                    'statusCode' => 200
                ], 200)
                : $ret = response()->json([
                'msg' => 'No Record found for crypto `' . $data->asset_title . '` with ID: ' . $id,
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

            $CryptoModel = new CryptoModel;
            // $CryptoModel->asset_cat_id = 1;

            $CryptoModel->asset_cat_id = 1;
            $CryptoModel->asset_title = $request->asset_title;
            $CryptoModel->asset_symbol = $request->asset_symbol;
            $CryptoModel->asset_slug = $request->asset_slug;
            $CryptoModel->asset_image = $request->asset_image;
            $CryptoModel->asset_tc = $request->asset_tc;
            $CryptoModel->save();

            return response()->json([
                'data' => $CryptoModel,
                'msg' => 'New Crypto Coin: `' . $request->asset_title .'` created successfully',
                'statusCode' => 201
            ], 201);
            } catch(\Exception $e){
                return response()->json([
                    'msg' => 'Crypto Coin creation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }

    }


    public function updateCryptoCoin(Request $request){
        try {

            // return $request->all();
            $CryptoModel = CryptoModel::findorFail($request->id);
        
            $CryptoModel->asset_cat_id = 1;
            $CryptoModel->asset_title = $request->filled('asset_title') ? $request->asset_title : $CryptoModel->asset_title;
            $CryptoModel->asset_symbol = $request->filled('asset_symbol') ? $request->asset_symbol : $CryptoModel->asset_symbol;
            $CryptoModel->asset_image = $request->filled('asset_image') ? $request->asset_image : $CryptoModel->asset_image;
            $CryptoModel->asset_tc = $request->filled('asset_tc') ? $request->asset_tc : $CryptoModel->asset_tc;
            $CryptoModel->asset_slug = $request->filled('asset_slug') ? $request->asset_slug : $CryptoModel->asset_slug;
            $CryptoModel->is_available = $request->filled('is_available') ? $request->is_available : $CryptoModel->is_available;
            $CryptoModel->is_new = $request->filled('is_new') ? $request->is_new : $CryptoModel->is_new;
            $CryptoModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $CryptoModel->is_popular;
            $CryptoModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $CryptoModel->is_recommended;

            $CryptoModel->save();

            return response()->json([
                'data' => $CryptoModel,
                'msg' => '`' . $CryptoModel->asset_title . '` details updated successfully.',
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Crypto coin update operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ], 409);
        }catch(ModelNotFoundException $e){
            return 33;
        };
    }

     /**
     * Get popular crypto coins.
     *
     * @return void
     */

    public function getPopularCrypto(){
        try {
            $query = $this->where('asset_cat_id', '=', 1)->where('is_popular', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
    
    /**
     * Get recommended crypto coins.
     *
     * @return void
     */
    public function getRecommendedCrypto(){
        try {
            $query = $this->where('asset_cat_id', '=', 1)->where('is_recommended', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
    
    
    /**
     * Get new crypto coins.
     *
     * @return void
     */
    public function getNewCrypto(){
        try {
            $query = $this->where('asset_cat_id', '=', 1)->where('is_new', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
   

    /**
     * Get unavailable crypto coins.
     *
     * @return void
     */
   
    public function getUnAvailableCrypto(){
        try {
            $query = $this->where('asset_cat_id', '=', 1)->where('is_available', '=', 0)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
}
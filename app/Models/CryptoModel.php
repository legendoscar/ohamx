<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class CryptoModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'crypto_coins_list';

    protected $fillable = ['coin_title', 'coin_code', 'coin_image', 'coin_tc']; 


    public function getAllCrypto(){
        try {
            $query = $this->where('is_available', '=', 1)->get();
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
                    'msg' => 'Record returned successfully.',
                    'statusCode' => 200
                ], 200)
                : $ret = response()->json([
                'msg' => 'No Record found for crypto with ID: ' . $id,
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
        // return 'hi';

        try{
            
            $image_name = $request->coin_image;
            if($request->hasFile('coin_image')){
                $image_name = $request->coin_image->getClientOriginalName();

                $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                $destinationPath = app()->basePath($path);
                $request->file('coin_image')->move($destinationPath, $image_name);
            }

            $CryptoModel = new CryptoModel;

            $CryptoModel->coin_title = $request->coin_title;
            $CryptoModel->coin_code = $request->coin_code;
            $CryptoModel->coin_image = $request->coin_image;
            $CryptoModel->coin_tc = $request->coin_tc;
            $CryptoModel->save();

            return response()->json([
                'data' => $CryptoModel,
                'msg' => 'New Crypto Coin: `' . $request->coin_title .'` created successfully',
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
        
            $CryptoModel->coin_title = $request->filled('coin_title') ? $request->coin_title : $CryptoModel->coin_title;
            $CryptoModel->coin_code = $request->filled('coin_code') ? $request->coin_code : $CryptoModel->coin_code;
            $CryptoModel->coin_image = $request->filled('coin_image') ? $request->coin_image : $CryptoModel->coin_image;
            $CryptoModel->coin_tc = $request->filled('coin_tc') ? $request->coin_tc : $CryptoModel->coin_tc;
            $CryptoModel->is_available = $request->filled('is_available') ? $request->is_available : $CryptoModel->is_available;
            $CryptoModel->is_new = $request->filled('is_new') ? $request->is_new : $CryptoModel->is_new;
            $CryptoModel->is_popular = $request->filled('is_popular') ? $request->is_popular : $CryptoModel->is_popular;
            $CryptoModel->is_recommended = $request->filled('is_recommended') ? $request->is_recommended : $CryptoModel->is_recommended;

            $CryptoModel->save();

            return response()->json([
                'data' => $CryptoModel,
                'msg' => '`' . $CryptoModel->coin_title . '` details updated successfully.',
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
            $query = $this->where('is_popular', '=', 1)->get();
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
            $query = $this->where('is_recommended', '=', 1)->get();
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
            $query = $this->where('is_new', '=', 1)->get();
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
            $query = $this->where('is_available', '=', 0)->get();
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
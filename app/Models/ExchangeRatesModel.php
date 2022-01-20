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

    protected $fillable = ['asset_id', 'min_range', 'max_range', 'rate', 'remarks', 'is_active']; 

    
     /**
     * The attributes for date created at, deleted at and updated at columns.
     *
     * @var array
     */
    const CREATED_AT = 'rate_creation_date';
    const UPDATED_AT = 'rate_update_date';
    const DELETED_AT = 'rate_deleted_at';

    public function __construct()
    {
        // $this->asset_cat_id = $asset_cat_id; 
    }

    // asset_cat_id for crypto = 1

    public function showExchangeRatesForAnAsset($id){

        $query = $this
            ->where('is_active', '=', 1)
            ->where('asset_id', '=', $id)
            // ->where('asset_list_deleted_at', '=', null)
            ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
            // ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
            ->get();

            return response()->json([
                'msg' => count($query) . ' exchange rates found.',
                'data' => $query, 
                'statusCode' => 200
            ], 200);
    }
    
    
    public function showSingleExchangeRate($id){

        $query = $this->findOrFail($id);

            return response()->json([
                'msg' => 'Exchange rate returned successfully!',
                'data' => $query, 
                'statusCode' => 200
            ], 200);
    }

    public function getAllCrypto(){
        $c = new ExchangeRatesModel;

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


    public function showOneExchangeRate($id){
        try {
            
            $data = $this->findOrFail($id); 
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => 'Record returned successfully.',
                    'statusCode' => 200
                ], 200)
                : $ret = response()->json([
                'msg' => 'No Record found for exchange rate with ID: ' . $id,
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

        
    public function createAssetExchangeRate(Request $request){
        try{

            $ExchangeRatesModel = new ExchangeRatesModel;
         
            $ExchangeRatesModel->asset_id = $request->asset_id;
            $ExchangeRatesModel->min_range = $request->min_range;
            $ExchangeRatesModel->max_range = $request->max_range;
            $ExchangeRatesModel->rate = $request->rate;
            $ExchangeRatesModel->remarks = $request->remarks;
            $ExchangeRatesModel->is_active = $request->is_active;
            $ExchangeRatesModel->save();

            return response()->json([
                'data' => $ExchangeRatesModel,
                'msg' => 'New Exchange rate created successfully',
                'statusCode' => 201
            ], 201);
            } catch(\Exception $e){
                return response()->json([
                    'msg' => 'Exchange rate creation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }

    }


    public function updateAssetExchangeRate(Request $request){
        try {

            // return $request->all();
            $ExchangeRatesModel = ExchangeRatesModel::findorFail($request->id);
        
            $ExchangeRatesModel->asset_id = 1;
            $ExchangeRatesModel->min_range = $request->filled('min_range') ? $request->min_range : $ExchangeRatesModel->min_range;
            $ExchangeRatesModel->max_range = $request->filled('max_range') ? $request->max_range : $ExchangeRatesModel->max_range;
            $ExchangeRatesModel->rate = $request->filled('rate') ? $request->rate : $ExchangeRatesModel->rate;
            $ExchangeRatesModel->remarks = $request->filled('remarks') ? $request->remarks : $ExchangeRatesModel->remarks;
            $ExchangeRatesModel->is_active = $request->filled('is_active') ? $request->is_active : $ExchangeRatesModel->is_active;
            $ExchangeRatesModel->rate_update_date = Carbon::now();

            $ExchangeRatesModel->save();

            return response()->json([
                'data' => $ExchangeRatesModel,
                'msg' => '`' . $ExchangeRatesModel->asset_title . '` details updated successfully.',
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
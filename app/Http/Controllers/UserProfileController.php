<?php

    namespace App\Http\Controllers;
    
    use App\Models\User;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Str;
    use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class UserProfileController extends Controller
    {
    
        public function __construct()
        {
        //     $this->middleware('auth:api', ['except' => ['login','registerAdmin']]);
        }
    
       
        
         /**
         * Get user details.
         *
         * @param  Request  $request
         * @return Response
         */	 	
        public function profile(Request $request) 
        {
                // return 3;
          
            try {
                $token = $request->header( 'Authorization' );
                if (!$token){ return response()->json(['msg' => 'token_not_found'], 404);}

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                        return response()->json(['user_not_found'], 404);
                }
    
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    
                    return response()->json(['token_expired'], $e->getStatusCode());
    
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    
                    return response()->json(['token_invalid'], $e->getStatusCode());
    
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
    
                    return response()->json(['token_absent'], $e->getStatusCode());
    
            }
    
            $user = auth()->user();
            return response()->json(['user'=>$user], 200);
        }
   
}
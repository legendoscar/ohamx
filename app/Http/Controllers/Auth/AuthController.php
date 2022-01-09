<?php

    namespace App\Http\Controllers\Auth;
    
    use App\Models\User; 
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\Validator;
    use Carbon\Carbon;
    use App\Models\LastLoginModel;

    class AuthController extends Controller
    {
        protected $guard = 'users'; 

        protected $username;

        
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','registerUser', 'registerStore']]);
            $this->middleware('storeCanCreate', ['only' => ['registerStore']]);

            $this->username = $this->findUsername();
        }

        /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');
 
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
 
        request()->merge([$fieldType => $login]);
 
        return $fieldType;
    }
 
    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    
        /**
         * Store a new user.
         *
         * @param  Request  $request
         * @return Response
         */
        public function registerUser(Request $request)
        {
            //validate incoming request 
            $rules = [
                'username' => 'required|bail|unique:users',
                'fname' => 'required|bail|string',
                'lname' => 'required|bail|string',
                'email' => 'required|bail|email|unique:users',
                'phone' => 'bail|numeric|unique:users',
                'password' => 'required|bail|min:6|confirmed',
                'profile_image' => 'bail|file',
                'designation' => 'bail|string',
                'dob' => 'bail|string',
                'location' => 'bail|string',
            ];

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };
    
            try 
            {
                $user = new User;
                $user->username= $request->input('username');
                $user->fname= $request->input('fname');
                $user->lname= $request->input('lname');
                $user->email= $request->input('email');
                $user->phone= $request->input('phone');
                $user->designation= $request->input('designation');
                $user->dob= $request->input('dob');
                $user->location= $request->input('location');

                if($request->getRequestUri() == '/api/auth/register/admin') {
                    $user->isAdmin = 1;
                    $type = 'Admin';
                }elseif($request->getRequestUri() == '/api/auth/register/user') {
                    $user->isAdmin = 0;
                    $type = 'User';
                }
                

                $user->password = Hash::make($request->input('password'));
                
                $user->save();
    
                $this->login($request);
                return response()->json( [
                            'data' => $user, 
                            'action' => 'create', 
                            'msg' => $type . ' account created successfully.',
                            'statusCode' => 201,
                             
                ], 201);
    
            } 
            catch (\Exception $e) 
            {
                return response()->json( [
                           'action' => 'create', 
                           'err' => $e->getMessage(),
                           'msg' => $type . ' account creation failed'
                ], 409);
            }
        }
        
         /**
         * Get a JWT via given credentials.
         *
         * @param  Request  $request
         * @return Response
         */	 
        public function login(Request $request)
        {
              //validate incoming request 
            $this->validate($request, [
                'login' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $credentials = $request->only(['email', 'username', 'password']);
    
            try{
            if (! $token = auth()->attempt($credentials)) {			
                return response()->json(['message' => 'Unauthorized. Invalid credentials'], 401);
            }

            $user = auth()->user()->isAdmin == 1 ? 'Admin' : 'User';

            //update login details
            
            $lastlogin = new LastLoginModel;
            $lastlogin->user_id = auth()->user()->id;
            $lastlogin->last_login = Carbon::now();

            $lastlogin->save();


        }catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        };
            return  response()->json([
                'msg' => $user . ' Login successful',
                'tokenData' => $this->respondWithToken($token),
                'userData' => auth()->user()                    
            ], 200);
        }
        
         /**
         * Get user details.
         *
         * @param  Request  $request
         * @return Response
         */	 	
        public function profile()
        {
            try {

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
            return response()->json(['user'=>$user], 201);
            // return response()->json([compact('user')], 200);
        }
    
        


        /**
     * Check the validity of token.
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function getAuthenticatedUser()
    {
        try {

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

        return response()->json(compact('user'));
    }

     /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function logout( Request $request ) {

        $token = $request->header( 'Authorization' );

        try {
            JWTAuth::parseToken()->invalidate( $token );

            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.logged_out' )
            ] );
        } catch ( TokenExpiredException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.token.expired' )

            ], 401 );
        } catch ( TokenInvalidException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.token.invalid' )
            ], 401 );

        } catch ( JWTException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.token.missing' )
            ], 500 );
        }
    }

    
}
<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(
                ['code' => 0,'message' => $validator->errors()->toJson()],
                422
            );
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json([
                'code' => 0,
                'message' => 'Unauthorized'
            ], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|between:2,100',
            'email' => 'bail|required|string|email|max:100|unique:users',
            'password' => 'bail|required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json(
                ['code' => 0,'message' => $validator->errors()->toJson()],
                400
            );
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'code' => 1,
            'message' => 'User successfully registered',
            //'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'code' => 1,
            'message' => 'User successfully signed out'
        ], 200);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'code' => 1,
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'message' => 'successfully'
            //'user' => auth()->user()
        ]);
    }
}

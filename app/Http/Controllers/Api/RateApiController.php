<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;

class RateApiController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

        // URL
        $apiURL = 'https://tw.rter.info/capi.php';

      	// POST Data
        $postInput = [
            //...
        ];

        // Headers
        $headers = [
            //...
        ];

        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', $apiURL, ['form_params' => $postInput, 'headers' => $headers]);

        $this->responseBody = json_decode($this->response->getBody(), true);
    }

    public function index()
    {
        $statusCode = $this->response->getStatusCode(); // status code
        if($statusCode == 200) {
            return response()->json([
                'code' => 1,
                'message' => 'successfully',
                'data' => [
                        $this->responseBody
                    ]
            ], 200);
        } else {
            return response()->json([
                'code' => 0,
                'message' => '系統錯誤!!',
                'data' => []
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'bail|required|string|alpha',
            'to' => 'bail|required|string|alpha',
            'money' => 'bail|required|int',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $statusCode = $this->response->getStatusCode(); // status code
        if($statusCode == 200) {
            // 用美金換算 USD -> XXX
            if($request->from == 'USD' && isset($this->responseBody[$request->from.$request->to])) {
                return response()->json([
                    'code' => 1,
                    'message' => 'successfully',
                    'data' => [
                            'Exrate' => ($request->money * $this->responseBody[$request->from.$request->to]['Exrate']),
                            'UTC'    => $this->responseBody[$request->from.$request->to]['UTC']
                        ]
                ], 200);
            } else if($request->to == 'USD' && isset($this->responseBody['USD'.$request->from])) {
                // 換成美金換算 XXX -> USD
                return response()->json([
                    'code' => 1,
                    'message' => 'successfully',
                    'data' => [
                            'Exrate' => ($request->money / $this->responseBody['USD'.$request->from]['Exrate']),
                            'UTC'    => $this->responseBody['USD'.$request->from]['UTC']
                        ]
                ], 200);
            } else if($request->to != 'USD' && $request->from != 'USD' &&
                        isset($this->responseBody['USD'.$request->from]) &&
                        isset($this->responseBody['USD'.$request->to])
            ) {
                return response()->json([
                    'code' => 1,
                    'message' => 'successfully',
                    'data' => [
                            'Exrate' => ($request->money / $this->responseBody['USD'.$request->from]['Exrate'] *
                                            $this->responseBody['USD'.$request->to]['Exrate']),
                            'UTC'    => $this->responseBody['USD'.$request->to]['UTC']
                        ]
                ], 200);
            } else {
                return response()->json([
                    'code' => 0,
                    'message' => '查無此匯率代碼',
                    'data' => []
                ], 200);
            }
        } else {
            return response()->json([
                'code' => 0,
                'message' => '系統錯誤!!',
                'data' => []
            ], 401);
        }
    }
}

<?php

namespace App\Http\Middleware;use Closure;

use Tymon\JWTAuth\Facades\JWTAuth;

use Exception;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware

{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {

        try {

            $user = JWTAuth::parseToken()->authenticate();

            if( !$user ) throw new Exception('User Not Found');

        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json([

                        'success' => false,

                        'data' => [

                            'message' => 'Token Invalid',

                            'code' =>  400

                        ]

                    ]

                );

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json([

                        'success' => false,

                        'data' => [

                            'message' => 'Token Expired',

                            'code' => 400

                        ]

                    ]

                );

            }

            else{

                if( $e->getMessage() === 'User Not Found') {

                    return response()->json([

                            "success" => false,

                            "data" => [

                                "message" => "User Not Found",

                                "code" =>  404

                            ]

                        ]

                    ); }

                return response()->json([

                        'success' => false,

                        'data' => [

                            'message' => 'Authorization Token not found',

                            'code' => 401

                        ]

                    ]

                );

            }

        }

        return $next($request);

    }

}

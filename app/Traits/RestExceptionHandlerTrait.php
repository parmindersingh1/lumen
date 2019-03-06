<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException as UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException as TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException as TokenInvalidException;


trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $exception)
    {        
        dd($exception);
        // This will replace our 404 response from the MVC to a JSON response.
        // Enable header Accept: application/json to see the proper error msg
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['error' => 'Resource not found'], 404);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['error' => 'Url not found'], 404);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Method Not Allowed'], 405);
        }
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json(['error' => 'Token not provided'], 401);
        }
       
        if ($exception instanceof TokenExpiredException) {
            return response()->json(['error' => 'token_expired'], 401);
        } 
        if ($exception instanceof TokenInvalidException) {
            return response()->json(['error' => 'token_invalid'], 401);
        }
        if ($exception->getPrevious() instanceof TokenBlacklistedException) {
            return response()->json(['error' => 'TOKEN_BLACKLISTED'], 401);
        }        
        if ($exception instanceof JWTException) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }

        // detect instance
        // if ($exception instanceof UnauthorizedHttpException) {
        //     // detect previous instance
        //     if ($exception->getPrevious() instanceof TokenExpiredException) {
        //         return response()->json(['error' => 'TOKEN_EXPIRED'], $exception->getStatusCode());
        //     } else if ($exception->getPrevious() instanceof TokenInvalidException) {
        //         return response()->json(['error' => 'TOKEN_INVALID'], $exception->getStatusCode());
        //     } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
        //         return response()->json(['error' => 'TOKEN_BLACKLISTED'], $exception->getStatusCode());
        //     } else {
        //         return response()->json(['error' => "UNAUTHORIZED_REQUEST"], 401);
        //     }
        // }

        
        return parent::render($request, $exception);
    }

    

   

}
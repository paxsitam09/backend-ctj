<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
    
            $user = User::where('email', $credentials['email'])->firstOrFail();
    
            if (!Hash::check($credentials['password'], $user->password)) {
                throw new AuthenticationException('Invalid email or password.');
            }
    
            // Generate Sanctum token using the 'ctj-api' guard
            $token = $user->createToken('CTJAuthToken', ['ctj-api'])->plainTextToken;

            return response()->json([
                'status'  => Response::HTTP_OK,
                'message' => 'Login successful',
                'user'    => new LoginResource($user, $token), // Pass token to resource
            ], Response::HTTP_OK);

        } catch (AuthenticationException $e) {
            return response()->json([
                'status'  => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => Response::HTTP_NOT_FOUND,
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

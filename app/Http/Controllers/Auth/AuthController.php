<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use Illuminate\Http\Request;
use App\Values\Auth\UserData;
use App\Http\Controllers\Controller;
use App\Services\Contracts\AuthService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                "name" => "required|max:255",
                "email" => "required|email|unique:users|max:255",
                "password" => "required|max:255",
            ]);

            $user = $this->authService->register(new UserData(
                $data['email'],
                $data['password'],
                $data['name']
            ));

            return response()->json([
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([ "error" => $e->errors() ], 400);
        } catch (\LogicException $e) {
            return response()->json([ "error" => $e->getMessage() ], 400);
        } catch (\Exception $e) {
            return response()->json([ "error" => 'something went wrong' ], 400);
        }
    }

    public function auth(Request $request)
    {
        try {
            $user = $this->authService->auth($request->input('token'));

            return response()->json([
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email
            ], 200);
        } catch (\LogicException $e) {
            return response()->json([ "error" => $e->getMessage() ], 400);
        } catch (\Exception $e) {
            return response()->json([ "error" => 'something went wrong' ], 400);
        }
    }
}

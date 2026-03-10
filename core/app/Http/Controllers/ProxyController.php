<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Service\ServiceControl\Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Google\Client as GoogleClient;


class ProxyController extends Controller
{
    public function proxy(Request $request)
    {
        $apiUrl = 'https://web.sprintpay.online/api/resolve-bank';
        $queryParams = $request->query();
        $client = new Client();

        try {
            // Make a request to the external API
            $response = $client->get($apiUrl, [
                'query' => $queryParams,
            ]);

            // Return the API response
            return response($response->getBody(), $response->getStatusCode())
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function googleCallback(Request $request)
    {
        $client = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->credential);

        if ($payload) {
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];

            // find or create user
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'google_id' => $googleId]
            );

            Auth::login($user);

            return redirect('/products');
        } else {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}

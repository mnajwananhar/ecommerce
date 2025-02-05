<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DestinationController extends Controller
{
    // Method untuk menampilkan halaman pencarian


    // Method untuk pencarian destinasi menggunakan API
    public function search(Request $request)
    {
        $search = $request->query('search'); // Ambil query param 'search'
        $client = new Client();

        try {
            $response = $client->request('GET', config('services.rajaongkir.base_url') . '/destination/domestic-destination', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => config('services.rajaongkir.api_key'),
                ],
                'query' => [
                    'search' => $search,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data['data']); // Kirim data ke frontend
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data'], 500);
        }
    }
}

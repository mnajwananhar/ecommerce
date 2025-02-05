<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SellerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerRequestController extends Controller
{

    public function index()
    {
        $requests = SellerRequest::with('user')->where('status', 'pending')->get();
        return view('seller-request.index', compact('requests'));
    }
    public function create()
    {
        $status = SellerRequest::where('user_id', auth()->id())->value('status');
        return view('seller-request.create', compact('status'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'selfie_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $selfiePhotoPath = $request->file('selfie_photo')->store('selfie_photos', 's3');

        SellerRequest::create([
            'nik' => $request->nik,
            'full_name' => $request->full_name,
            'selfie_photo' => $selfiePhotoPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('seller-request.create')->with('success', 'Request submitted successfully.');
    }





    public function approve(SellerRequest $sellerRequest)
    {
        $sellerRequest->update(['status' => 'approved']);
        // dd($sellerRequest->user);
        $sellerRequest->user->update(['role' => 'seller']);
        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(SellerRequest $sellerRequest)
    {
        $sellerRequest->update(['status' => 'rejected']);
        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function searchDestination(Request $request)
    {
        $search = $request->query('search');
        $client = new \GuzzleHttp\Client();

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
            return response()->json($data['data']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data'], 500);
        }
    }

    public function reset(Request $request)
    {
        $sellerRequest = SellerRequest::where('user_id', auth()->id())->where('status', 'rejected')->first();

        if ($sellerRequest) {
            $sellerRequest->delete(); // Hapus data pengajuan
        }

        return redirect()->route('seller-request.create')->with('success', 'Anda dapat mengajukan ulang pengajuan Anda.');
    }
}

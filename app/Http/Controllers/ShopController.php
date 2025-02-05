<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product; // Tambahkan ini di bagian atas file jika belum ada

class ShopController extends Controller
{
    public function create()
    {
        $shop = Shop::where('seller_id', Auth::id())->first();
        if ($shop) {
            return redirect()->route('shops.edit', $shop)->with('info', 'Anda sudah memiliki toko. Edit toko jika ingin memperbarui.');
        }
        return view('shops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string|max:255',
            'shop_address_label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'shop_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['shop_name', 'shop_address', 'shop_address_label', 'description']);
        $data['seller_id'] = Auth::id();

        if ($request->hasFile('shop_logo')) {
            $data['shop_logo'] = $request->file('shop_logo')->store('shop-logos', 's3');
        }

        Shop::create($data);

        return redirect()->route('shops.create')->with('success', 'Toko berhasil dibuat.');
    }

    public function edit(Shop $shop)
    {


        if ($shop->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit toko ini.');
        }
        return view('shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        if ($shop->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit toko ini.');
        }

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string|max:255',
            'shop_address_label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'shop_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['shop_name', 'shop_address', 'shop_address_label', 'description']);

        if ($request->hasFile('shop_logo')) {
            if ($shop->shop_logo) {
                Storage::disk('s3')->delete($shop->shop_logo);
            }
            $data['shop_logo'] = $request->file('shop_logo')->store('shop-logos', 's3');
        }

        $shop->update($data);

        return redirect()->route('shops.edit', $shop)->with('success', 'Toko berhasil diperbarui.');
    }

    public function searchDestination(Request $request)
    {
        $query = $request->query('search');
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', config('services.rajaongkir.base_url') . '/destination/domestic-destination', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => config('services.rajaongkir.api_key'),
                ],
                'query' => [
                    'search' => $query,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data['data']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data'], 500);
        }
    }

    public function index()
    {
        $shop = Shop::where('seller_id', Auth::id())->first();
        if (!$shop) {
            return redirect()->route('shops.create')->with('error', 'Anda belum memiliki toko. Silakan buat toko terlebih dahulu.');
        }

        $products = Product::where('seller_id', Auth::id())
            ->with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shops.index', compact('shop', 'products'));
    }

    public function show(Shop $shop)
    {
        $products = $shop->products()->paginate(12);
        return view('products.shop', compact('shop', 'products'));
    }
}

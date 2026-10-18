<?php

namespace App\Http\Controllers;

use App\Jobs\VideoHlsProcess;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    public function index(Request $request){
        $data = Product::get();
        return response()->json(['message' => "Menampilkan daftar product", 'success' => true, 'data' => $data], 200);
    }
    
    public function show(Request $request, $id){
        $data = Product::with('productMedia')->findOrFail($id);
        return response()->json(['message' => "Menampilkan detail product", 'success' => true, 'data' => $data], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'product_media' => 'required',
            'product_media.*' => 'required|file|mimes:jpg,jpeg,png,mp4',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false], 422);
        }

        $product = Product::create($request->only(['product_name','description','price','stock']));
        foreach($request->file('product_media') as $index => $media){
            $extension = $media->getClientOriginalExtension();
            if($extension == "mp4"){
                $nmmedia = "video_" . time() . ($index + 1) . '.' . $extension;
                $mediaPath = $media->storeAs('videos/raw',$nmmedia,'public');
                $productMedia = ProductMedia::create([
                    'product_id' => $product->id,
                    'type' => 'video',
                    'media_path' => $mediaPath
                ]);
                VideoHlsProcess::dispatch($productMedia->id);
            }else if(in_array($extension,['jpg','jpeg','png'])){
                $nmmedia = "image_" . time() . ($index + 1) . '.' . $extension;
                $mediaPath = $media->storeAs('images',$nmmedia,'public');
                ProductMedia::create([
                    'product_id' => $product->id,
                    'type' => 'image',
                    'media_path' => $mediaPath
                ]);
            }
        }

        $product->load('productMedia');
        return response()->json(['message' => "Product berhasil ditambahkan", 'success' => true, 'data' => $product], 201);
    }
}

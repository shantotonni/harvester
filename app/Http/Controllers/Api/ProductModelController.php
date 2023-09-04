<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductModel\ProductModelStoreRequest;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductModelController extends Controller
{
    public function index(){
        $models = ProductModel::with('Products')->OrderBy('id','asc')->where('product_id',4)->paginate(20);
        return new ProductModelCollection($models);
    }
    public function store(ProductModelStoreRequest $request)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/ProductModel/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

         $model = new ProductModel();
        $model->product_id = $request->product_id;
        $model->model_name = $request->model_name;
        $model->model_name_bn = $request->model_name_bn;
        $model->model_code = $request->model_code;
        $model->details = $request->details;
        $model->model_image = $name;
         $model->save();
        return response()->json(['message' => 'Successfully Product Model Created', 200]);

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
         $model = ProductModel::where('id', $id)->first();
        $image = $request->image;
        if ($image !=  $model->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/ProductModel/';
                $file_old = public_path('/') . $destinationPath .  $model->model_image;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/ProductModel/') . $name);
            } else {
                $name =  $model->image;
            }

        } else {
            $name =  $model->image;
        }

         $model->product_id = $request->product_id;
         $model->model_name = $request->model_name;
         $model->model_name_bn = $request->model_name_bn;
         $model->model_code = $request->model_code;
         $model->details = $request->details;
         $model->model_image = $name;
         $model->save();
        return response()->json(['message' => 'Product Model Successfully Updated '], 200);
    }

    public function destroy(Request $request, $id)
    {

         $model = ProductModel::where('id', $id);
        if ($request->has('image')) {
            $image = $request->image;
            if (file_exists($image)) {
                unlink($image);
            }
        }

         $model->delete();
        return response()->json(['message' => 'Product Model Deleted Successfully']);
    }

    public function search($query)
    {
        return new ProductModelCollection(ProductModel::Where('model_name', 'like', "%$query%")
          ->paginate(10));
    }



}

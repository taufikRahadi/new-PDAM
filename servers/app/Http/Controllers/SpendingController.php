<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spending;
use App\Http\Resources\SpendingResource;
use Image;
use DB;

class SpendingController extends Controller
{
    public function insert(Request $request)
    {
        $message = '';
        $status = 'error';
        $code = 500;
        $data = null;
        $this->validate($request, [
            'name' => 'required',
            'total' => 'required',
            'information' => 'required',
            'img' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $rand = rand();
            $exten = '.' .explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
            $small_thumbnail = time()."_small_".$rand.$exten;
            $original = time()."_origin_".$rand.$exten;
            $img = Image::make($request->img);
            // tujuan
            $tujuan_thumbnail = 'img/thumbnail';
            $tujuan_original = 'img/original';
            //resize
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($tujuan_thumbnail.'/'.$small_thumbnail);
            //upload original size
            Image::make($request->img)->save($tujuan_original.'/'.$original);
            // store db
            $spending = new Spending;
            $spending->user_id = auth('api')->user()->id;
            $spending->name = $request->name;
            $spending->total = $request->total;
            $spending->information = $request->information;
            $spending->img = $original;
            $spending->thumbnail = $small_thumbnail;

            if ($spending->save()) {
                DB::commit();
                $message = 'spending created';
                $status = 'success';
                $data = $spending;
                $code = 200;
            }
            else {
                $message = 'insert failed';
                $code = 402;
            }
        }
        catch(\Exception $e) {
            $message = $e->getMessage();
            $this->deleteImg('/img/original/', $original);
            $this->deleteImg('/img/thumbnail/', $small_thumbnail);
        }
        return Response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);

    }
    public function getSpending()
    {
        $data = Spending::with('user')->paginate(10);
        return SpendingResource::collection($data);
    }

    public function delete($id)
    {
        $status = '';
        $message = 'error';
        $data = null;
        $code = 200;
        $speds = Spending::find($id);
        if ($speds) {
            if ($speds->delete()) {
                $status = 'success';
                $message = 'delete success';
                $data = $speds;
                $this->deleteImg('/img/original/', $speds->img);
                $this->deleteImg('/img/thumbnail/', $speds->thumbnail);
            }
            else {
                $message = 'delete failed';
                $code = 400;
            }
        }
        else {
            $message = 'Spending not found';
            $code = 404;
        }
        return Response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function zoom($id)
    {
        $data = Spending::where('id', $id)->get();
        return new SpendingResource($data);
    }

    public function update(Request $request, $id)
    {
        $message = '';
        $status = 'error';
        $code = 500;
        $data = null;
        $this->validate($request, [
            'name' => 'required',
            'total' => 'required',
            'information' => 'required',
            'img' => 'sometimes'
        ]);
        $spending = Spending::find($id);
        DB::beginTransaction();
        try {
            $original = $spending->img;
            $small_thumbnail = $spending->thumbnail;
            if ($request->img != $original) {
                $rand = rand();
                $exten = '.' .explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
                $small_thumbnail = time()."_small_".$rand.$exten;
                $original = time()."_origin_".$rand.$exten;
                $img = Image::make($request->img);
                // tujuan
                $tujuan_thumbnail = 'img/thumbnail';
                $tujuan_original = 'img/original';
                //resize
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($tujuan_thumbnail.'/'.$small_thumbnail);
                //upload original size
                Image::make($request->img)->save($tujuan_original.'/'.$original);
                // delete file lama
                $this->deleteImg('/img/original/', $spending->img);
                $this->deleteImg('/img/thumbnail/', $spending->thumbnail);
            }
            $spending->user_id = auth('api')->user()->id;
            $spending->name = $request->name;
            $spending->total = $request->total;
            $spending->information = $request->information;
            $spending->img = $original;
            $spending->thumbnail = $small_thumbnail;

            if ($spending->save()) {
                DB::commit();
                $message = 'spending edited';
                $status = 'success';
                $data = $spending;
                $code = 200;
            }
            else {
                $message = 'edit failed';
                $code = 402;
            }
        }
        catch(\Exception $e) {

            $message = $e->getMessage();
            $this->deleteImg('/img/original/', $spending->img);
            $this->deleteImg('/img/thumbnail/', $spending->thumbnail);

        }
        return Response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    private function deleteImg($patch, $name)
    {
        $image_path = $_SERVER['DOCUMENT_ROOT'].$patch.$name;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
}

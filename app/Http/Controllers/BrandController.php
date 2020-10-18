<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use Validator;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();

        return view('admin.brand', ['brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_name'    =>  'required',
            'brand_description'     =>  'required',
            'brand_image'         =>  'required|image|max:2048',
            'publication_status'  => 'required'
        ]);

        $image = $request->file('brand_image');

        $new_name = rand() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('images'), $new_name);

        $form_data = array(
            'brand_name'        =>  $request->brand_name,
            'brand_description'         =>  $request->brand_description,
            'brand_image'             =>  $new_name,
            'publication_status'      => $request->publication_status
        );

      $brand = Brand::updateOrCreate($form_data);

        return response()->json(
        [      'code'=>200,
            'message'=>'Brand Created successfully',
            'data' => $brand
        ],
        200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
//        $image_name = $request->hidden_image;
//        $image = $request->file('brand_image');
//        if($image != '')
//        {
//            $rules = array(
//                'brand_name'    =>  'required',
//                'brand_description'     =>  'required',
//                'brand_image'         =>  'image|max:2048',
//                'publication_status' => 'required'
//            );
//            $error = Validator::make($request->all(), $rules);
//            if($error->fails())
//            {
//                return response()->json(['errors' => $error->errors()->all()]);
//            }
//
//            $image_name = rand() . '.' . $image->getClientOriginalExtension();
//            $image->move(public_path('images'), $image_name);
//        }
//        else
//        {
//            $rules = array(
//                'brand_name'    =>  'required',
//                'brand_description'     =>  'required',
//                'publication_status'  => 'required'
//            );
//
//            $error = Validator::make($request->all(), $rules);
//
//            if($error->fails())
//            {
//                return response()->json(['errors' => $error->errors()->all()]);
//            }
//        }
//
//        $form_data = array(
//            'brand_name'               =>   $request->brand_name,
//            'brand_description'        =>   $request->brand_description,
//            'brand_image'                    =>   $image_name,
//            'publication_status'       =>   $request->publication_status
//        );
//        Brand::whereId($request->hidden_id)->update($form_data);
//
//        return response()->json(['success' => 'Brand is successfully updated']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::find($id)->delete();
        return  response()->json(['success'=>'Brand Deleted Successfully']);

    }
}

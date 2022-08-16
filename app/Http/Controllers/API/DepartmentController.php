<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $department =Department::all();
        // $department =Department::find(2);
        // $department =Department::where('name', 'like', '%บ%')->get();
        // $department =Department::select('name', 'id')->orderBy('id', 'desc')->get();
        // $total =Department::count();
        // $department = DB::select('select * from departments order by id desc');
        $page_size = request()->query('page_size');
        $pageSize =$page_size == null ? 5 : $page_size;

        // $department = Department::paginate($pageSize);

        // $department =Department::orderBy('id', 'desc')->with(['officers'])->get();
        // // $department =Department::orderBy('id', 'desc')->with(['officers'])->paginate($pageSize);
        // $department =Department::orderBy('id', 'desc')->with(['officers' => function($query){
        //     $query->orderBy('salary', 'desc');
        // }])->paginate($pageSize);
        $department =Department::orderBy('id', 'desc')->with(['officers' => function($query){
            $query->orderBy('salary', 'desc');
        }])->get();
        return response()->json($department, 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $depart = new Department();
        $depart->name = $request->name;
        $depart->save();

        return response()->json([
            'message' => 'เพิ่มข้อมูลเรียบร้อย'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $department = Department::find($id);
      if ($department == null) {
        return response()->json([
            'errors' => [
                'status_code' => 404,
                'message' => 'ไม่พบข้อมุูล'
            ]
            ]);
      }
      return response()->json($department, 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id != $request->id) {
            return response()->json([
                'errors' => [
                    'status_code' => 400,
                    'message' => 'รหัสไม่ตรงกัน'
                ]
            ], 400);
        }

        $department = Department::find($id);
        $department->name = $request->name;
        $department->save();

        return response()->json([
            'message' => 'แก้ไขข้อมูลเรียบร้อย',
            'data' => $department
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        if ($department == null) {
          return response()->json([
              'errors' => [
                  'status_code' => 404,
                  'message' => 'ไม่พบข้อมุูล'
              ]
              ]);
        }
        //ลบ
        $department->delete();
        return response()->json([
        'message' => 'ลบข้อมูลเรียบร้อย',
        ], 200);
    }

    // ค้นหาชื่อแผนก
    public function search () {
        $query = request()->query('name');
        $keyword = '%'.$query. '%';
        $department = Department::where('name','like', $keyword)->get();

        if ($department->isEmpty()) {

              return response()->json([
              'errors' => [
                  'status_code' => 404,
                  'message' => 'ไม่พบข้อมุูล'
              ]
              ], 404);
        }

        return response()->json([
            'data' => $department,

        ], 200);
    }
}

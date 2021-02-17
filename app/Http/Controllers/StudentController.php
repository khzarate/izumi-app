<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $st = Student::latest()->paginate(5);
        $student = [];
        $x = 0;
        foreach($st as $s){
            $d = $this->get_student_courses($s->student_id);
            $student[$x]['student_id'] = $s->student_id;
            $student[$x]['student_name'] = $s->student_name;
            $student[$x]['contact_number'] = $s->contact_number;
            $student[$x]['email_address'] = $s->email_address;
            $student[$x]['address'] = $s->address;
            $student[$x]['parents'] = implode(',', $this->get_student_parents($s->student_id));
            $student[$x]['courses_enrolled'] = implode(',', $d['course_desc']);
            $x++;

        }
    
        return view('students.index', compact('student'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function get_student_parents($student_id){
        //GET THE PARENTS' NAMES
        $names = [];
        $parents = DB::table('tbl_parents')
                    ->join('tbl_child_parent', 'tbl_parents.prent_id', '=', 'tbl_child_parent.parent_id')
                    ->select('tbl_parents.parent_name')
                    ->where('tbl_child_parent.student_id', '=', $student_id)
                    ->get();

        foreach($parents as $p){
            $names[] = $p->parent_name;
        }
        return $names;
    }

    public function get_student_courses($student_id){
        //GET ENROLLED COURSES
        $courses_names = [];
        $courses = DB::table('tbl_course')
                    ->join('tbl_joined_courses', 'tbl_course.course_name', '=', 'tbl_joined_courses.course_id')
                    ->select('tbl_course.course_description', 'tbl_course.course_name')
                    ->where('tbl_joined_courses.student_id', '=', $student_id)
                    ->get();
        foreach($courses as $c){
            $courses_names['course_desc'][] = $c->course_description;
            $courses_names['course_name'][] = $c->course_name;

        }
        return $courses_names;
    }

    public function get_parents_list(){
        $parents = DB::table('tbl_parents')
                    ->select('prent_id', 'parent_name')
                    ->get();
        return $parents;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $parents = $this->get_parents_list();

        return view('students.create', compact('parents'));
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
            'student_name' => 'required',
            'contact_number' => 'required',
            'email_address' => 'required',
            'address' => 'required',
            'courses' => 'required|min:1',
            'parent_1' => 'required'
        ]);
        $check = $request->get('courses');

        Student::create($request->all());

        //GET last ID created to create record for tbl_child_parent and tbl_joined_courses
        $last_id = DB::table('tbl_students')
                    ->select('student_id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
                    ->get();
        
        foreach($last_id as $id){
            for($x = 0; $x <= 1; $x++){
                if($request->input('parent_'.($x+1)) != ""){
                    DB::table('tbl_child_parent')->insert([
                        'student_id' => $id->student_id,
                        'parent_id' => $request->input('parent_'.($x+1)),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            for($x = 0; $x <= sizeof($check)-1; $x++){
                DB::table('tbl_joined_courses')->insert([
                    'course_id' => $check[$x],
                    'student_id' => $id->student_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

        }


        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $students)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($student_id)
    {
        $st = DB::table('tbl_students')
                    ->where('student_id', '=', $student_id)
                    ->limit(1)
                    ->get();
        
        $data = [];
        foreach($st as $s){
            $d = $this->get_student_courses($s->student_id);
            $data['student_id'] = $s->student_id;
            $data['student_name'] = $s->student_name;
            $data['contact_number'] = $s->contact_number;
            $data['email_address'] = $s->email_address;
            $data['address'] = $s->address;
            $data['student_parents'] = json_encode($this->get_student_parents($s->student_id));
            $data['courses_enrolled'] = json_encode($d['course_name']);
            $data['parents_list'] = $this->get_parents_list();
        }


        return view('students.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $student_id)
    {
        $request->validate([
            'student_name' => 'required',
            'contact_number' => 'required',
            'email_address' => 'required',
            'address' => 'required',
            'courses' => 'required|min:1',
            'parent_1' => 'required'
        ]);
        $check = $request->get('courses');

        DB::table('tbl_students')
            ->where('student_id', '=', $student_id)
            ->update(['student_name' => $request->input('student_name'), 'contact_number' => $request->input('contact_number'), 'email_address' => $request->input('email_address'), 'address' => $request->input('address'), 'updated_at' => date('Y-m-d H:i:s')]);

        DB::table('tbl_child_parent')
            ->where('student_id', '=', $student_id)
            ->delete();

        for($x = 0; $x <= 1; $x++){
            if($request->input('parent_'.($x+1)) != ""){
                DB::table('tbl_child_parent')->insert([
                    'student_id' => $student_id,
                    'parent_id' => $request->input('parent_'.($x+1)),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        DB::table('tbl_joined_courses')
            ->where('student_id', '=', $student_id)
            ->delete();

        for($x = 0; $x <= sizeof($check)-1; $x++){
            DB::table('tbl_joined_courses')->insert([
                'course_id' => $check[$x],
                'student_id' => $student_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }



        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($student_id)
    {   
        DB::table('tbl_students')->where('student_id', $student_id)->delete();
        DB::table('tbl_child_parent')->where('student_id', $student_id)->delete();

        //for tbl_joined_courses, just update the status of the is_active column
        DB::table('tbl_joined_courses')->where('student_id', $student_id)->update(['is_active' => false]);

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }
}

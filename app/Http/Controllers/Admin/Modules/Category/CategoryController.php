<?php

namespace App\Http\Controllers\Admin\Modules\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use Carbon\Carbon;
use Session;
use Validator;
use File;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    public function index(Request $request) {
        $data['category'] = Category::orderBy('name','asc')->where('status','!=', 'D');
        if(!empty(@$request->all())){
            if(!empty(@$request->keyword)){
                $data['category'] = $data['category']->where(function($query) use ($request){
                    $query->where('name', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
                    ->orWhere('description', 'Like', '%' . @$request->keyword .'%')
                    ->orwhereHas('parentCategory', function($qey)  use ($request){
                        $qey->where('name', 'Like', '%' . @$request->keyword .'%')
                        ->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
                        ->orWhere('description', 'Like', '%' . @$request->keyword .'%')
                        ->orwhereHas('parentCategory', function($qe)  use ($request){
                            $qe->where('name', 'Like', '%' . @$request->keyword .'%')
                            ->orWhere('slug', 'Like', '%' . @$request->keyword .'%')
                            ->orWhere('description', 'Like', '%' . @$request->keyword .'%');
                        });
                    });
                });
            }
            if(!empty(@$request->is_parent)){
                if(@$request->is_parent == 'Y'){
                    $data['category'] = $data['category']->where('parent_id',0);
                }
                else{
                    $data['category'] = $data['category']->where('parent_id','!=',0);
                }
            }

        }
        $data['category'] = $data['category']->paginate(10);
        return view('admin.modules.category.category',$data);
    }

    
    public function create() {
        // $data['category'] = Category::where('status', 'A')->whereIn('level', [1])->orderBy('name','asc')->get();
        return view('admin.modules.category.add_category');
    }

    
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('categories')->where(function($query) use ($request){
                    $query->where('status','!=','D')->where('_id','!=',@$request->rowid);
                })
            ],
            'parent_id' => 'nullable',
            'image' => 'nullable|image'
        ]);
        // dd($request->all());
        try {
            if(!empty(@$request->parent_id)){
                if(!empty(@$request->parent_sub_category_id)){
                    $parentCategory = Category::where('_id',@$request->parent_sub_category_id)->where('status','!=','D')->first();
                }
                else{
                    $parentCategory = Category::where('_id',@$request->parent_id)->where('status','!=','D')->first();
                }
                if(!empty(@$parentCategory)){
                    if(@$parentCategory->level == 1){
                        $level = 2;
                    }
                    else{
                        $level = 3;
                    }
                }
                else{
                    $level = 1;
                }
            }
            else{
                $level = 1;
            }
            if (@$request->hasFile('image')) {

               if(!empty(@$request->rowid)){
                $project =  Category::where('_id',@$request->rowid)->first();
                if (File::exists("storage/category/" . @$project->image)) {
                    File::delete("storage/category/" . @$project->image);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->storeAs('public/category', @$filename);

            $new['image'] = @$filename;
        }
        $new['name'] = @$request->name;
        $new['slug'] = Str::slug(@$request->name);
        $new['status'] = 'A';
        if(empty(@$request['rowid'])){
            if(!empty(@$request->parent_sub_category_id)){
                $new['parent_id'] = @$request->parent_sub_category_id ? @$request->parent_sub_category_id : 0;
            }else{
                $new['parent_id'] = @$request->parent_id ? @$request->parent_id : 0;
            }
            $new['level'] = @$level;
        }
        $new['description'] = @$request->description ? nl2br(@$request->description): null;
        $data = Category::updateOrCreate(['_id' => @$request['rowid']], $new);
        if(@$data){
            if(!empty(@$request->rowid)){
                Session::flash('success','Category updated successfully!');
            }
            else{
                Session::flash('success','Category created successfully!');
            }
        } else {
            Session::flash('error','Sorry a problem has occurred.');
        }
        return redirect()->back();
    } catch(\Exception $e) {
        return $e->getMessage();
    }
}

   
    public function edit($id=null) {
        try{
            $data['category_data'] = $categoryData = Category::where('_id',@$id)->where('status','!=','D')->first();
            // $data['category'] = Category::where('status', 'A')->whereIn('level', ['1'])->orderBy('name','asc')->get();
            if(!empty(@$categoryData)){
                return view('admin.modules.category.add_category',$data);
            } else {
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

   
    public function update(Request $request) {
        dd($request->all());
    }

   
    public function delete($id) {
        try{
            $category = Category::where('_id',@$id)->where('status','!=','D')->first();
            if(!empty(@$category)){
                $checkExistSubCategory = Category::where('parent_id',@$category->_id)->where('status','!=','D')->count();
                if(@$checkExistSubCategory > 0){
                    Session::flash('error',"Category can't be deleted. Because this category is associated with some sub-categories.");
                }
                else{
                    @$category->update(['status' => 'D']);
                    Session::flash('success',"Category deleted successfully");
                }
            }else{
                Session::flash('error','Sorry a problem has occurred.');
            }
        }
        catch(\Exception $e) {
            Session::flash('error',@$e->getMessage());
        }
        return redirect()->back();
    }

   
    public function status($id) {
        $data = Category::where('_id',@$id)->first();
        if($data->status=='A') {
            Category::where('_id',@$id)->update(['status' => 'I']);
        } else {
            Category::where('_id',@$id)->update(['status' => 'A']);
        }
        Session::flash('success',"Category status change successfully");
        return redirect()->back();
    }
    public function getSubcategory(Request $request){
        try{
            $catData = Category::where('parent_id',@$request->category_id)->where('status','!=','D')->orderBy('name','asc')->get();
            if(@$catData->isNotEmpty()){
                // $result = '<option value="">Select Sub Category</option>';
                $result = '';
                foreach(@$catData as $cat){
                    $result .= '<option value='.@$cat->_id.'>'.@$cat->name.'</option>';
                }
                return response()->json([
                    'data' => [
                        "status"  => 'success',
                        'html' => @$result
                    ],
                ], 200);
            }
            else{
                return response()->json([
                    'data' => [
                        "status"  => 'error',
                        "message" => 'Sorry a problem has occurred.',
                    ],
                ], 200);
            }
        }
        catch(\Exception $e) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => @$e->getMessage(),
                ],
            ], 200);
        }
    }
}

<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Filters\CategoryFilter\ParentFilter;
use App\Http\Repository\CategoryRepository;
use App\Http\Requests\CategoryRequest;
use App\Http\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * categoryRepository
     *
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * categoryService
     *
     * @var CategoryService
     */
    private $categoryService;

    /**
     * __construct
     * inject needed data in constructor
     * @param  CategoryRepository $categoryRepository
     * @param  CategoryService $categoryService
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository, CategoryService $categoryService)
    {
        $this->categoryRepository    = $categoryRepository;
        $this->categoryService       = $categoryService;
    }

    /**
     * get all category
     *
     * @return View
     */
    public function index(Request $request)
    {
        $parentTitle = '';
        if($request->filled('parent_id')) {
            $parentTitle  = $this->categoryRepository->where("id",$request->parent_id)->first()->title;
            $categorys = $this->categoryRepository->filter($this->categoryFilter())->get();
        } else {
            $categorys = $this->categoryRepository->filter($this->categoryFilter())->parent()->get();
        }

    	return view('category.index',compact('categorys','parentTitle'));
    }

    /**
     * get page for create category
     *
     * @return View
     */
    public function create()
    {
        $category = null;
        $parents = $this->categoryRepository->parent()->get();
    	return view('category.form',compact('category','parents'));
    }

    /**
     * store Category Data
     *
     * @param  CategoryStoreRequest $request
     * @return Redirect
     */
    public function store(CategoryRequest $request)
    {
    	$category = $this->categoryService->handle($request->validated());
    	$request->session()->flash('success', 'Created Successfully');
        if($request->has('parent_id')) {
            return redirect('category?parent_id='.$request->parent_id.'');
        }
        return redirect('category');
    }

    /**
     * get page for update category
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
    	$category = $this->categoryRepository->find($id);
    	$parents = $this->categoryRepository->parent()->get();
    	return view('category.form',compact('category','parents'));
    }

    /**
     * update Category Data
     *
     * @param  int $id
     * @param  CategoryUpdateRequest $request
     * @return redirect
     */
    public function update($id,CategoryRequest $request)
    {
    	$this->categoryService->handle($request->validated(), $id);
        $request->session()->flash('success', 'Updated Successfully');
        if($request->has('parent_id')) {
            return redirect('category?parent_id='.$request->parent_id.'');
        }
    	return redirect('category');
    }

    /**
     * remove category data
     *
     * @param  int $id
     * @return redirect
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);
        $this->categoryRepository->destroy($id);
    	\Session::flash('success', 'Deleted Successfully');
        if($category->parent_id) {
            return redirect('category?parent_id='.$category->parent_id.'');
        }
    	return redirect('category');
    }

    /**
     * Method categoryFilter
     *
     * @return array
     */
    public function categoryFilter()
    {
        return [
            'parent_id' => new ParentFilter
        ];
    }
}

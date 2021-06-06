<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Repository\CategoryRepository;
use App\Http\Repository\ContentRepository;
use App\Http\Repository\ContentTypeRepository;
use App\Http\Requests\ContentRequest;
use App\Http\Services\ContentService;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * contentRepository
     *
     * @var ContentRepository
     */
    private $contentRepository;
    /**
     * contentService
     *
     * @var ContentService
     */
    private $contentService;
    /**
     * contentTypeRepository
     *
     * @var ContentTypeRepository
     */
    private $contentTypeRepository;

    /**
     * categoryRepository
     *
     * @var categoryRepository
     */
    private $categoryRepository;
    /**
     * __construct
     *
     * @param  ContentRepository $contentRepository
     * @param  ContentTypeRepository $contentTypeRepository
     * @param  ContentService $contentService
     * @param  CategoryRepository $categoryRepository
     * @return void
     */
    public function __construct(
        ContentRepository $contentRepository,
        ContentTypeRepository $contentTypeRepository,
        CategoryRepository $categoryRepository,
        ContentService $contentService)
    {
        $this->contentRepository = $contentRepository;
        $this->contentService = $contentService;
        $this->contentTypeRepository = $contentTypeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * index
     * get all content data
     * @return View
     */
    public function index()
    {
        return view('content.index');
    }

    public function allData(Request $request)
    {
      $contents = Content::with(['type', 'category'])->withCount(['rbt_operators', 'operators']);

      if($request->filled("category_id")){
        $contents = $contents->where('category_id', $request->category_id);
      }

      return \DataTables::eloquent($contents)
        ->addColumn('index', function(Content $content) {
            return '<input class="select_all_template" type="checkbox" name="selected_rows[]" value="{{$content->id}}" class="roles" onclick="collect_selected(this)">';
        })
        ->addColumn('id', function(Content $content) {
            return $content->id;
        })
        ->addColumn('title', function(Content $content) {
            return $content->title;
        })
        ->addColumn('content', function(Content $content) {
            if($content->type->id == 1)
                return $content->path;
            elseif($content->type->id == 2)
                return $content->path;
            elseif($content->type->id == 3)
                return '<img src="'.url(isset($content->path)?$content->path: '').'" alt="" style="width:250px" height="200px">';
            elseif($content->type->id == 4)
                return '<audio controls src="'.url(isset($content->path)?$content->path: '').'" style="width:100%"></audio>';
            elseif($content->type->id == 5)
                return '<video src="'.url(isset($content->path)?$content->path: '').'" style="width:250px;height:200px" height="200px" controls poster="'.url(isset($content->image_preview)?$content->image_preview: '').'"></video>';
            elseif($content->type->id == 6)
                return '<iframe src="'.$content->path.'" width="250px" height="200px"></iframe>';
            elseif($content->type->id == 7)
                return '<a href="'.$content->path.'">'.$content->path.'</a>' ;
        })
        ->addColumn('content_type', function(Content $content) {
            return $content->type->title;
        })
        ->addColumn('Category', function(Content $content) {
            if(isset($content->category))
              return $content->category->title;
        })
        ->addColumn('patch number', function(Content $content) {
              return $content->patch_number;
        })
        ->addColumn('action', function(Content $content) {
          $value = $content;
          return view('content.action', compact('value'))->render();
        })
        ->escapeColumns([])
        ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  View
     */
    public function create()
    {
        $content = null;
        $content_types = $this->contentTypeRepository->all();
        $categorys = $this->categoryRepository->all();
        return view('content.form',compact('content_types','content','categorys'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $content = $this->contentRepository->with('type')->findOrfail($id);
      return view('content.show_post',compact('content'));
    }

    /**
     * store content data
     *
     * @param  ContentRequest $request
     * @return Redirect
     */
    public function store(ContentRequest $request)
    {
        $this->contentService->handle($request->validated());

        $request->session()->flash('success', 'Content created successfull');

        return redirect('content');
    }

    /**
     * Show the form for editing the specified resource.
     * @param   int  $id
     * @return  View
     */
    public function edit($id)
    {
        $content = $this->contentRepository->with('type')->findOrfail($id);
        $content_types = $this->contentTypeRepository->all();
        $categorys = $this->categoryRepository->all();
        return view('content.form',compact('content_types','content','categorys'));
    }

    /**
     * update
     *
     * @param  int $id
     * @param  ContentRequest $request
     * @return Redirect
     */
    public function update($id, ContentRequest $request)
    {
        $content = $this->contentRepository->findOrfail($id);

        $this->contentService->handle($request->validated(), $id);

        $request->session()->flash('success', 'updated successfully');

        return redirect('content');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = $this->contentRepository->findOrfail($id);

        $content->delete();

        \Session::flash('success', 'deleted successfully');

        return back();
    }
}

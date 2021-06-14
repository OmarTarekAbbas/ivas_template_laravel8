<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Repository\CountryRepository;
use App\Http\Repository\OperatorRepository;
use App\Http\Requests\OperatorRequest;
use App\Http\Requests\OperatorStoreRequest;
use App\Http\Requests\OperatorUpdateRequest;
use App\Http\Services\OperatorService;

class OperatorController extends Controller
{
    /**
     * operatorRepository
     *
     * @var OperatorRepository
     */
    private $operatorRepository;
    /**
     * countryRepository
     *
     * @var CountryRepository
     */
    private $countryRepository;
    /**
     * operatorService
     *
     * @var OperatorService
     */
    private $operatorService;

    /**
     * __construct
     * inject needed data in constructor
     * @param  OperatorRepository $operatorRepository
     * @param  OperatorRepository $countryRepository
     * @param  CountryRepository $operatorService
     * @return void
     */
    public function __construct(OperatorService $operatorService, OperatorRepository $operatorRepository,CountryRepository $countryRepository)
    {
        $this->get_privilege();

        $this->operatorRepository    = $operatorRepository;
        $this->countryRepository    = $countryRepository;
        $this->operatorService  = $operatorService;

    }
    /**
     * index
     *
     * @return View
     */
    public function index()
    {
        $operators = $this->operatorRepository->withCount(['rbts', 'posts'])->get();
        return view('operator.index',compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $countrys = $this->countryRepository->all();
        $operator = null;
        return view('operator.form',compact('countrys','operator'));
    }

    /**
     * store
     *
     * @param  OperatorRequest $request
     * @return Redirect
     */
    public function store(OperatorStoreRequest $request)
    {
        $this->operatorService->handle($request->validated());
        $request->session()->flash('success', 'Created Successfully');
        return redirect('operator');
    }

    /**
     * show
     *
     * @param  int $id
     * @return View
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $operator = $this->operatorRepository->find($id);
        $countrys = $this->countryRepository->all();
        return view('operator.form',compact('countrys','operator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OperatorRequest $request
     * @param  int $id
     * @return Redirect
     */
    public function update(OperatorUpdateRequest $request, $id)
    {
        $this->operatorService->handle($request->validated(), $id);
        $request->session()->flash('success', 'Created Successfully');
        return redirect('operator');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        $operator = $this->operatorRepository->find($id);
        $operator->delete();
        session()->flash('success', 'Deleted Successfully');
        return redirect('operator');
    }
}

<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Repository\CountryRepository;
use App\Http\Requests\CountryStoreRequest;
use App\Http\Requests\CountryUpdateRequest;
use App\Http\Services\CountryService;
use Illuminate\Support\Facades\Session;
use Config;

class CountryController extends Controller
{
    /**
     * countryRepository
     *
     * @var CountryRepository
     */
    private $countryRepository;
    /**
     * countryService
     *
     * @var CountryService
     */
    private $countryService;

    /**
     * __construct
     * inject needed data in constructor
     * @param  CountryRepository $countryRepository
     * @param  CountryService $countryService
     * @return void
     */
    public function __construct(CountryRepository $countryRepository, CountryService $countryService)
    {
        $this->get_privilege();
        $this->countryRepository    = $countryRepository;
        $this->countryService    = $countryService;
    }

    /**
     * get all country
     *
     * @return View
     */
    public function index()
    {
    	$countrys = $this->countryRepository->all();
    	return view('country.index',compact('countrys'));
    }

    /**
     * get page for create country
     *
     * @return View
     */
    public function create()
    {
        $country = null;
    	return view('country.form',compact('country'));
    }

    /**
     * store Country Data
     *
     * @param  CountryStoreRequest $request
     * @return Redirect
     */
    public function store(CountryStoreRequest $request)
    {
    	$country = $this->countryService->handle($request->validated());
    	$request->session()->flash('success', 'Created Successfully');
    	return redirect('country');
    }

    /**
     * get page for update country
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
    	$country = $this->countryRepository->find($id);
    	return view('country.form',compact('country'));
    }

    /**
     * update Country Data
     *
     * @param  int $id
     * @param  CountryUpdateRequest $request
     * @return redirect
     */
    public function update($id,CountryUpdateRequest $request)
    {
        // dd($id);
    	$this->countryService->handle($request->validated(), $id);
    	$request->session()->flash('success', 'Updated Successfully');
    	return redirect('country');
    }

    /**
     * remove country data
     *
     * @param  int $id
     * @return redirect
     */
    public function delete($id)
    {
    	$this->countryRepository->destroy($id);
    	\Session::flash('success', 'Deleted Successfully');
    	return redirect('country');
    }
}

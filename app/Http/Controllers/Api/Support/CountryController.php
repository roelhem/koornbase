<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Resources\Api\Support\CountryResource;
use CommerceGuys\Addressing\Country\CountryRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{

    use HasCollectablePaginate;


    public function index(Request $request, CountryRepositoryInterface $countryRepository) {
        $countries = collect($countryRepository->getAll($request->query('locale', 'NL')));

        return CountryResource::collection($this->paginate($countries, $request));
    }

    public function show(Request $request, $country_code, CountryRepositoryInterface $countryRepository) {
        $country = $countryRepository->get($country_code, $request->query('locale', 'NL'));

        return new CountryResource($country);
    }

}

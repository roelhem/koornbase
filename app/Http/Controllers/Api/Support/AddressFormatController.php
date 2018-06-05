<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Resources\Api\Support\AddressFormatResource;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressFormatController extends Controller
{

    use HasCollectablePaginate;

    /**
     * Gets a list of all the addressFormats
     *
     * @param Request $request
     * @param AddressFormatRepositoryInterface $addressFormatRepository
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, AddressFormatRepositoryInterface $addressFormatRepository) {
        $items = collect($addressFormatRepository->getAll());

        return AddressFormatResource::collection($this->paginate($items, $request));
    }

    /**
     * Shows one specific AddressFormat
     *
     * @param Request $request
     * @param $country_code
     * @param AddressFormatRepositoryInterface $addressFormatRepository
     * @return AddressFormatResource
     */
    public function show(Request $request, $country_code, AddressFormatRepositoryInterface $addressFormatRepository) {
        $addressFormat = $addressFormatRepository->get($country_code);
        return new AddressFormatResource($addressFormat);
    }

}

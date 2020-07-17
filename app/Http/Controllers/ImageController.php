<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Values\Image;
use App\Actions\Image\{
    ApplyFilterAction,
    GetFiltersAction,
};

class ImageController extends Controller
{
    private ApplyFilterAction $applyFilter;
    private GetFiltersAction $getFilters;

    public function __construct(
        ApplyFilterAction $applyFilter,
        GetFiltersAction $getFilters
    ) {
        $this->applyFilter = $applyFilter;
        $this->getFilters = $getFilters;
    }

    public function updateImage(Request $request)
    {
        $image = new Image(
            $request->imageId,
            $request->src,
        );
        $result = $this->applyFilter->execute($image, $request->filter);

        return response()->json($result->toArray(), 200);
    }

    public function filters()
    {
        return response()->json(
            $this->getFilters->execute(),
            200
        );
    }
}

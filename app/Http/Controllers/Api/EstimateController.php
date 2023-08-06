<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EstimateService;

use App\Services\HttpService;
use App\Services\JWTAuthService; // Add the JwtAuthService namespace
use App\Helpers\PayloadHelper; // Import the PayloadHelper

class EstimateController extends Controller
{
    private $estimateService;

    public function __construct(EstimateService $estimateService)
    {
        $this->estimateService = $estimateService;
    }

    public function checkEstimateDates(Request $request): \Illuminate\Http\JsonResponse
    {
        $requestData = $request->all();

        $result = $this->estimateService->checkEstimateDates($requestData);

        if ($result !== null) {
            return response()->json($result);
        } else {
            return response()->json(['error' => 'Failed to get estimate dates'], 500);
        }
    }
    public function checkEstimateRates(Request $request)
    {
        $requestData = $request->all();

        $shippingFee = $this->estimateService->checkEstimateRates($requestData);

        if ($shippingFee !== null) {
            return response()->json($shippingFee);
        } else {
            return response()->json(['error' => 'Failed to retrieve shipping fee'], 500);
        }
    }
    public function getCombinedEstimates(Request $request)
    {
        $requestData = $request->all();

        $combinedEstimates = $this->estimateService->getCombinedEstimates($requestData);

        if ($combinedEstimates !== null) {
            return response()->json($combinedEstimates);
        } else {
            return response()->json(['error' => 'Failed to retrieve combined estimates'], 500);
        }
    }
}

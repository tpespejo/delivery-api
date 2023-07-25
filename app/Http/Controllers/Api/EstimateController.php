<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HttpService;
use App\Http\Controllers\Api\TokenController;

class EstimateController extends Controller
{

    public function checkEstimateDates(Request $request)
{
    try {
        $tokenController = new TokenController();
        $bearerToken = $tokenController->generateBearerToken();

        if ($bearerToken === null) {
            return response()->json(['error' => 'Failed to generate bearer token'], 500);
        }

        $httpService = new HttpService();

        // Set the headers for the POST request
        $headers = [
            'Authorization' => 'Bearer ' . $bearerToken,
            'Accept' => 'application/json',
        ];

        // Prepare the payload
        $data = [
            "data" => [
                "attributes" => $request->all()
            ]
        ];

        // Make a POST request with the authentication header and payload using the HttpService instance
        $response = $httpService->post('https://api.staging.quadx.xyz/v2/orders/estimates/dates', $data, $headers);

        // Check if the API call was successful
            if ($response->successful()) {
                // Get the JSON response
                $responseData = $response->json();
                // Extract the pickup and delivery dates from the JSON response
                 // Assuming the pickup date and estimated delivery dates are available in the 'data' key
                $pickupDate = $responseData['data']['attributes']['pickup_date'];
                $estimatedDeliveryDate = $responseData['data']['attributes']['estimated_delivery_date'];
    
                return response()->json([
                    'pickupDate' => $pickupDate,
                    'estimatedDeliveryDate' => $estimatedDeliveryDate,
                ]);
        } else {
            return response()->json(['error' => 'Failed to get estimate dates'], 500);
        }
    } catch (\Exception $e) {
        // Exception occurred, handle the error and return 500 Internal Server Error
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

public function checkEstimateRates(Request $request)
{
    try {
        $tokenController = new TokenController();
        $bearerToken = $tokenController->generateBearerToken();

        if ($bearerToken === null) {
            return response()->json(['error' => 'Failed to generate bearer token'], 500);
        }

        $httpService = new HttpService();

        // Set the headers for the POST request
        $headers = [
            'Authorization' => 'Bearer ' . $bearerToken,
            'Accept' => 'application/json',
        ];

        // Prepare the payload
        $data = [
            "data" => [
                "attributes" => $request->all()
            ]
        ];

        // Make a POST request with the authentication header and payload using the HttpService instance
        $response = $httpService->post('https://api.staging.quadx.xyz/v2/orders/estimates/rates', $data, $headers);

        // Check if the API call was successful
        if ($response->successful()) {
            // Get the JSON response
            $responseData = $response->json();

            // Check if the service_type is "next_day"
            $serviceType = $request->input('service_type');
            if ($serviceType === 'next_day') {
                // Set shipping_fee to null if service_type is "next_day"
                $shippingFee = null;
            } else {
                // Extract the shipping fee from the JSON response
                // Assuming the shipping fee is available in the 'data' key
                $shippingFee = $responseData['data']['attributes']['shipping_fee'];
            }
            
            return response()->json(['shipping_fee' => $shippingFee]);
            
        } else {
            return response()->json(['error' => 'Failed to retrieve shipping fee'], 500);
        }
    } catch (\Exception $e) {
        // Exception occurred, handle the error and return 500 Internal Server Error
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
    public function getCombinedEstimates(Request $request) {
       
        try {
            $estimateDatesResponse = $this->checkEstimateDates($request);
        $estimateRatesResponse = $this->checkEstimateRates($request);

        // Check if both responses are successful
        if ($estimateDatesResponse->getStatusCode() == 200 && $estimateRatesResponse->getStatusCode() == 200) {
            // Get the JSON data from each response as objects
            $estimateDatesData = $estimateDatesResponse->getData();
            $estimateRatesData = $estimateRatesResponse->getData();

            // Access object properties using the correct syntax
            $pickupDate = $estimateDatesData->pickupDate;
            $estimatedDeliveryDate = $estimateDatesData->estimatedDeliveryDate;
            $shippingFee = $estimateRatesData->shipping_fee;

            // Merge the data into a single object
            $mergedData = (object) [
                'pickupDate' => $pickupDate,
                'estimatedDeliveryDate' => $estimatedDeliveryDate,
                'shippingFee' => $shippingFee,
            ];

            return response()->json($mergedData);
        } else {
            // Handle the case where one or both of the requests were not successful
            return response()->json(['error' => 'Failed to retrieve estimated pick up and delivery dates and estimated shipping fee rate'], 500);
        }

        } catch (\Exception $e) {
            // Exception occurred, handle the error and return 500 Internal Server Error
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

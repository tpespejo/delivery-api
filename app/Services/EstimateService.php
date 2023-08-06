<?php

namespace App\Services;

use App\Services\HttpService;
use App\Services\JWTAuthService;
use App\Helpers\PayloadHelper;

class EstimateService
{
    public function checkEstimateDates($requestData)
    {
        try {
            $jwtAuthService = new JwtAuthService();
            $bearerToken = $jwtAuthService->generateBearerToken();

            if ($bearerToken === null) {
                return null;
            }

            $httpService = new HttpService();

            // Set the headers for the POST request
            $headers = [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Accept' => 'application/json',
            ];

            // Prepare the payload
            $data = PayloadHelper::preparePayload($requestData);

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

                return [
                    'pickupDate' => $pickupDate,
                    'estimatedDeliveryDate' => $estimatedDeliveryDate,
                ];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Exception occurred, handle the error and return 500 Internal Server Error
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function checkEstimateRates($requestData)
    {
        try {
            $jwtAuthService = new JwtAuthService();
            $bearerToken = $jwtAuthService->generateBearerToken();

            if ($bearerToken === null) {
                return null;
            }

            $httpService = new HttpService();

            // Set the headers for the POST request
            $headers = [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Accept' => 'application/json',
            ];

            // Prepare the payload
            $data = PayloadHelper::preparePayload($requestData);

            // Make a POST request with the authentication header and payload using the HttpService instance
            $response = $httpService->post('https://api.staging.quadx.xyz/v2/orders/estimates/rates', $data, $headers);

            // Check if the API call was successful
            if ($response->successful()) {
                // Get the JSON response
                $responseData = $response->json();

                // Check if the service_type is "next_day"
                $serviceType = $requestData['service_type'];
                if ($serviceType === 'next_day') {
                    // Set shipping_fee to null if service_type is "next_day"
                    $shippingFee = null;
                } else {
                    // Extract the shipping fee from the JSON response
                    // Assuming the shipping fee is available in the 'data' key
                    $shippingFee = $responseData['data']['attributes']['shipping_fee'];
                }

                return [
                    'shipping_fee' => $shippingFee
                ];

            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Exception occurred, handle the error and return 500 Internal Server Error
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getCombinedEstimates($requestData)
    {
        try {
            $estimateDatesResponse = $this->checkEstimateDates($requestData);
            $estimateRatesResponse = $this->checkEstimateRates($requestData);

            // Check if both responses are successful
            if ($estimateDatesResponse !== null && $estimateRatesResponse !== null) {
                $pickupDate = $estimateDatesResponse['pickupDate'];
                $estimatedDeliveryDate = $estimateDatesResponse['estimatedDeliveryDate'];
                $shippingFee = $estimateRatesResponse['shipping_fee'];

                return [
                    'pickupDate' => $pickupDate,
                    'estimatedDeliveryDate' => $estimatedDeliveryDate,
                    'shippingFee' => $shippingFee,
                ];
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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PostmanData;

class StorePostmanData extends Command
{
    protected $signature = 'postman:store';

    public function handle()
    {
        // Read the JSON files
        $requestsData = file_get_contents('path/to/requests.json');
        $responsesData = file_get_contents('path/to/responses.json');

        // Convert JSON to PHP array
        $requestsArray = json_decode($requestsData, true);
        $responsesArray = json_decode($responsesData, true);

        // Store the data in the database
        foreach ($requestsArray as $index => $requestData) {
            PostmanData::create([
                'request_data' => json_encode($requestData),
                'response_data' => json_encode($responsesArray[$index]),
            ]);
        }

        $this->info('Data stored successfully.');
    }
}
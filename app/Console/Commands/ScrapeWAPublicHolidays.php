<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\PublicHoliday;
use App\Models\Region;

class ScrapeWAPublicHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:wa-public-holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape public holidays from WA Government website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://www.wa.gov.au/service/employment/workplace-arrangements/public-holidays-western-australia';
        $httpClient = new Client([
            'timeout' => 60,
        ]);

        $this->info('Fetching data from ' . $url);

        try {
            $response = $httpClient->request('GET', $url);
            $html = (string) $response->getBody();
        } catch (\Exception $e) {
            $this->error('Failed to fetch data: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info('Parsing public holidays table');

        $crawler = new Crawler($html);

        // Find the table
        $table = $crawler->filterXPath('//table');
        // $this->info('HTML:');
        // $this->line($table->html());
        // Initialize an array to store the data
        $publicHolidays = [];

        // Extract the years from the first thead tag
        $years = $table->filter('thead:first-of-type th')->each(function (Crawler $node, $i) {
            $text = trim($node->text());
            return $i > 0 && is_numeric($text) ? $text : null; // Skip the first column and non-numeric values
        });
        $years = array_slice(array_filter($years), 0, 3); // Remove null values and only return the first three cells

        // Extract holiday names and dates
        $results = [];
        $crawler->filter('tbody tr')->each(function (Crawler $row) use (&$results, $years) {
            // Ensure the row has data
            if ($row->filter('th')->count() === 0 || $row->filter('td')->count() === 0) {
                return;
            }

            $holidayName = trim($row->filter('th')->text());
            $this->info('Extracting public holiday: ' . $holidayName);
            $dates = $row->filter('td')->each(function (Crawler $cell, $i) use ($years) {
                $this->info("cell text: ". $cell->text());
                //clean up cell text
                $cellText = strip_tags($cell->text());
                $cleanText = preg_replace('/[^A-Za-z0-9\s]/', '', $cellText);

                $dateString = $cleanText." ". $years[$i];
                $date = \Carbon\Carbon::createFromFormat('Y j F', $dateString);
            });

            foreach ($dates as $yearDates) {
                foreach ($yearDates as $fullDate) {
                    $results[] = [
                        'holiday' => $holidayName,
                        'date' => $fullDate,
                    ];
                }
            }
        });

        // Output the results
        foreach ($results as $result) {
            echo "Holiday: {$result['holiday']}, Date: {$result['date']}" . PHP_EOL;
        }

        // // Remove null values
        // $holidays = array_filter($holidays);

        // // Ensure the region exists in the database
        // $region = Region::firstOrCreate(['name' => 'APAC-West']);

        // // Save the holidays to the database
        // foreach ($holidays as $holiday) {
        //     $this->info('Saving public holiday: ' . $holiday['name'] . " date: " . $holiday['date']);
        //     PublicHoliday::updateOrCreate(
        //         [
        //             'date' => \Carbon\Carbon::parse($holiday['date'])->format('Y-m-d'),
        //             'name' => $holiday['name'],
        //             'region_id' => $region->id,
        //         ],
        //         [
        //             'region_id' => $region->id,
        //         ]
        //     );
        // }

        $this->info('Public holidays saved to the database');

        return Command::SUCCESS;
    }

    /**
     * Parse the date text and return a formatted date.
     *
     * @param string $dateText
     * @param string $year
     * @return string|null
     */
    private function parseDate($dateText, $year)
    {
        // Remove any HTML entities
        $dateText = html_entity_decode($dateText);

        // Split the date text into parts
        $parts = preg_split('/\s+/', $dateText);

        if (count($parts) < 2) {
            return null;
        }

        $day = $parts[1];
        $monthName = $parts[0];

        // Map month names to month numbers
        $months = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        ];

        if (!isset($months[$monthName])) {
            return null;
        }

        $month = $months[$monthName];

        // Return the formatted date
        return \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
    }
}

<?php

namespace Tests\Feature\ThirdParty\VersionControlSystems\Search\SearchRepositories\Providers\GitHub;

use Mockery;
use Carbon\Carbon;
use Tests\TestCase;
use App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories\Providers\GitHub;
use App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories\SearchRepositoryContract;

/**
 * Class SearchRepositoriesTest
 * @group Search, GitHub
 */
class SearchRepositoriesTest extends TestCase
{
    /** @test */
    public function it_can_search_github_repositories_successfully()
    {
        // Mock the SearchRepositoryContract with instance of GitHub class
        $this->instance(SearchRepositoryContract::class, Mockery::mock(GitHub::class, function ($mock) {
            $mock->shouldReceive('validate')->once()
                ->andReturn(true)
                ->shouldReceive('search')->once()
                ->andReturn([
                    "status"  => true,
                    "message" => "Success",
                ]);
        }));

        $this->json('get', 'api/v1/search/repositories', [

                'created'  => Carbon::now()->firstOfMonth()->format('Y-m-d'),
                'sort'     => 'stars',
                'order'    => 'desc',
                'per_page' => 1,
                'language' => 'php'
        ])
            ->assertJson([
                'status'      => true,
                'message'     => 'Success',
            ]);
    }

    /**
     * @test
     * @dataProvider validationProvider
     *
     * @param $request_data | The request that you want to Post|Get by.
     * @param $form_input | The Form Key Name.
     * @param $validation_error_message | The expected validation error message for the given form input.
     */
    public function it_returns_validation_error($request_data, $form_input, $validation_error_message)
    {
        $this->json('get', 'api/v1/search/repositories', $request_data)
            ->assertJson([
                'status'      => false,
                'message'     => 'validation_error',
                'errors'      => $validation_error_message,
            ]);
    }

    /**
     * Data provider method that contain keys names and the relative
     * validation message after looping on each key and get the error message
     *
     * @return array
     */
    public function validationProvider(): array
    {
        return [

            // Test created input
            'created is empty' => [
                'request_data'             => [
                    'created' => '',
                ],
                'form_input'               => 'created',
                'validation_error_message' => [
                    "The created field is required when none of sort / order / language are present.",
                    "The sort field is required when none of created / order / language are present.",
                    "The order field is required when none of created / sort / language are present.",
                    "The language field is required when none of created / sort / order are present."
                ],
            ],
            'created is not [date]' => [
                'request_data'             => [
                    'created' => 'random-string-value',
                ],
                'form_input'               => 'created',
                'validation_error_message' => [
                    "The created is not a valid date.",
                    "The created does not match the format Y-m-d.",
                ],
            ],
            'created is wrong [date_format]' => [
                'request_data'             => [
                    'created' => '10-03-2021',
                ],
                'form_input'               => 'created',
                'validation_error_message' => [
                    "The created does not match the format Y-m-d.",
                ],
            ],

            // Test sort input
            'sort is empty' => [
                'request_data'             => [
                    'sort' => '',
                ],
                'form_input'               => 'sort',
                'validation_error_message' =>
                    [
                        "The created field is required when none of sort / order / language are present.",
                        "The sort field is required when none of created / order / language are present.",
                        "The order field is required when none of created / sort / language are present.",
                        "The language field is required when none of created / sort / order are present."
                    ],
            ],
            'sort is not in [stars,forks,help-wanted-issues]'=> [
                'request_data'             => [
                    'sort' => 'invalid-value',
                ],
                'form_input'               => 'sort',
                'validation_error_message' =>
                    [
                        "The selected sort is invalid.",
                    ],
            ],

            // Test order input
            'order is empty' => [
                'request_data'             => [
                    'order' => '',
                ],
                'form_input'               => 'order',
                'validation_error_message' =>
                    [
                        "The created field is required when none of sort / order / language are present.",
                        "The sort field is required when none of created / order / language are present.",
                        "The order field is required when none of created / sort / language are present.",
                        "The language field is required when none of created / sort / order are present."
                    ],
            ],
            'order is not in [asc,desc]' => [
                'request_data'             => [
                    'order' => 'invalid-value',
                ],
                'form_input'               => 'order',
                'validation_error_message' =>
                    [
                        "The selected order is invalid.",
                    ],
            ],

            // Test language input
            'language is empty' => [
                'request_data'             => [
                    'language' => '',
                ],
                'form_input'               => 'language',
                'validation_error_message' =>
                    [
                        "The created field is required when none of sort / order / language are present.",
                        "The sort field is required when none of created / order / language are present.",
                        "The order field is required when none of created / sort / language are present.",
                        "The language field is required when none of created / sort / order are present."
                    ],
            ],
            'language is not [string]' => [
                'request_data'             => [
                    'language' => 1234,
                ],
                'form_input'               => 'language',
                'validation_error_message' =>
                    [
                        "The language must be a string.",
                    ],
            ],

            // Test per_page input
            'per_page is not [integer]' => [
                'request_data'             => [
                    // need to send created value in order to test integer rule for per_page input
                    'created'  => '2019-01-10',
                    'per_page' => 'random-string-value',
                ],
                'form_input'               => 'per_page',
                'validation_error_message' =>
                    [
                        "The per page must be an integer.",
                    ],
            ],
            'per_page has not valid [min:1]' => [
                'request_data'             => [
                    // need to send created value in order to test integer rule for per_page input
                    'created'  => '2019-01-10',
                    'per_page' => -1,
                ],
                'form_input'               => 'per_page',
                'validation_error_message' =>
                    [
                        "The per page must be at least 1.",
                    ],
            ],
            'per_page has not valid [max:30]' => [
                'request_data'             => [
                    // need to send created value in order to test integer rule for per_page input
                    'created'  => '2019-01-10',
                    'per_page' => 31,
                ],
                'form_input'               => 'per_page',
                'validation_error_message' =>
                    [
                        "The per page may not be greater than 30.",
                    ],
            ],

        ];
    }
}
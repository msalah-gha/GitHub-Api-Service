<?php

namespace App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories\Providers;

use App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories\SearchRepositoryContract;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GitHub implements SearchRepositoryContract
{
    /**
     * Configuration Array.
     *
     * @var | Array
     * */
    protected $config = [];

    public function __construct()
    {
        // Load GitHub Configurations (settings) as we use github as our provider
        $this->config = config('vcs.github');
    }

    /**
     * Search method, will call GitHub search repositories endpoint
     * after building url with the used query string (filters)
     *
     * @return mixed
     */
    public function search()
    {
        $result = Http::get(
            $this->getFullUrl()
        );

       if($result->status() == 200) {
           return $this->transform($result);
       }

       return false;
    }

    /**
     * Validate request data
     *
     * @return bool|array
     */
    public function validate()
    {
        $validation =  Validator::make(request()->all(), [

            'created'  => 'required_without_all:sort,order,language|date|date_format:Y-m-d',
            'sort'     => 'required_without_all:created,order,language|in:stars,forks,help-wanted-issues',
            'order'    => 'required_without_all:created,sort,language|in:asc,desc',
            'language' => 'required_without_all:created,sort,order|string',
            'per_page' => 'sometimes|required|integer|min:1|max:30',
        ]);

        // check if validation fails return array of errors otherwise, return true in success
        if($validation->fails()) {
            return $validation->errors()->all();
        }

        return true;
    }

    /**
     * Build Query String using the allowed filters (keys)
     *
     * @return string
     */
    public function buildQueryString(): string
    {
        $queryString = [];

        $allowedFilters = request()->only(['created', 'sort', 'order', 'language', 'per_page']);

        foreach ($allowedFilters as $key => $value) {

            if(request()->filled($key)) {

                $queryString[$key] = request()->{$key};

                if($key == 'created') {
                    $queryString[$key] = ':>' . request()->{$key};
                }
            }
        }

        // check if there is no value sent for per_page, use our default value
        if(! in_array('per_page', array_keys($allowedFilters))) {

            $queryString['per_page'] = $this->config['paginate'];
        }

        return '?q=' . http_build_query($queryString);
    }

    /**
     * Build the full url
     *
     * @return string
     */
    public function getFullUrl(): string
    {
        return $this->config['base_uri'] .'search/repositories'. $this->buildQueryString();
    }

    /**
     * Manipulate data based on github
     *
     * @param $data
     * @return mixed|void
     */
    public function transform($data)
    {
        // if we need to manipulate data, we will set our mapper here
        // before return the final result

        return $data['items'];
    }
}
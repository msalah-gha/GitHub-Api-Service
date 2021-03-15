<?php


namespace App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories;


interface SearchRepositoryContract
{
    /**
     * Search Method
     *
     * @return mixed
     */
    public function search();

    /**
     * Validate data
     *
     * @return mixed
     */
    public function validate();

    /**
     * Build query string
     *
     * @return mixed
     */
    public function buildQueryString();

    /**
     * transform result
     *
     * @param $data
     * @return mixed
     */
    public function transform($data);

}
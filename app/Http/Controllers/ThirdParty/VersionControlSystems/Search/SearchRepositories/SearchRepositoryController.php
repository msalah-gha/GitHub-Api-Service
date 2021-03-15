<?php

namespace App\Http\Controllers\ThirdParty\VersionControlSystems\Search\SearchRepositories;

use App\Traits\JsonResponderTrait;
use App\Http\Controllers\Controller;

class SearchRepositoryController extends Controller
{
    use JsonResponderTrait;

    /**
     * version control system provider
     * like (github, bitbucket, etc ..)
     *
     * @var SearchRepositoryContract
     */
    protected $vcsProvider;

    public function __construct(SearchRepositoryContract $vcsProvider)
    {
        $this->vcsProvider = $vcsProvider;
    }

    /**
     * Search and list repositories according to specific criteria
     */
    public function search()
    {
        // validate request first
        $validation = $this->vcsProvider->validate();

        // check if validation fails, while there are array of errors
        if(is_array($validation)) {
            return $this->validationErrorResponse($validation);
        }

        try {
            $result = $this->vcsProvider->search();

            return $this->successResponse($result);

        }catch (\Exception $e) {

            return $this->badRequestResponse();
        }
    }
}
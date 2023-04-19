<?php
    namespace App\Http\Controllers\Api;

    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Routing\Controller as RouteController;
    use Kairnial\LaravelApi\Traits\ApiResponse;

    /**
     * @OA\Info(
     *      title=L5_SWAGGER_DOC_TITLE,
     *      version=L5_SWAGGER_DOC_VERSION,
     *      description=L5_SWAGGER_DOC_DESCRIPTION,
     *      @OA\Contact(
     *          email=L5_SWAGGER_DOC_CONTACT
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_DOC_HOST,
     *      description=L5_SWAGGER_DOC_DESCRIPTION
     * )
     *
     */
    class BaseController extends RouteController
    {
        use ApiResponse, ValidatesRequests;
    }

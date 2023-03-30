<?php
    namespace Tests\Feature;

    use Symfony\Component\HttpFoundation\Response;
    use Illuminate\Support\Facades\Route;
    use Tests\BaseTestController;

    class ExampleTest extends BaseTestController
    {
        /**
         * A basic test example.
         */
        public function test_the_application_returns_a_successful_response(): void
        {
            Route::get('/fake-route', function () {});

            $this->get('/fake-route')->assertStatus(Response::HTTP_OK);
        }
    }

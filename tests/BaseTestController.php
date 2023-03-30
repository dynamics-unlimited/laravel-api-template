<?php
    namespace Tests;

    use Kairnial\LaravelApi\Models\Enums\ApiResponseStatus;
    use Kairnial\LaravelApi\Services\Auth\JwtGuard;
    use Symfony\Component\HttpFoundation\Response;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Testing\TestResponse;
    use Illuminate\Support\Str;

    abstract class BaseTestController extends TestCase
    {
        public string $table;
        public string $searchKey;
        public string $searchValue;
        public string $baseUrl;

        /** @inheritdoc */
        public function setUp(): void
        {
            parent::setUp();

            Auth::extend('jwt', function ($app, $name, array $config) {
                return new JwtGuard('', $config['audience'], $config['keys']);
            });
        }

        /**
         * Checks if a the keys of the first element of a model collection
         * @param array $content : the data
         * @param array $keys : the keys to check for
         * @param string $modelKey : the key representing the collection
         * @return bool true if the collection has elements; false otherwise
         */
        protected function assertContentHasModels(array $content, array $keys, string $modelKey = ''): bool
        {
            if (count($content) > 0)
            {
                $this->assertArrayHasKey(0, $content);

                foreach ($keys as $key)
                {
                    $this->assertArrayHasKey($key, $content[0]);

                    if ($key === $modelKey)
                    {
                        $this->assertIsArray($content[0][$key]);
                        $this->assertNotEmpty($content[0][$key]);
                    }
                }

                return true;
            }

            return false;
        }

        protected function assertUnauthorized(TestResponse $response): void
        {
            $response->assertUnauthorized();
            $this->assertErrorResponse($response);
        }

        private function assertErrorResponse($response): void
        {
            $this->assertJson($response->content());
            $content = json_decode($response->content());
            $this->assertSame('error', $content->status);
            $this->assertTrue(is_object($content));
            $this->assertTrue(property_exists($content, 'errors'));
            $this->assertIsObject($content->errors);
            $this->assertNotEmpty($content->errors);
        }

        protected function assertForbidden(TestResponse $response): void
        {
            $response->assertForbidden();
            $this->assertErrorResponse($response);
        }

        protected function assertExpectationFailedResponse(TestResponse $response): void
        {
            $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
            $this->assertErrorResponse($response);
        }

        protected function assertConflictResponse(TestResponse $response): void
        {
            $response->assertStatus(Response::HTTP_CONFLICT);
            $this->assertErrorResponse($response);
        }

        protected function testGetOneObjectWithImbrication(string $imbrications = ''): array
        {
            // not existing object
            $value = $this->keyMissingFromDatabase();
            $response = $this->get($this->baseUrl . $imbrications . $value);
            $this->assertNotFoundResponse($response);

            // existing object
            $value = $this->keyExistsInDatabase();
            $response = $this->get($this->baseUrl . $imbrications . $value);
            $content = $this->assertSuccessResponse($response);
            $this->assertArrayHasKey('data', $content);
            $this->assertArrayHasKey('key', $content['data']);
            $this->assertSame($value, $content['data']['key']);

            return $content;
        }

        private function keyMissingFromDatabase(): string
        {
            $value = Str::random(2);
            $this->assertDatabaseMissing($this->table, [$this->searchKey => $value]);

            return $value;
        }

        protected function assertNotFoundResponse(TestResponse $response): void
        {
            $response->assertNotFound();
            $this->assertErrorResponse($response);
        }

        private function keyExistsInDatabase(): string
        {
            $value = $this->searchValue;
            $this->assertDatabaseHas($this->table, [$this->searchKey => $value]);

            return $value;
        }

        /**
         * Assert that a response is successful, is well-structured and return its content
         *
         * @param TestResponse $response
         * @return array
         */
        protected function assertSuccessResponse(TestResponse $response): array
        {
            $response->assertSuccessful();
            $contentJSON = $response->content();
            $this->assertJson($contentJSON);
            $content = json_decode($contentJSON, true);
            $this->assertArrayHasKey('status', $content);
            $this->assertArrayHasKey('message', $content);
            $this->assertSame(ApiResponseStatus::SUCCESS->value, $content['status']);
            $this->assertArrayHasKey('errors', $content);
            $this->assertNull($content['errors']);
            $this->assertArrayHasKey('data', $content);

            return $content;
        }
    }

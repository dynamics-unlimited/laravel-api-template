<?php
    namespace App\Http\Resources;

    use Kairnial\LaravelApi\Http\Resources\BaseResource;

    /**
     * @property string $example_property
     */
    class Resource extends BaseResource
    {
        /** @inheritDoc */
        public function toArray($request): array
        {
            return [ 'key' => $this->example_property ];
        }
    }

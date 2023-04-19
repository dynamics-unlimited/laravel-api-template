<?php
    namespace App\Http\Resources;

    use Kairnial\Common\Http\Resources\BaseResource;

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

<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Concerns\HasRelationships;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Kairnial\LaravelApi\Traits\OrderedUuid;
    use Kairnial\LaravelApi\Models\BaseModel;
    use App\Http\Resources\Resource;

    /**
     * @OA\Schema(
     *     schema="Model",
     *     @OA\Property(property="key", type="string", example="key"),
     *     @OA\Property(property="label", type="string", example="label"),
     * )
     */
    class Model extends BaseModel
    {
        use HasRelationships;
        use OrderedUuid;

        const RESOURCE_CLASS = Resource::class;

        protected $table = 'model_table';
        protected $primaryKey = 'pk_model';
        protected $fillable = ['key', 'value'];

        public function models() : HasMany
        {
            return $this->hasMany(Model::class, 'fk_model');
        }
    }

<?php

namespace Tychovbh\LaravelCrud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Tychovbh\LaravelCrud\Actions\ModelNamespace;
use Tychovbh\LaravelCrud\Actions\ModelName;
use Tychovbh\LaravelCrud\Actions\ModelQuery;
use Illuminate\Routing\Controller as BaseController;

/**
 * @property string model
 * @property string name
 * @property string method
 */
class Controller extends BaseController
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // TODO run not in !app()->runningInConsole() but yes in testing mode
        $this->name = (new ModelName())->get(request());
        $this->model = (new ModelNamespace($this->name))->get();

        app()->bind(Model::class, $this->model);
    }

    /**
     * Response JSON.
     * @param Request $request
     * @param Builder $query
     * @return JsonResponse
     */
    private function responseJson(Request $request, Builder $query): JsonResponse
    {
        $paginate = $request->get('paginate');

        $response = [];
        if ($paginate) {
            $pagination = $query->paginate($paginate)->toArray();
            $response['data'] = $pagination['data'];
            Arr::forget($pagination, 'data');
            $response['meta'] = $pagination;
        } else {
            $response['data'] = $query->get();
        }

        return response()->json($response);
    }

    /**
     * Response index request.
     * @param Request $request
     * @param Builder $query
     * @return mixed
     */
    private function responseIndex(Request $request, Builder $query): mixed
    {
        // TODO clean up method it is large
        if ($request->get('resource') === 'off') {
            return $this->responseJson($request, $query);
        }

        $paginate = $request->get('paginate');

        $class = get_namespace() . 'Http\\Resources\\';

        if ($request->has('resource')) {
            $class .= $request->get('resource');
            $data = $paginate ? $query->paginate($paginate) : $query->get();
            return is_subclass_of($class, ResourceCollection::class) ? new $class($data) : $class::collection($data);
        }

        if (Arr::has($request->route()->defaults, 'resource')) {
            $class = $request->route()->defaults['resource'];
            $data = $paginate ? $query->paginate($paginate) : $query->get();
            return is_subclass_of($class, ResourceCollection::class) ? new $class($data) : $class::collection($data);
        }

        $class .= $this->name;

        $collection = $class . 'Collection';
        if (class_exists($collection)) {
            $data = $paginate ? $query->paginate($paginate) : $query->get();
            return new $collection($data);

        }

        $resource = $class . 'Resource';
        if (class_exists($resource)) {
            $data = $paginate ? $query->paginate($paginate) : $query->get();
            return $resource::collection($data);
        }

        return $this->responseJson($request, $query);
    }

    /**
     * Response show request.
     * @param Request $request
     * @param Model $model
     * @param int $status
     * @return mixed
     */
    private function responseShow(Request $request, Model $model, int $status = 200): mixed
    {
        $class = get_namespace() . 'Http\\Resources\\';
        $class .= $request->get('resource') ?? $this->name . 'Resource';

        if ($request->get('resource') === 'off' || !class_exists($class)) {
            return response()->json([
                'data' => $model
            ], $status);
        }

        return new $class($model);
    }

    /**
     * Index records.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $query = (new ModelQuery($this->model))->start($request);
        return $this->responseIndex($request, $query);
    }

    /**
     * Count records.
     * @param Request $request
     * @return JsonResponse
     */
    public function count(Request $request): JsonResponse
    {
        $query = (new ModelQuery($this->model))->start($request);
        $results = $query->paginate(1);

        return response()->json([
            'data' => [
                'count' => $results->total()
            ]
        ]);
    }

    /**
     * Show record.
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function show(Request $request, Model $model): mixed
    {
        return $this->responseShow($request, $model);
    }

    /**
     * Store record.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        $model = $this->model::create($request->toArray());
        return $this->responseShow($request, $model, 201);
    }

    /**
     * Update record.
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function update(Request $request, Model $model): mixed
    {
        $model->fill($request->toArray());
        $model->save();

        return $this->responseShow($request, $model);
    }

    /**
     * Restore soft deleted record.
     * @param Request $request
     * @param Model $model
     * @return mixed
     */
    public function restore(Request $request, Model $model): mixed
    {
        $model->restore();
        return $this->responseShow($request, $model);
    }

    /**
     * Soft/Delete record.
     * @param Model $model
     * @return JsonResponse
     */
    public function destroy(Model $model): JsonResponse
    {
        return response()->json([
            'deleted' => $model->delete()
        ]);
    }

    /**
     * Force Delete record.
     * @param Model $model
     * @return JsonResponse
     */
    public function forceDestroy(Model $model): JsonResponse
    {
        return response()->json([
            'deleted' => $model->forceDelete()
        ]);
    }

    /**
     * Bulk destroy records
     *
     * @return JsonResponse
     */
    public function bulkRestore(): JsonResponse
    {
        $ids = request()->input('id');

        return response()->json([
            'deleted' => $this->model::bulkRestore($ids)
        ]);
    }

    /**
     * Bulk destroy records
     *
     * @return JsonResponse
     */
    public function bulkDestroy(): JsonResponse
    {
        $ids = request()->input('id');

        return response()->json([
            'deleted' => $this->model::bulkDestroy($ids)
        ]);
    }

    /**
     * Bulk destroy records
     *
     * @return JsonResponse
     */
    public function bulkForceDestroy(): JsonResponse
    {
        $ids = request()->input('id');

        return response()->json([
            'deleted' => $this->model::bulkForceDestroy($ids)
        ]);
    }
}

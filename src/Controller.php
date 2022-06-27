<?php

namespace Tychovbh\LaravelCrud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Tests\App\Models\User;


/**
 * @property string model
 * @property string name
 * @property string method
 */
class Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // TODO run not in !app()->runningInConsole() but yes in testing mode
        $this->name = $this->name(request());
        $this->model = $this->model();
        $this->method = $this->method(request());

        app()->bind(Model::class, $this->model);

        Route::bind(Str::lower($this->name), fn(int $id) => $this->findModel($id));
        Route::bind('id', fn(int $id) => $this->findModel($id));
    }

    /**
     * Find model.
     * @param int $id
     * @return Model
     */
    private function findModel(int $id): Model
    {
        $query = $this->query(request());
        $query->where('id', $id);

        if (in_array($this->method, ['forceDestroy', 'restore'])) {
            $query->withTrashed();
        }

        return $query->firstOrFail();
    }

    /**
     * The request name.
     * @param Request $request
     * @return string
     */
    private function name(Request $request): string
    {
        $name = $request->route()->getName();
        return Str::ucfirst(
            Str::camel(
                Str::singular(explode('.', $name)[0])
            )
        );
    }

    /**
     * The request method.
     * @param Request $request
     * @return string
     */
    private function method(Request $request): string
    {
        $name = $request->route()->getName();
        return explode('.', $name)[1];
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
     * Retrieve model.
     * @return string
     */
    private function model(): string
    {
        return get_namespace() . 'Models\\' . $this->name;
    }

    /**
     * Start request query.
     * @param Request $request
     */
    private function query(Request $request): Builder
    {
        $model = $this->model;
        $query = $model::query();

        if ($request->method() !== 'GET') {
            return $query;
        }

        $params = $request->toArray();

        if ($params && method_exists($model, 'params')) {
            $query = $model::params($params);
        }

        $select = $request->get('select') ?? ['*'];

        if (is_string($select)) {
            $select = explode(',', $select);
        }

        return $query->select($select);
    }

    /**
     * Index records.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $query = $this->query($request);
        return $this->responseIndex($request, $query);
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
     * @param Model $model
     * @return mixed
     */
    public function restore(Model $model): JsonResponse
    {
        return response()->json([
            'restored' => $model->restore()
        ]);
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
}

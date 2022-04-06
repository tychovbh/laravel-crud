<?php

namespace Tychovbh\LaravelCrud;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Controller
{
    /**
     * The request name.
     * @param Request $request
     * @return string
     */
    private function name(Request $request): string
    {
        $name = $request->route()->getName();
        return Str::ucfirst(Str::singular(explode('.', $name)[0]));
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

        $class .= $this->name($request);

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
        $class .= $request->get('resource') ?? $this->name($request) . 'Resource';

        if ($request->get('resource') === 'off' || !class_exists($class)) {
            return response()->json([
                'data' => $model
            ], $status);
        }

        return new $class($model);
    }

    /**
     * Retrieve model.
     * @param Request $request
     * @return string
     */
    private function model(Request $request): string
    {
        return get_namespace() . 'Models\\' . $this->name($request);
    }

    /**
     * Start request query.
     * @param Request $request
     */
    private function query(Request $request): Builder
    {
        $model = $this->model($request);
        $query = $model::query();

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
     * @param int $id
     * @return mixed
     */
    public function show(Request $request, int $id): mixed
    {
        $query = $this->query($request);
        $query->where('id', $id);


        return $this->responseShow($request, $query->firstOrFail());
    }

    /**
     * Store record.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        $model = $this->model($request);
        $model = $model::create($request->toArray());

        return $this->responseShow($request, $model, 201);
    }

    /**
     * Update record.
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id): mixed
    {
        $model = $this->model($request);
        $model = $model::findOrFail($id);
        $model->fill($request->toArray());
        $model->save();

        return $this->responseShow($request, $model);
    }

    /**
     * Delete record.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $model = $this->model($request);

        return response()->json([
            'deleted' => $model::destroy($id)
        ]);
    }
}

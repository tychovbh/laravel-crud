<?php
namespace Tychovbh\LaravelCrud;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Controller
{
    /**
     * Retrieve model.
     * @param Request $request
     * @return string
     */
    private function model(Request $request)
    {
        $name = $request->route()->getName();
        $model = Str::ucfirst(Str::singular(explode('.', $name)[0]));
        return get_namespace() . 'Models\\' . $model;
    }

    /**
     * Index records.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $model = $this->model($request);
        $params = $request->toArray();
        $paginate = $request->get('paginate');

        $query = $model::query();
        if ($params && method_exists($model, 'params')) {
            $query = $model::params($params);
        }

        if ($paginate) {
            $pagination = $query->paginate($paginate)->toArray();
            $data = $pagination['data'];
            Arr::forget($pagination, 'data');

            return response()->json([
                'data' => $data,
                'meta' => $pagination
            ]);
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    /**
     * Show record.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $model = $this->model($request);

        return response()->json([
            'data' => $model::findOrFail($id)
        ]);
    }

    /**
     * Store record.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $model = $this->model($request);
        $model = $model::create($request->toArray());

        return response()->json([
            'data' => $model
        ]);
    }

    /**
     * Update record.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $model = $this->model($request);
        $model = $model::findOrFail($id);
        $model->fill($request->toArray());
        $model->save();

        return response()->json([
            'data' => $model
        ]);
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

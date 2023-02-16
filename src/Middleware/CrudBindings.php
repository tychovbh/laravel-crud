<?php

namespace Tychovbh\LaravelCrud\Middleware;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Actions\ModelName;
use Tychovbh\LaravelCrud\Actions\ModelNamespace;
use Tychovbh\LaravelCrud\Actions\ModelQuery;
use Tychovbh\LaravelCrud\Actions\RequestMethod;

class CrudBindings
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {

        $action = $request->route()->getAction();
        if (Str::contains($action['controller'], 'Tychovbh\LaravelCrud\Controller')) {
            $model = (new ModelName())->get($request);
            Route::bind(Str::lower($model), fn(int $id) => $this->findModel($model, $id));
            Route::bind('id', fn(int $id) => $this->findModel($model, $id));
        }

        return $next($request);
    }

    /**
     * Find model.
     * @param string $model
     * @param int $id
     * @return Model
     */
    private function findModel(string $model, int $id): Model
    {
        $model = (new ModelNamespace($model))->get();
        $method = (new RequestMethod())->get();
        $query = (new ModelQuery($model))->start(request());
        $query->where('id', $id);

        if (in_array($method, ['forceDestroy', 'restore'])) {
            $query->withTrashed();
        }

        return $query->firstOrFail();
    }
}

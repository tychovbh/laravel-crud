<?php

namespace Tychovbh\LaravelCrud\Middleware;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Actions\ModelName;
use Tychovbh\LaravelCrud\Actions\ModelNamespace;
use Tychovbh\LaravelCrud\Actions\ModelQuery;
use Tychovbh\LaravelCrud\Actions\RequestMethod;
use ReflectionClass;
use Tychovbh\LaravelCrud\Controller;

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
        $controller = explode('@', $action['controller'])[0];

        if ($controller === Controller::class) {
            $this->bindModel($request);
            return $next($request);
        }

        $class = new ReflectionClass($controller);
        $parent = $class->getParentClass();

        if ($parent->name === Controller::class) {
            $this->bindModel($request);
        }

        return $next($request);
    }

    /**
     * Bind model
     * @param Request $request
     */
    private function bindModel(Request $request)
    {
        $model = (new ModelName())->get($request);
        Route::bind(Str::lower($model), fn(int $id) => $this->findModel($model, $id));
        Route::bind('id', fn(int $id) => $this->findModel($model, $id));
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

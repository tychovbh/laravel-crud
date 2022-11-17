<?php


namespace Tychovbh\LaravelCrud\Actions;


class ModelNamespace
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function get()
    {
        return get_namespace() . 'Models\\' . $this->name;
    }
}

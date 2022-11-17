<?php


namespace Tychovbh\LaravelCrud\Actions;


class RequestMethod
{
    public function get()
    {
        $name = request()->route()->getName();
        return explode('.', $name)[1];
    }
}

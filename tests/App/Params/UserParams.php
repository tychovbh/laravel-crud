<?php
namespace Tychovbh\LaravelCrud\Tests\App\Params;

use Tychovbh\LaravelCrud\Params\Params;

class UserParams extends Params
{
    /**
     * @param string $value
     */
    public function search(string $value)
    {
        $this->query->where('name', 'like', '%' . $value . '%');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: 82683
 * Date: 2018/9/25 0025
 * Time: 下午 8:54
 */
namespace Jiangyong\AddressFence\Traits;

trait AddressScope{

    protected $lngMin;
    /**
     * @var string
     */
    protected $lngMax;
    /**
     * @var string
     */
    protected $latMin;
    /**
     * @var string
     */
    protected $latMax;

    /**
     * addressFence constructor.
     * @param string $lngMin
     * @param string $lngMax
     * @param string $latMin
     * @param string $latMax
     * 初始化默认值设置，可以自己手动设置
     */

    public function __construct($lngMin = 'lngMin',$lngMax = 'lngMax',$latMin = 'latMin',$latMax = 'latMax')
    {
        $this->lngMin = empty(config('services.address.lngMin')) ? $lngMin : config('services.address.lngMin');
        $this->lngMax = empty(config('services.address.lngMax')) ? $lngMax : config('services.address.lngMax');
        $this->latMin = empty(config('services.address.latMin')) ? $latMin : config('services.address.latMin');
        $this->latMax = empty(config('services.address.latMax')) ? $latMax : config('services.address.latMax');
    }

    /**
     * @param $query
     * @param array $point
     * @return mixed
     * 通过调用直接在数据库中匹配出符合的长方形区域
     */
    public function scopeReturnFeasibleFenceArea($query,$point = [])
    {
        if(empty($point)) {
            throw new InvalidArgumentException('The array can be null');
        }
        //此处用于scope作用域，在模型里引用，然后控制器直接调用
        return $query->where($this->lngMin,'>=',$point[0][0])->where($this->lngMax,'<=',$point[0][1])->where($this->latMin,'>=',$point[1][0])->where($this->latMax,'<=',$point[1][1]);

    }

}
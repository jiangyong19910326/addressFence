<?php
/**
 * Created by PhpStorm.
 * User: 82683
 * Date: 2018/9/25 0025
 * Time: 下午 8:54
 */
namespace Jiangyong\AddressFence\Traits;

use Jiangyong\AddressFence\Exceptions\InvalidArgumentException;

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
        $this->lngMin = empty(config('services.addressFence.lngMin')) ? $lngMin : config('services.addressFence.lngMin');
        $this->lngMax = empty(config('services.addressFence.lngMax')) ? $lngMax : config('services.addressFence.lngMax');
        $this->latMin = empty(config('services.addressFence.latMin')) ? $latMin : config('services.addressFence.latMin');
        $this->latMax = empty(config('services.addressFence.latMax')) ? $latMax : config('services.addressFence.latMax');
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
        if(!is_array($point)){
            throw  new InvalidArgumentException('This must be array');
        }
        //此处用于scope作用域，在模型里引用，然后控制器直接调用
        return $query->where($this->lngMin,'<=',$point['lng'])->where($this->lngMax,'>=',$point['lng'])->where($this->latMin,'<=',$point['lat'])->where($this->latMax,'>=',$point['lat']);

    }

}
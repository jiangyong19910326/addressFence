<h1 align="center"> address-fence </h1>

<p align="center"> a address-fence APi.</p>


### 安装

```shell
$ composer require jiangyong/address-fence 
```
### 配置
    在使用本扩展之前，请先熟悉配置相关
    1.配置默认的模型字段'lngMin','lngMax','latMin','latMax'
    2.当然你也可以自己配置字段
      首先在config/services.php中
      添加以下
      'addressFence' => [
              'lngMin' => env('ADDRESS_LNGMIN'),
              'lngMax' => env('ADDRESS_LNGMAX'),
              'latMin' => env('ADDRESS_LATMIN'),
              'latMax' => env('ADDRESS_LATMAX'),
              ]
      然后在.env文件中配置你自己的数据表字段名
      ADDRESS_LNGMIN=
      ADDRESS_LNGMAX=
      ADDRESS_LATMIN=
      ADDRESS_LATMAX=
## 使用
    在需要的控制器中添加
    use Jiangyong\AddressFence\addressFence;
    然后调用
    在方法中注入实力类，写法如下:
    public function Demo(addressFence $addressFence)
    {
        /*
        *参数 $areaFence就是你输入的围栏地址
        *方法返回一个长方形的两个最大最小的xy点
        */
        $addressFence->returnPointOfAddressFence($areaFence)
        
    }
    
    在模型中通过调用获取数据库中可行的围栏区域
    在你需要的模型中
    use Jiangyong\AddressFence\Traits;
    在模型类中调用即可
    class model_name extends model
    {
        use Traits\AddressScope;
    }
    然后在你的控制器中直接返回长方形区域
    public functiong demoText(Model $model)
    {
        /**
        *   $polygon就是你地址围栏获得的长方形区域最大最小的xy点
        */
        Model::returnFeasibleFenceArea($polygon)->get()
    }
    最后通过调用返回最后可行的地址围栏数据
    public funtion demoTest(addressFence $addressFence)
    {   
        /**
        * $area 为返回的长方形区域
        * $point 输入的一个具体的点
        /
        $addressFence->returnFinalArea($area,$point);
    }

TODO

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/jiangyong/address-fence/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/jiangyong/address-fence/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
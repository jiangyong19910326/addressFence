<?php
/**
 * Created by PhpStorm.
 * User: 82683
 * Date: 2018/9/25 0025
 * Time: 下午 4:11
 */

namespace Jiangyong\AddressFence;
use Jiangyong\AddressFence\Exceptions\InvalidArgumentException;
use Jiangyong\AddressFence\Traits;
class addressFence
{
    use Traits\AddressScope;
    public $polygon;
    private $checkResult;

    /**
     * @param $array
     */
    public function setPolygon($array) {
        $this->polygon = $array;
        if (!is_null($this->polygon)) {
            array_push($this->polygon, $array[0]);
            $this->checkResult = false;
        }
    }
    //  @param: array( array( lat, lng ), array( y, x ));
    public function checkPoints($points) {
        $inPoly = array();
        $polyVertices = count($this->polygon);
        if ($polyVertices < 5) {
            return $this->checkRectangle($points);
        }
        foreach ($points as $key => $point) {
            $inPoly[$key] = false;
            $intersections = 0;
            for ($i = 1; $i < $polyVertices; $i += 1) {
                $v1 = $this->polygon[$i - 1];
                $v2 = $this->polygon[$i];
                //  Check if point is within min/max of vertices and determine amount of intersections
                if ($point[1] > min($v1[1], $v2[1])
                    && $point[1] <= max($v1[1], $v2[1])
                    && $point[0] <= max($v1[0], $v2[0])
                    && $v1[1] != $v2[1]
                ) {
                    $lngInters = ($point[1] - $v1[1]) * ($v2[0] - $v1[0]) / ($v2[1] - $v1[1]) + $v1[0];
                    if ($v1[0] == $v2[0] || $point[0] <= $lngInters) {
                        $intersections += 1;
                    }
                }
            }
            //  Intersections must be an odd number in order for the point to reside inside the polygon
            if ($intersections % 2 != 0) {
                $inPoly[$key] = true;
            }
        }
        $this->checkResult = $inPoly;
        return $this->checkResult;
    }
    public function getCheckResult() {
        return $this->checkResult;
    }
    //  Checking for a 4 sided polygon and executing simpler logic to determine if the point exists within the polygon
    //  @param: array( array( lat, lng ), array( y, x ));
    public function checkRectangle($points) {
        $inPoly = array();
        $polyVertices = count($this->polygon);
        foreach ($points as $key => $point) {
            $HighLow = array('latHigh' => $point[0], 'latLow' => $point[0], 'longHigh' => $point[1], 'longLow' => $point[1]);
            $inPoly[$key] = false;
            for ($i = 0; $i < $polyVertices; $i += 1) {
                $v = $this->polygon[$i];
                $HighLow['latHigh'] = ($HighLow['latHigh'] < $v[0]) ? $v[0] : $HighLow['latHigh'];
                $HighLow['latLow'] = ($HighLow['latLow'] > $v[0]) ? $v[0] : $HighLow['latLow'];
                $HighLow['longHigh'] = ($HighLow['longHigh'] < $v[1]) ? $v[1] : $HighLow['longHigh'];
                $HighLow['longLow'] = ($HighLow['longLow'] > $v[1]) ? $v[1] : $HighLow['longLow'];
            }
            if ($point[0] > $HighLow['latLow'] && $point[0] < $HighLow['latHigh'] && $point[1] > $HighLow['longLow'] && $point[1] < $HighLow['longHigh']) {
                $inPoly[$key] = !$inPoly[$key];
            }
        }
        $this->checkResult = $inPoly;
        return $inPoly;
    }

    /**
     * @param array $addressFence
     * @return array
     * 提供地址围栏:返回最大最小值的x,y
     */
    public function returnPointOfAddressFence($addressFence = [],$xArray = [],$yArray = [])
    {

        $returnPoint = [];
        //参数异常返回
        if( empty($addressFence) || !is_array($addressFence)) {
            throw new InvalidArgumentException('This value can be null!');
        }
        foreach ($addressFence as $key => $value) {
            $xArray[] = $value[0];
            $yArray[] = $value[1];
        }
        if(empty($xArray) || empty($yArray)) {
            throw new InvalidArgumentException('The param xArray and yArray can not be null');
        }

        $returnPoint = [[min($xArray),max($xArray)],[min($yArray),max($yArray)]];
        return $returnPoint; //返回最大和最小的点
    }


    /**
     * @param array $areas
     * @param array $point
     * @return array
     * @throws InvalidArgumentException
     * 返回最后的地址围栏区域
     */
    public function returnFinalArea($areas = [],$point = [])
    {
        if(empty($areas) || ! is_object($areas)) {
            throw new InvalidArgumentException('The areas must be object and can not be null');
        }
        if(empty($point) || !is_array($point)) {
            throw new InvalidArgumentException('The point value can not be null and must be array');
        }
        $availableAreas = []; //点所在的区域返回
        //循环判断是否在地址围栏的区域内，如果再，就添加到所在区域的数组里面
        foreach ($areas as $key => $area){
            $this->setPolygon(json_decode($area->points));
            if($this->checkPoints([$point])[0]) {
                $availableAreas[$area->id] = true;
            }
        }
        array_filter($availableAreas);
        return $availableAreas;
    }
}
<?php
// /////////////////////////////////////////////////////////////////////////////
// PREDEFINED CONSTANTS, INTERFACES AND CLASSES
// /////////////////////////////////////////////////////////////////////////////

use function PHPSTORM_META\type;

define("PI", 3.14);

/**
 * Interface defining methods for all shapes.
 */
interface ShapeInterface
{
    public function getPerimeter();
    public function getArea();
}

/**
 * Interface defining methods for polygon shapes.
 */
interface PolygonInterface
{
    public function getAngles();
}

/**
 * Base class for geometry objects.
 */
class GeometryShape
{
    public function getName()
    {
        return get_class($this);
    }
}

// /////////////////////////////////////////////////////////////////////////////
// WORKING AREA
// THIS IS AN AREAD WHERE YOU SHOULD WRITE YOUR CODE AND MAKE CHANGES.
// /////////////////////////////////////////////////////////////////////////////


class Circle extends GeometryShape implements ShapeInterface
{

    private $radious;

    function __construct($params)
    {
        list($r) = $params;
        $this->radious = (int)$r;
    }

    public function getPerimeter()
    {
        return (2 * PI * $this->radious);
    }

    public function getArea()
    {
        return  PI * $this->radious ** 2;
    }
}


class Square  extends GeometryShape implements ShapeInterface, PolygonInterface
{

    private $edge;

    function __construct($params)
    {
        list($e) = $params;
        $this->edge = (int)$e;
    }


    public function getPerimeter()
    {
        return 4 * $this->edge;
    }

    public function getArea()
    {
        return  intval($this->edge) ^ 2;
    }

    public function getAngles()
    {
        return 4;
    }
}

class Rectangle extends GeometryShape implements ShapeInterface, PolygonInterface
{

    private $height;
    private $width;

    function __construct($params)
    {
        list($h, $w) = $params;

        $this->height = $h;
        $this->width = $w;
    }

    public function getPerimeter()
    {
        return 2 * ($this->height + $this->width);
    }

    public function getArea()
    {
        return $this->height * $this->width;
    }

    public function getAngles()
    {
        return 4;
    }
}

/**
 * Factory class for creating different GeometryShapes.
 */
class ShapeFactory
{

    /**
     * Creates a specific GeometryShape object from the given attributes.
     *
     * Usage examples:
     *     ShapeFactory::createShape("Circle", 4)
     *     ShapeFactory::createShape("Rectangle", [3, 5])
     *
     * @param string shape Shape to create.
     * @param array params Array of needed parameters.
     */
    public static function createShape($shape, $params)
    {
        //check the shape existance
        if (!class_exists($shape)) {
            throw new UnsuportedShapeException;
        }

        // check the params count
        $reflec = new ReflectionClass($shape);
        if( $reflec->getConstructor()->getNumberOfParameters() != count($params) ){
            throw new WrongParamCountException;
        }

        return new $shape($params);
    }
}

class UnsuportedShapeException extends Exception
{
}

class WrongParamCountException extends Exception
{
}


// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);


// /////////////////////////////////////////////////////////////////////////////
// TEST CODE
// THE CODE BELOW IS READ ONLY CODE AND YOU SHOULD INSPECT IT TO SEE WHAT IT
// DOES IN ORDER TO COMPLETE THE TASK, BUT DO NOT MODIFY IT IN ANY WAY
// AS THAT WILL RESULT IN A TEST FAILURE
// /////////////////////////////////////////////////////////////////////////////

/**
 * Helper function which is used to create shape based on input parameters
 * and return information about that specific shape.
 */
function getInfo($shape, $params)
{
    try {
        $shapeObject = ShapeFactory::createShape($shape, $params);

        $info = $shapeObject->getName() . PHP_EOL;
        if ($shapeObject instanceof ShapeInterface) {
            $info .= "Perimeter is: " . number_format($shapeObject->getPerimeter(), 2) . PHP_EOL;
            $info .= "Area is: " . number_format($shapeObject->getArea(), 2) . PHP_EOL;
        }
        if ($shapeObject instanceof PolygonInterface) {
            $info .= "Number of angles: " . $shapeObject->getAngles() . PHP_EOL;
        }
        $info .= PHP_EOL;

        return $info;
    } catch (UnsuportedShapeException $e) {
        return "Unsupported Shape" . PHP_EOL;
    } catch (WrongParamCountException $e) {
        return "Wrong Number Of Shape Params" . PHP_EOL;
    }
}

// while($f = fgets(STDIN)){
$file = fopen('shapesData.txt', "r");
while ($f = fgets($file)) {
    $params = explode(" ", $f);
    $shape = $params[0];
    $shapeParams = explode(",", $params[1]);

    echo getInfo($shape, $shapeParams);
}

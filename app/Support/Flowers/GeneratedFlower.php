<?php

declare(strict_types=1);

namespace App\Support\Flowers;

class GeneratedFlower
{
    /** @var string */
    protected $svgFileContents;

    /** @var int */
    protected $colorParam;

    /** @var array */
    protected $shapeParams;

    /**
     * FlowerData constructor.
     * @param string $svgFileContents
     * @param int $colorParam
     * @param array $shapeParams
     */
    public function __construct(string $svgFileContents, int $colorParam, array $shapeParams)
    {
        $this->svgFileContents = $svgFileContents;
        $this->colorParam = $colorParam;
        $this->shapeParams = $shapeParams;
    }

    /**
     * @return string
     */
    public function getSvgFileContents() : string
    {
        return $this->svgFileContents;
    }

    /**
     * @return int
     */
    public function getColorParam() : int
    {
        return $this->colorParam;
    }

    /**
     * @return array
     */
    public function getShapeParams() : array
    {
        return $this->shapeParams;
    }
}

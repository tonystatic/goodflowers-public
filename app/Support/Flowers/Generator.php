<?php

declare(strict_types=1);

namespace App\Support\Flowers;

use SVG\Nodes\Shapes\SVGPolygon;
use SVG\Nodes\Structures\SVGDefs;
use SVG\Nodes\SVGGenericNodeType;
use SVG\SVG;

class Generator
{
    const SHAPE_PARAM_MAX = 20;

    const IMAGE_SIZE = 100;

    /**
     * @return \App\Support\Flowers\GeneratedFlower
     */
    public function generateFlower() : GeneratedFlower
    {
        $colorParam = $this->generateColorParam();
        $shapeParams = $this->generateShapeParams();

        $svgFileContents = $this->generateSvgFromParams(
            $colorParam,
            $shapeParams[0],
            $shapeParams[1],
            $shapeParams[2],
            $shapeParams[3]
        );

        $flower = new GeneratedFlower($svgFileContents, $colorParam, $shapeParams);

        unset($svgFileContents);

        return $flower;
    }

    /**
     * @param int $colorParam
     * @param array $shapeParams
     * @return \App\Support\Flowers\GeneratedFlower
     */
    public function regenerateFlower(int $colorParam, array $shapeParams) : GeneratedFlower
    {
        $svgFileContents = $this->generateSvgFromParams(
            $colorParam,
            $shapeParams[0],
            $shapeParams[1],
            $shapeParams[2],
            $shapeParams[3]
        );

        $flower = new GeneratedFlower($svgFileContents, $colorParam, $shapeParams);

        unset($svgFileContents);

        return $flower;
    }

    /**
     * @return array
     */
    protected function generateShapeParams() : array
    {
        $shapeParams = [];
        for ($i = 1; $i <= 4; ++$i) {
            $shapeParams[] = \mt_rand(0, self::SHAPE_PARAM_MAX);
        }

        return $shapeParams;
    }

    /**
     * @return int
     */
    protected function generateColorParam() : int
    {
        return (int) (\mt_rand(155, 360 + 65) % 360 / 5) * 5;
    }

    /**
     * @param int $colorParam
     * @param int $shapeParam1
     * @param int $shapeParam2
     * @param int $shapeParam3
     * @param int $shapeParam4
     * @return string
     */
    protected function generateSvgFromParams(
        int $colorParam,
        int $shapeParam1,
        int $shapeParam2,
        int $shapeParam3,
        int $shapeParam4
    ) : string {
        $coordinates = $this->getShapeCoordinates(
            self::IMAGE_SIZE / 2,
            self::IMAGE_SIZE / 2,
            self::IMAGE_SIZE * 0.4,
            $shapeParam1,
            $shapeParam2,
            $shapeParam3,
            $shapeParam4
        );

        [$color1, $color2] = $this->randomizeGradientColors($colorParam);

        $gradientStop1 = (new SVGGenericNodeType('stop'))
            ->setAttribute('offset', '0')
            ->setAttribute('stop-color', $this->rgbToHex($color1['r'], $color1['g'], $color1['b']));
        $gradientStop2 = (new SVGGenericNodeType('stop'))
            ->setAttribute('offset', '1')
            ->setAttribute('stop-color', $this->rgbToHex($color2['r'], $color2['g'], $color2['b']));
        $gradient = (new SVGGenericNodeType('radialGradient'))
            ->setAttribute('id', 'a')
            ->addChild($gradientStop1)
            ->addChild($gradientStop2);
        $defs = (new SVGDefs())
            ->addChild($gradient);
        $shape = (new SVGPolygon($coordinates))
            ->setStyle('fill', 'url(#a)');

        $image = (new SVG(self::IMAGE_SIZE, self::IMAGE_SIZE));

        $document = $image->getDocument();
        $document->addChild($defs)
            ->addChild($shape);

        return $image->toXMLString(false);
    }

    /**
     * @param float $centerX
     * @param float $centerY
     * @param float $radius
     * @param int $seed1
     * @param int $seed2
     * @param int $seed3
     * @param int $seed4
     * @return array
     */
    protected function getShapeCoordinates(
        float $centerX,
        float $centerY,
        float $radius,
        int $seed1,
        int $seed2,
        int $seed3,
        int $seed4
    ) : array {

        // An algorythm here

        return [];
    }

    /**
     * @param int $hue
     * @return array
     */
    protected function randomizeGradientColors(int $hue) : array
    {
        $saturation = 90;
        $value = 90;

        return [
            $this->hsvToRgb($hue, $saturation, $value),
            $this->hsvToRgb($hue + 20, $saturation, $value - 10)
        ];
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $v
     * @return array
     */
    protected function hsvToRgb(int $h, int $s, int $v) : array
    {
        if ($h < 0) {
            $h = 0;
        } elseif ($h >= 360) {
            $h = $h % 360;
        }
        if ($s < 0) {
            $s = 0;
        } elseif ($s > 100) {
            $s = 100;
        }
        if ($v < 0) {
            $v = 0;
        } elseif ($v > 100) {
            $v = 100;
        }

        $s /= 100;
        $v /= 100;
        $c = $v * $s;
        $hP = $h / 60;

        $x = $c * (1 - \abs(\fmod($hP, 2) - 1));

        [$r, $g, $b] = [0, 0, 0];

        if (0 <= $hP && $hP < 1) {
            [$r, $g] = [$c, $x];
        } elseif (1 <= $hP && $hP < 2) {
            [$r, $g] = [$x, $c];
        } elseif (2 <= $hP && $hP < 3) {
            [$g, $b] = [$c, $x];
        } elseif (3 <= $hP && $hP < 4) {
            [$g, $b] = [$x, $c];
        } elseif (4 <= $hP && $hP < 5) {
            [$r, $b] = [$x, $c];
        } else {
            [$r, $b] = [$c, $x];
        }

        $m = $v - $c;
        $r = ($r + $m) * 255;
        $g = ($g + $m) * 255;
        $b = ($b + $m) * 255;

        return ['r' => (int) \floor($r), 'g' => (int) \floor($g), 'b' => (int) \floor($b)];
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @param bool $addHash
     * @return string
     */
    public function rgbToHex(int $r, int $g, int $b, bool $addHash = true) : string
    {
        return ($addHash ? '#' : '') . \dechex($r) . \dechex($g) . \dechex($b);
    }
}

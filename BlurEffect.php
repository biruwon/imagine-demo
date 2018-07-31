<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Imagine\Filter\Advanced\RelativeResize;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

$imagine = new Imagine\Gd\Imagine();

$image = $imagine->open('test.jpg');

$size = [1600, 900];
scale($image, $size);
$blurImage = createFrame($image, $size);
$blurImage->paste($image, getPointToPaste($image, $size));
$blurImage->save('blurImage.jpg');


function scale(ImageInterface $image, array $size)
{
    if ($image->getSize()->getWidth() > $size[0] || $image->getSize()->getHeight() > $size[1]) {
        if ($image->getSize()->getWidth() > $size[0]) {
            rescale($image, 'widen', $size[0]);
        }
        if ($image->getSize()->getHeight() > $size[1]) {
            rescale($image, 'heighten', $size[1]);
        }
    }
}

function rescale(ImageInterface $image, $type, $size)
{
    $scaleFilter = new RelativeResize($type, $size);
    $scaleFilter->apply($image);
}

function createFrame(ImageInterface $image, array $size)
{
    $frameImage = $image->copy();
    $frameImage->resize(new Box($size[0], $size[1]));

    for ($x = 0; $x < 100; ++$x) {
        $frameImage->effects()
            ->blur(1);
    }

    return $frameImage;
}

function getPointToPaste(ImageInterface $image, array $frameSize)
{
    $x = ($image->getSize()->getWidth() >= $frameSize[0]) ? 0 : ($frameSize[0] - $image->getSize()->getWidth()) / 2;
    $y = ($image->getSize()->getHeight() >= $frameSize[1]) ? 0 : ($frameSize[1] - $image->getSize()->getHeight()) / 2;

    return new Point($x, $y);
}

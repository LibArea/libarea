<?php
// modife areadev 2021

class ImageUpload
{

    private $image; // our opened image, known as a resource in an attribute
    public $width;
    public $height;
    public $error = '';
    public $fileSize; // in KB
    public $imageResized;
    public $imageNewName;
    private $extension;
    private $allowed_extensions = ['jpeg', 'jpg', 'png', 'gif'];
    private $not_allowed_extensions = ['py', 'exe', 'sh', 'bat', 'msi', 'php', 'bin', 'js'];



    /* Magic functions */

    public function __construct($fileName)
    {
        /* with creating an object of an image,
         * we can access to the attributes like: 'width', 'height', 'size', 'extension'
         * and the image itself which is stored in 'image' attribute
         **/

        // get extension
        $this->extension = @strtolower(end(explode(".", $_FILES[$fileName]['name'])));

        if ($this->extension == '')
            $this->error = 'image not selected';

        // get image from specified path & store it into `image` property
        else if (in_array($this->extension, $this->allowed_extensions)) {
            switch ( $this->extension ) {
                case 'jpeg':
                case 'jpg':
                    $this->image = imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
                    break;
                case 'png':
                    $this->image = imagecreatefrompng($_FILES[$fileName]['tmp_name']);
                    break;
                case 'gif':
                    $this->image = imagecreatefromgif($_FILES[$fileName]['tmp_name']);
                    break;
                default:
                    $this->image = null;
                    $this->error = 'upacceptable file type';
                    break;
            }
        } else {
            $this->error = 'upacceptable mime type';
        }

        // get with , height , fileSize , store them into class attributes, if there is no error
        if ( empty($this->error) ) {

            // get image size from path && set error if size < 10kb
            $this->fileSize = @round(filesize($_FILES[$fileName]['tmp_name']) / 1024); // kb
            if ( $this->fileSize < 10 )
                $this->error = "very small image";

            // get width and height from path && set error if width < 128 || height < 128
            list($this->width, $this->height) = @getimagesize($_FILES[$fileName]['tmp_name']);
            if ( $this->width < 128 || $this->height < 128 )
                $this->error = "very low resolution";

        }
    }

    public function __set($key, $value)
    {
        /* for set `allowed_extensions` attribute
         * returns null
         **/

        if ($key == 'allowed_extensions' || $key == 'not_allowed_extensions') {
            if (is_array($value)) {
                $this->$key = $value;
            } else {
                $this->error = 'unacceptable `'  . $key . '` value';
            }
        }
    }

    /* Available functions */

    public function check($maxSize=2024)
    {
        /* check upload errors & empty file & fileSize (in KB)
         * returns boolean
         **/

        if ( empty($this->error) ) {
            if ( $this->fileSize < $maxSize && $this->fileSize != 0 ) {
                $this->error = null;
                return true;
            } else if ( $this->fileSize == 0 ) {
                $this->error = "no file";
                return false;
            } else {
                $this->error = "big image";
                return false;
            }
        }
        else
            return false;
    }

    public function resize($newWidth, $newHeight, $option="auto")
    {
        /* resize image and save it into `imageResized` attribute
         * returns null
         **/

        // Get optimal width and height - based on $option
        $optimalSizes  = $this->getOptimalDimensions($newWidth, $newHeight, strtolower($option));
        $optimalWidth  = $optimalSizes['optimalWidth'];
        $optimalHeight = $optimalSizes['optimalHeight'];

        // Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);


        // if option is 'crop', then crop too
        if ($option == 'crop')
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
    }

    public function saveTo($path, $name, $quality="100")
    {
        // returns null
        // save image to specified path with random name

        if (!is_dir($path) || !is_writable($path)) {
            $this->error = 'invalid path';
        }

        $this->imageNewName = "img_" .$name. "." .$this->extension;

        switch ( $this->extension ) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($this->imageResized, $path . $this->imageNewName, $quality);
                break;
            case 'gif':
                imagegif($this->imageResized, $path . $this->imageNewName);
                break;
            case 'png':
                $scaleQuality = round(($quality/100) * 9);
                $invertScaleQuality = 9 - $scaleQuality;
                imagepng($this->imageResized, $path . $this->imageNewName, $invertScaleQuality); // 0 is best - 9 is poor
                break;
            default:
                $this->error = 'Ошибка в хранении изображений';
                break;
        }

        imagedestroy($this->imageResized);
        
        return $this->imageNewName;
    }



    /* Internal functions */

    private function getOptimalDimensions($newWidth, $newHeight, $option)
    {
        /* choose optional size by selected option
         * returns array
         **/

        switch ($option)
        {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function getSizeByFixedHeight($newHeight)
    {
        // returns integer

        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;

        return $newWidth;
    }

    private function getSizeByFixedWidth($newWidth)
    {
        // returns integer

        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;

        return $newHeight;
    }

    private function getSizeByAuto($newWidth, $newHeight)
    {
        // returns array

        // Image to be resized is wider (landscape)
        if ($this->height < $this->width) {
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        }

        // Image to be resized is taller (portrait)
        elseif ($this->height > $this->width) {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }

        // Image to be resizerd is a square
        else {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                // *** Sqaure being resized to a square
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function getOptimalCrop($newWidth, $newHeight)
    {
        // returns array

        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;

        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }

        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        /* used for `resize()` function
         * returns null
         **/

        try {
            // Find center - this will be used for the strart point for crop
            $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
            $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

            $crop = $this->imageResized;
            // imagedestroy($this->imageResized);

            // crop from center to exact requested size
            $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
            imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
        }
        catch (Exception $e) {
            $this->error = 'cropError : '.$e->getMessage();
        }
    }

}
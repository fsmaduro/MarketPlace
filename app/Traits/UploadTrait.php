<?php

namespace App\Traits;

trait UploadTrait
{
    private function imageUpload($images, $directory, $imageColumn = null)
    {
        $uploadedImages = [];

        if(is_array($images)) {
            foreach($images as $image) {
                $uploadedImages[] = [$imageColumn => $image->store($directory, 'public')];
            }
        } else {
            $uploadedImages = $images->store($directory, 'public');
        }

        return $uploadedImages;
    }
}

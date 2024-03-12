<?php

function saveUserImage($image)
{
    if ($image) {
        $imagePath = "user_image/" . rand() . "_" . time() . ".png";
        file_put_contents(public_path($imagePath), file_get_contents($image));
        return $imagePath;
    } else {
        return 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png';
    }
}


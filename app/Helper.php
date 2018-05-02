<?php
/**
 * Created by PhpStorm.
 * User: Dozie
 * Date: 4/25/2018
 * Time: 6:11 PM
 */

namespace App;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class Helper
{
    public static function getUserId(){
        $admin = User::hasRole('admin')->first();
        return $admin->id;
    }

    /**
     * @return array
     */
    public static function getStaffRoles(){
        return [
            'sales',
            'supervisor'
        ];
    }

    /**
     * @param $folderUrl
     * @param $img
     * @return string
     */
    public static function saveImage($folderUrl, UploadedFile $img){

        if (!File::exists($folderUrl)) {
            File::makeDirectory($folderUrl, 777, true);
        }

        $filename = time().'.'.$img->getClientOriginalExtension();
        $relUrl = $folderUrl . $filename;
        $filePath = public_path($relUrl);

        Image::make($img->getRealPath())->fit(200, 200)->save($filePath);
        return $relUrl;
    }

    /**
     * @param $data
     * @param string $message
     * @param bool $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseJson($data, string $message, bool $status){
        $body = [
            'success' => $status,
            'message' => $message
        ];

        if(!is_null($data)){
            $body['data'] = $data;
        }

        return response()->json($body);
    }

    /**
     * @param $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public static function invalid($validator){
        $message = implode(', ', $validator->errors()->all());

        return self::responseJson(null, $message, false);
    }
}
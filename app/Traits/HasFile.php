<?php namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile ;

/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 19.12.2017
 * Time: 11:09
 */


trait HasFile{
	public $file_field = 'spec_file';

	public function deleteSpecFile($upload_url = null) {
		if(!$this->{$this->file_field}) return;

		if(!$upload_url){
			$upload_url = self::UPLOAD_SPEC_URL;
		}

		@unlink(public_path($upload_url . $this->{$this->file_field}));
	}

	public function getFileSrcAttribute() {
		return $this->{$this->file_field} ? url(self::UPLOAD_SPEC_URL . $this->{$this->file_field}) : null;
	}

    /**
     * Converts bytes into human readable file size.
     *
     * @param string $bytes
     * @return string human readable file size (2,87 Мб)
     * @author Mogilev Arseny
     */
    public function fileSizeConvert(string $bytes): string {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "тб",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "гб",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "мб",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "кб",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "б",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    public function getFileSizeAttribute() {
        $fileUrl = public_path(self::UPLOAD_SPEC_URL . $this->{$this->file_field});
        if(file_exists($fileUrl)) {
            $size = filesize($fileUrl);
            return $this->fileSizeConvert($size);
        }
        return 0;

    }

	/**
	 * @param UploadedFile $file
	 * @return string
	 */
	public static function uploadFile(UploadedFile $file, $name): string {
		$file_name = $name . '.' . Str::lower($file->getClientOriginalExtension());
        $file->move(public_path(self::UPLOAD_SPEC_URL), $file_name);
		return $file_name;
	}
}

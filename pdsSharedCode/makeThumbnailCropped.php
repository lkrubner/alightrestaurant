<?php


	function makeThumbnailCropped ($srcfile, $dstfile) {
		$src_path = $this->upload_path() . "/$srcfile";
		if (!file_exists ($src_path)) return FALSE;
		$dst_path = $this->upload_path() . "/$dstfile";
		list ($w, $h, $type, $attr) = getimagesize ($src_path);
		if ($w > $h) $scale = ((float)THUMB_SIDE) / $w;
		else $scale = ((float)THUMB_SIDE) / $h;
		$dw = (int) ($w * $scale);
		$dh = (int) ($h * $scale);
		$dst = imagecreatetruecolor ($dw, $dh);
		if ($type == 1) { # GIF
			$src = imagecreatefromgif ($src_path);
			imagecopyresampled ($dst, $src, 0, 0, 0, 0,
						$dw, $dh, $w, $h);
			return imagegif ($dst, $dst_path);
			}
		else if ($type == 2) { #JPG
			$src = imagecreatefromjpeg ($src_path);
			imagecopyresampled ($dst, $src, 0, 0, 0, 0,
						$dw, $dh, $w, $h);
			return imagejpeg ($dst, $dst_path);
			}
		else if ($type == 3) { #PNG
			$src = imagecreatefrompng ($src_path);
			imagecopyresampled ($dst, $src, 0, 0, 0, 0,
						$dw, $dh, $w, $h);
			return imagepng ($dst, $dst_path);
			}
		else if ($type == 6) { #BMP
			$src = imagecreatefromwbmp ($src_path);
			imagecopyresampled ($dst, $src, 0, 0, 0, 0,
						$dw, $dh, $w, $h);
			return imagewbmp ($dst, $dst_path);
			}
		else return FALSE;
		}


?>
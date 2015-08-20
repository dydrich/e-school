<?php 

/**
 * class that represents a wrapper for mime content types
 * @author cravenroad17@gmail.com
 *
 */
class MimeType{
	
	/**
	 * return the mime type for file
	 * @param string $file
	 * @return array (ctype, description, image)
	 */
	static function getMimeContentType($file){
	  
		$tmp_ext = array();
		$ext = "";
		$ctype = "";
		$image = "";
		$tmp_ext = explode(".", $file);
		$ext = strtolower($tmp_ext[(count($tmp_ext) - 1)]);
		$ret = array();
		switch($ext){
			/*
			 * text files
			*/
			case "html":
			case "htm":
			case "text/html":
				$ctype = "text/html";
				$tipo = "Pagina HTML";
				$image = "text-html.png";
				break;
			case "h":
			case "c":
			case "c++":
			case "cpp":
				$ctype = "text/plain";
				$tipo = "Sorgente C";
				$image = "text-c.png";
				break;
			case "pl":
			case "txt":
			case "css":
			case "js":
			case "bat":
			case "sh":
			case "csv":
			case "text/plain":
				$ctype = "text/plain";
				$tipo = "File di testo";
				$image = "text-plain.png";
				break;
				/*
				 * images
				*/
			case "gif":
			case "image/gif":
				$ctype = "image/gif";
				$tipo = "Immagine gif";
				$image = "image-gif.png";
				break;
			case "png":
			case "image/x-png":
				$ctype = "image/x-png";
				$tipo = "Immagine png";
				$image = "image-png.png";
				break;
			case "jpeg":
			case "jpg":
			case "jpe":
			case "image/jpeg":
				$ctype = "image/jpeg";
				$tipo = "Immagine jpeg";
				$image = "image-jpg.png";
				break;
			case "tiff":
			case "tif":
			case "image/tiff":
				$ctype = "image/tiff";
				$tipo = "Immagine tif";
				$image = "image-tiff.png";
				break;
			case "bmp":
			case "image/x-ms-bmp":
				$ctype = "image/x-ms-bmp";
				$tipo = "Immagine bmp";
				$image = "image-bmp.png";
				break;
				/*
				 * audio files
				*/
			case "au":
			case "snd":
			case "audio/basic":
				$ctype = "audio/basic"; 
				$tipo = "File audio";
				$image = "audio-x-generic.png";
				break;
			case "aifc":
			case "aiff":
			case "aif":
			case "audio/x-aiff":
				$ctype = "audio/x-aiff";
				$tipo = "File audio";
				$image = "audio-x-generic.png";
				break;
			case "wav":
			case "audio/x-wav":
				$ctype = "audio/x-wav";
				$tipo = "File audio";
				$image = "audio-x-generic.png";
				break;
			case "mpega":
			case "abs":
			case "mpa":
			case "audio/x-mpeg":
				$ctype = "audio/x-mpeg";
				$tipo = "File audio";
				$image = "audio-x-generic.png";
				break;
			case "mpa2":
			case "mp2a":
			case "audio/x-mpeg-2":
				$ctype = "audio/x-mpeg-2";
				$tipo = "File audio";
				$image = "audio-x-generic.png";
				break;
			case "mp3":
				$ctype = "audio/mpeg";
				$tipo = "File mp3";
				$image = "audio-x-generic.png";
				break;
			case "ogg":
				$ctype = "audio/ogg";
				$tipo = "File ogg";
				$image = "audio-x-generic.png";
				break;
				/*
				 * video files
				*/
			case "mpeg":
			case "mpg":
			case "mpe":
			case "video/mpeg":
				$ctype = "video/mpeg";
				$tipo = "Video mpeg";
				$image = "video-x-generic.png";
				break;
			case "mp2v":
			case "mpv2":
			case "video/mpeg-2":
				$ctype = "video/mpeg-2";
				$tipo = "Video mpeg 2";
				$image = "video-x-generic.png";
				break;
			case "mov":
			case "qt":
			case "video/quicktime":
				$ctype = "video/quicktime";
				$tipo = "QuickTime";
				$image = "video-x-generic.png";
				break;
			case "avi":
			case "video/x-msvideo":
				$ctype = "video/x-msvideo";
				$tipo = "Video AVI";
				break;
			case "movie":
			case "video/x-sgi-movie":
				$ctype = "video/x-sgi-movie";
				$tipo = "Video";
				$image = "video-x-generic.png";
				break;
				/*
				 * open document
				*/
			case "odt":
				$ctype = "application/vnd.oasis.opendocument.text";
				$tipo = "File OpenDocument text: OO.org - LibreOffice Writer";
				$image = "libreoffice-writer.png";
				break;
			case "ott":
				$ctype = "application/vnd.oasis.opendocument.text-template";
				$tipo = "File OO.org - LibreOffice Writer Template";
				$image = "libreoffice-writer.png";
				break;
			case "ods":
				$ctype = "application/vnd.oasis.opendocument.spreadsheet";
				$tipo = "File OO.org - LibreOffice Calc";
				$image = "libreoffice-calc.png";
				break;
			case "ots":
				$ctype = "application/vnd.oasis.opendocument.spreadsheet-template";
				$tipo = "File OO.org - LibreOffice Calc Template";
				$image = "libreoffice-calc.png";
				break;
			case "odp":
				$ctype = "application/vnd.oasis.opendocument.presentation";
				$tipo = "File OO.org - LibreOffice Impress";
				$image = "libreoffice-impress.png";
				break;
				 
				/*
				 * binary files (applications)
				*/
			case "ai":
			case "eps":
			case "ps":
			case "application/postscript":
				$ctype = "application/postscript";
				$tipo = "File Postscript";
				$image = "image-x-psd.png";
				break;
			case "rtf":
			case "application/rtf":
				$ctype = "application/rtf";
				$tipo = "Rich Text File";
				$image = "text-plain.png";
				break;
			case "pdf":
			case "application/pdf":
				$ctype = "application/pdf";
				$tipo = "File pdf";
				$image = "application-pdf.png";
				break;
			case "pps":
			case "ppt":
			case "application/vnd.ms-powerpoint":
				$ctype = "application/ms-powerpoint";
				$tipo = "Presentazione PowerPoint";
				$image = "application-vnd.ms-powerpoint.png";
				break;
			case "pptx":
				$ctype = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
				$tipo = "Presentazione PowerPoint";
				$image = "application-vnd.ms-powerpoint.png";
				break;
			case "doc":
			case "docx":
			case "application/ms-word":
				$ctype = "application/ms-word";
				$tipo = "File MS Word";
				$image = "application-msword.png";
				break;
			case "xls":
			case "application/vnd.ms-excel":
				$ctype = "application/vnd.ms-excel";
				$tipo = "File MS Excel";
				$image = "application-vnd.ms-excel.png";
				break;
			case "xslx":
				$ctype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
				$tipo = "File MS Excel";
				$image = "application-vnd.ms-excel.png";
				break;
			case "mdb":
			case "application/ms-access":
				$ctype = "application/ms-access";
				$tipo = "Database Access";
				$image = "application-x-msdownload.png";
				break;
				/*
				 * compressed files
				*/
			case "gtar":
			case "application/x-gtar":
				$ctype = "application/x-gtar";
				$tipo = "Archivio gtar";
				$image = "application-x-tar.png";
				break;
			case "tar":
			case "application/x-tar":
				$ctype = "application/x-tar";
				$tipo = "Archivio tar";
				$image = "application-x-tar.png";
				break;
			case "shar":
			case "application/x-shar":
				$ctype = "application/x-shar";
				$tipo = "Archivio";
				$image = "application-x-tar.png";
				break;
			case "zip":
			case "application/zip":
				$ctype = "application/zip";
				$tipo = "Archivio zip";
				$image = "application-x-zip.png";
				break;
			case "gz":
				$ctype = "application/x-gzip";
				$tipo = "Archivio gz";
				$image = "application-x-gzip.png";
				break;
			case "bzip":
			case "bz2":
				$ctype = "application/x-bzip";
				$tipo = "Archivio bzip2";
				$image = "application-x-gzip.png";
				break;
			case "bin":
			case "exe":
			case "com":
			case "application/octet-stream":
				$ctype = "application/octet-stream";
				$tipo = "File eseguibile";
				$image = "application-x-executable.png";
				break;
				/*
				 * default
				*/
			default:
				$ctype = "application/octet-stream"; 
				$image = "multipart-encrypted.png";
				break;
		}
		return array('ctype' => $ctype, 'tipo' => $tipo, 'image' => $image);
	}
}

?>

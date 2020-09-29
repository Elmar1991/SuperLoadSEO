<?php

	/**
	* This file is the main file of SuperLoadSEO
	* Gain SEO power Super loading your files
	*
	* @package   SuperLoadSEO
	* @copyright Copyright (c) 2020 Betridee - Will Gittens ( https://betridee.com )
	* @license   http://opensource.org/licenses/mit-license The MIT License
	* @version   1.0.0
	*/

    class superLoadSEO{

        private function loadFile( $url ){

            $binaryFile = @file_get_contents( $url );
            if( $binaryFile == false ){ die("<h3>Error:</h3>We find some problem coping the file!"); }
            else{ return $binaryFile; }

        }

        private function fileFormat( $url ){

            $data = pathinfo( $url );

            if( isset( $data["extension"] ) ){

                $result["extension"] = $data["extension"];
                $result["binary"] = $this->loadFile( $url );
                return $result;

            }else{

                $result["extension"] = false;
                return $result;

            }

        }

        public function createFile( $url , $maxSize = NULL , $quality = 50 , $cache = 800000, $lastmodified = 1577872800, $webp = 0 ){

            $file = $this->fileFormat( $url );
            $lastmodified = date( "D, d M Y H:i:s" , 1577872800 )." GMT";

            if( $file["extension"] == "jpg" || $file["extension"] == "jpeg" || $file["extension"] == "png" ){

                if( $maxSize !== NULL ){

                    $size = getimagesize( $url );
                    $newPercentWidth = ( $maxSize * 100 ) / $size[0];
                    $newHeight = round(( $size[1] * $newPercentWidth ) / 100);

                    $loadImg = imagecreatefromstring( $file["binary"] );
                    $createImg = imagecreatetruecolor( $maxSize , $newHeight );

                    if( $file["extension"] == "png" ){

                        imagealphablending( $createImg, false );
                        imagesavealpha( $createImg, true ); 

                    }

                    imagecopyresized( $createImg , $loadImg , 0 , 0 , 0 , 0 , $maxSize , $newHeight , $size[0] , $size[1] );                        

                    if( $file["extension"] == "png" && $webp == 0 ){

                        header('Content-Type: image/png');
                        header('Cache-Control: max-age='.$cache);
                        header('Last-Modified: '.$lastmodified);
                        imagepng( $createImg , NULL , 2 );
                        imagedestroy( $createImg );

                    }
                    
                    else if( ( $file["extension"] == "jpg" || $file["extension"] == "jpeg" ) && $webp == 0 ){

                        header('Content-Type: image/jpeg');
                        header('Cache-Control: max-age='.$cache);
                        header('Last-Modified: '.$lastmodified);
                        imagejpeg( $createImg , NULL , $quality );
                        imagedestroy( $createImg );

                    }
                    
                    else if( $webp == 1 ){

                        header('Content-Type: image/webp');
                        header('Cache-Control: max-age='.$cache);
                        header('Last-Modified: '.$lastmodified);
                        imagewebp( $createImg , NULL , $quality );
                        imagedestroy( $createImg ); 

                    }

                }else{

                    die("<h3>Error:</h3>You need to inform the size of the new image when you call the function.<br><i><b>Send the variable GET 'size' in the url call.</b></i>");

                }

            }

            else if( $file["extension"] == "gif" ){

                header('Content-Type: image/gif');
                header('Cache-Control: max-age='.$cache);
                header('Last-Modified: '.$lastmodified);
                echo $file["binary"];

            }            

            else if( $file["extension"] == "css" ){

                header('Content-Type: text/css');
                header('Cache-Control: max-age='.$cache);
                header('Last-Modified: '.$lastmodified);
                echo $file["binary"];

            }

            else if( $file["extension"] == "js" ){

                header('Content-Type: text/javascript');
                header('Cache-Control: max-age='.$cache);
                header('Last-Modified: '.$lastmodified);
                echo $file["binary"];

            }

            else if( $file["extension"] == false ){

                $file_type = get_headers( $url , 1 );

                if( strpos( $file_type["Content-Type"], "text/css" )  !== false  ){

                        header('Content-Type: text/css');
                        header('Cache-Control: max-age='.$cache);
                        header('Last-Modified: '.$lastmodified);
                        echo @file_get_contents( $url );

                }

                else if( strpos( $file_type["Content-Type"], "text/js" )  !== false  ){

                        header('Content-Type: text/js');
                        header('Cache-Control: max-age='.$cache);
                        header('Last-Modified: '.$lastmodified);
                        echo @file_get_contents( $url );

                }                

            }                    

        }

    }

    if( isset( $_GET["url"] ) ){

        if( $_GET["url"] !== ""  ){

            if( isset( $_GET["size"] ) ){ $var_size = $_GET["size"]; }else{ $var_size = NULL; }
            if( isset( $_GET["quality"] ) ){ $var_quality = $_GET["quality"]; }else{ $var_quality = 50; }
            if( isset( $_GET["cache"] ) ){ $var_cache = $_GET["cache"]; }else{ $var_cache = 800000; }
            if( isset( $_GET["lastmodified"] ) ){ $var_lmodif = $_GET["lastmodified"]; }else{ $var_lmodif = 1577872800; }
            if( isset( $_GET["webp"] ) ){ $var_webp = $_GET["webp"]; }else{ $var_webp = 0; }                                    

            $superLoadSEO = new superLoadSEO();
            $superLoadSEO->createFile( urldecode($_GET["url"]) , $var_size , $var_quality , $var_cache , $var_lmodif , $var_webp );

        }else{

            die("<h3>Error:</h3>The variable GET 'url' cannot be empty.");

        }

    }else{

        die("<h3>Error:</h3>You need to send the variable GET 'url' to start this function.");

    }

// EOL

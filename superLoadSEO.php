<?php

	/**
	* This file is the main file of SuperLoadSEO
	* Gain SEO power Super loading your files
	*
	* @package   SuperLoadSEO
	* @copyright Copyright (c) 2020 Betridee - Will Gittens ( https://betridee.com )
	* @license   http://opensource.org/licenses/mit-license The MIT License
	* @version   2.0.0
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

                $file_header = get_headers( $url , 1 );

                if( isset( $file_header["Content-Type"] ) ){

                    if( strpos( $file_header["Content-Type"], "text/css" )  !== false ){

                        $result["extension"] = "css";
                        $result["binary"] = $this->loadFile( $url );
                        return $result;

                    }     

                    else if( strpos( $file_header["Content-Type"], "text/js" )  !== false ){

                        $result["extension"] = "js";
                        $result["binary"] = $this->loadFile( $url );
                        return $result;

                    }    

                    else{

                        die("<h3>Error:</h3>We could not find the file extension!");

                    }                                

                }else{

                    die("<h3>Error:</h3>We could not find the file extension!");

                }

            }

        }

        private function libraryCheck(){

            if( function_exists( "gd_info" ) ){

                $compatibility = gd_info();
                $result["jpg"] = $compatibility["JPEG Support"];
                $result["png"] = $compatibility["PNG Support"];
                $result["webp"] = $compatibility["WebP Support"];

                return $result;

            }else{

                return false;

            }

        }

        public function createFile( $url , $maxSize = NULL , $square = 0 , $quality = 50 , $cache = 800000, $lastmodified = 1577872800, $webp = 0 ){

            $file = $this->fileFormat( $url );
            $lastmodified = date( "D, d M Y H:i:s" , 1577872800 )." GMT";

            if( $file["extension"] == "jpg" || $file["extension"] == "jpeg" || $file["extension"] == "png" ){

                $support = $this->libraryCheck();

                if( $support !== false ){

                    if( $maxSize !== NULL ){

                        $size = getimagesize( $url );
                        $newPercentWidth = ( $maxSize * 100 ) / $size[0];
                        $newHeight = round(( $size[1] * $newPercentWidth ) / 100);

                        $loadImg = imagecreatefromstring( $file["binary"] );
                        
                        if( $square == 1 ){

                            $createImg = imagecreatetruecolor( $maxSize , $maxSize );

                        }else{

                            $createImg = imagecreatetruecolor( $maxSize , $newHeight );

                        }

                        if( $file["extension"] == "png" ){

                            imagealphablending( $createImg, false );
                            imagesavealpha( $createImg, true ); 

                        }

                        if( $square == 1 ){

                            if( $size[1] > $size[0] ){

                                imagecopyresized( $createImg , $loadImg , 0 , 0 , 0 , 0 , $maxSize , $newHeight , $size[0] , $size[1] ); 

                            }else{

                                $diference = $maxSize - $newHeight;
                                $positionX = - ( $diference / 2 );

                                imagecopyresized( $createImg , $loadImg , $positionX , 0 , 0 , 0 , $maxSize + $diference , $newHeight + $diference , $size[0] , $size[1] ); 

                            }

                        }else{

                            imagecopyresized( $createImg , $loadImg , 0 , 0 , 0 , 0 , $maxSize , $newHeight , $size[0] , $size[1] );

                        }                      

                        if( $file["extension"] == "png" && $webp == 0 ){

                            if( $support["png"] == true ){

                                header('Content-Type: image/png');
                                header('Cache-Control: max-age='.$cache);
                                header('Last-Modified: '.$lastmodified);
                                imagepng( $createImg , NULL , 2 );
                                imagedestroy( $createImg );

                            }else{

                                die("<h3>Error:</h3>PNG image support is not installed in your GD library.");

                            }

                        }
                        
                        else if( ( $file["extension"] == "jpg" || $file["extension"] == "jpeg" ) && $webp == 0 ){

                            if( $support["jpg"] == true ){

                                header('Content-Type: image/jpeg');
                                header('Cache-Control: max-age='.$cache);
                                header('Last-Modified: '.$lastmodified);
                                imagejpeg( $createImg , NULL , $quality );
                                imagedestroy( $createImg );

                            }else{

                                die("<h3>Error:</h3>JPG / JPEG image support is not installed in your GD library.");
                                
                            }

                        }
                        
                        else if( $webp == 1 ){

                            if( $support["webp"] == true ){

                                header('Content-Type: image/webp');
                                header('Cache-Control: max-age='.$cache);
                                header('Last-Modified: '.$lastmodified);
                                imagewebp( $createImg , NULL , $quality );
                                imagedestroy( $createImg ); 

                            }else{

                                die("<h3>Error:</h3>WEBP image support is not installed in your GD library.");
                                
                            }

                        }

                    }else{

                        die("<h3>Error:</h3>You need to inform the size of the new image when you call the function.<br><i><b>Send the variable GET 'size' in the url call.</b></i>");

                    }

                }else{

                    die("<h3>Error:</h3>You apparently do not have the GD library installed on your server!");

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

        }

    }

    if( isset( $_GET["url"] ) ){

        if( $_GET["url"] !== ""  ){

            if( isset( $_GET["size"] ) ){ $var_size = $_GET["size"]; }else{ $var_size = NULL; }
            if( isset( $_GET["square"] ) ){ $var_square = 1; }else{ $var_square = 0; }
            if( isset( $_GET["quality"] ) ){ $var_quality = $_GET["quality"]; }else{ $var_quality = 50; }
            if( isset( $_GET["cache"] ) ){ $var_cache = $_GET["cache"]; }else{ $var_cache = 800000; }
            if( isset( $_GET["lastmodified"] ) ){ $var_lmodif = $_GET["lastmodified"]; }else{ $var_lmodif = 1577872800; }
            if( isset( $_GET["webp"] ) ){ $var_webp = $_GET["webp"]; }else{ $var_webp = 0; }                                    

            $superLoadSEO = new superLoadSEO();
            $superLoadSEO->createFile( urldecode($_GET["url"]) , $var_size , $var_square , $var_quality , $var_cache , $var_lmodif , $var_webp );

        }else{

            die("<h3>Error:</h3>The variable GET 'url' cannot be empty.");

        }

    }else{

        die("<h3>Error:</h3>You need to send the variable GET 'url' to start this function.");

    }

// EOL

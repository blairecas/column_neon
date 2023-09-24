<?php

    $img = imagecreatefrompng('./graphics/TTiles.png');
    $width = imagesx($img);
    $height = imagesy($img);
    echo "Image: $width x $height\n";
    $tiles_dx = intval($width / 17);
    $tiles_dy = intval($height / 17);
    echo "Tiles: $tiles_dx x $tiles_dy\n";
    
    // tiles array
    $tilesArray = Array();
    
    // scan image and create array
    for ($tiley=0; $tiley<$tiles_dy; $tiley++)
    {
        for ($tilex=0; $tilex<$tiles_dx; $tilex++)
        {
	        $tile = Array();
	        for ($y=0; $y<32; $y++)
            {
                $res = 0; 
		        for ($x=0; $x<8; $x++)
                {
                    $py = $tiley*17 + 1 + ($y>>1);
		            $px = $tilex*17 + 1 + $x + (($y&1)<<3);
		            $res = ($res >> 2) & 0xFFFF;
                    $rgb_index = imagecolorat($img, $px, $py);
                    $rgba = imagecolorsforindex($img, $rgb_index);
                    $r = $rgba['red'];
                    $g = $rgba['green'];
                    $b = $rgba['blue'];
                    // blue pixel
                    if ($b > 127 && $r < 127 && $g < 127) $res = $res | 0b0100000000000000;
                    // green pixel
                    if ($b < 127 && $r < 127 && $g > 127) $res = $res | 0b1000000000000000;
                    if ($b > 127 && $r < 127 && $g > 127) $res = $res | 0b1000000000000000;
                    if ($b > 127 && $r > 127 && $g > 127) $res = $res | 0b1000000000000000;
                    // red pixel
                    if ($b < 127 && $r > 127 && $g < 127) $res = $res | 0b1100000000000000;
                }
                array_push($tile, $res);
            }
	        $found = array_push($tilesArray, $tile) - 1;
        }
    }
    
    echo "Different tiles count: ".count($tilesArray)."\n";
    
    ////////////////////////////////////////////////////////////////////////////
    
    echo "Writing CPU tiles data ...\n";
    $f = fopen ("inc_cpu_sprites.mac", "w");
    fputs($f, "TilesCpuData:\n");
    $n=0;
    for ($t=0; $t<count($tilesArray); $t++)
    {
	    $tile = $tilesArray[$t];
    	for ($i=0; $i<32; $i++)
	    {
    	    if ($n==0) fputs($f, "\t.word\t");
	        $ww = $tile[$i] & 0xFFFF;
	        fputs($f, decoct($ww));
	        $n++; if ($n<16) fputs($f, ", "); else { $n=0; fputs($f, "\n"); }
        }
    }
    fputs($f, "\n");
    fclose($f);

?>
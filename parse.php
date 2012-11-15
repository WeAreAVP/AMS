<?php

$xml = 'assets/20121105 AA XML Export FTP Group/1323_KGNU_PBCoreXMLBag_20121105/data/cpb-aacip-224-99n2zfc9/pbcore.xml';
echo "<pre>";
//$pbcore_data=simplexml_load_file($xml);
//foreach($pbcore_data as $data) 
{
  echo "<pre>";
  //print_r($data);
}
$contact = array();
$path = "assets/20121105 AA XML Export FTP Group/";

function getDirectory($path = '.', $level = 0)
{

  $ignore = array('cgi-bin', '.', '..', '.DS_Store');

  $dh = @opendir($path);

  while (false !== ( $file = readdir($dh) ))
  {
    if (!in_array($file, $ignore))
    {
      $spaces = str_repeat('&nbsp;', ( $level * 4));
      if (is_dir("$path/$file"))
      {

        echo "<strong>$spaces $file</strong><br />";
        //rename($path."\\".$file, strtolower($path."\\".$file));
        if ($level < 1)
          getDirectory("$path/$file", ($level + 1));
      } else
      {
        echo "$spaces $file<br />";
        $contact[] = $file;
        //rename($path."\\".$file, ($path."\\".$file.".xml"));
      }
    }
  }
  closedir($dh);
}
echo "<pre>";
print_r($contact);
$menifest=array();
getDirectory($path, 0);
foreach ($contact as $key => $value)
{
  if ($value == 'manifest-md5.txt')
  {
    $menifest[] = $value;
  }
}

//echo print
/*$path="assets/20121105 AA XML Export FTP Group/1438_WFCR_PBCoreXMLBag_20121105/data";
function getDirectory( $path = '.', $level = 0 ){

    $ignore = array( 'cgi-bin', '.', '..' );

    $dh = @opendir( $path );
   
    while( false !== ( $file = readdir( $dh ) ) )
    {
        if( !in_array( $file, $ignore ) )
        {
            $spaces = str_repeat( '&nbsp;', ( $level * 4 ) );
            if( is_dir( "$path/$file" ) )
            {
               // echo "<strong>$spaces $file</strong><br />";
                getDirectory( "$path/$file", ($level+1) );
               
            }
            else {
							echo $file_extension=pathinfo($xml, PATHINFO_EXTENSION);
							 if($file_extension=='')
							 {
							 		$contact[$path]=$file;
						   		var_dump(rename($path."\\".$file, ($path."\\".$file.".xml")));
							 }
						
            }
        }  
    }
    closedir( $dh );
		echo "<pre>";
		print_r($contact);
}
getDirectory( $path ,0 );*/

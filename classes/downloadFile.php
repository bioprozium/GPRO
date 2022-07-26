<?php
$newfile = new downloadFile;
downloadFile::DownloadFile("https://ftp.ncbi.nlm.nih.gov/genomes/all/GCF/000/001/215/GCF_000001215.4_Release_6_plus_ISO1_MT/GCF_000001215.4_Release_6_plus_ISO1_MT_genomic.fna.gz","Tmpfile.gz");
class downloadFile
{
    function __construct()
    {
        
    }
    // public static function downloading($source, $dest)
    // {
    //     $curl = curl_init();
    //     //$proxy = '192.168.192.1:3211';
    //     curl_setopt($curl, CURLOPT_URL,"$source");
    //     curl_setopt($curl, CURLOPT_PROXY, null);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl, CURLOPT_VERBOSE, 1);
    //     curl_setopt($curl, CURLOPT_FTP_USE_EPSV, 0);
    //     curl_setopt($curl, CURLOPT_TIMEOUT, 300);
    //     $outfile = fopen($dest, 'wb');
    //     curl_setopt($curl, CURLOPT_FILE, $outfile);
    //     $info = curl_exec($curl);
    //     fclose($outfile);
    //     curl_close($curl);
    // }
    public static function DownloadFile($source, $dest)
    {
        $curl = curl_init();
        $proxy = "192.168.192.1:3211";
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_URL,"$source");
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_FTP_USE_EPSV, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        $outfile = fopen($dest, 'wb');
        curl_setopt($curl, CURLOPT_FILE, $outfile);
        $info = curl_exec($curl);
        fclose($outfile);
        curl_close($curl);
    }
}
// $local_file = "GCF_000001215.4_Release_6_plus_ISO1_MT_genomic.fna.gz";
// $remote_file = "https://ftp.ncbi.nlm.nih.gov/genomes/all/GCF/000/001/215/GCF_000001215.4_Release_6_plus_ISO1_MT/GCF_000001215.4_Release_6_plus_ISO1_MT_genomic.fna.gz";

// function getPage($url) {
//         $ch = curl_init();
//         $proxy = "192.168.192.1:3211";
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//         curl_setopt($ch, CURLOPT_HEADER, false);
//         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_PROXY, $proxy);
//         curl_setopt($ch, CURLOPT_REFERER, $url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//         $result = curl_exec($ch);
//         curl_close($ch);
//         return $result;
// }
// function saveToFile($base, $decode=false, $output_file)
// {
//         $ifp = fopen($output_file, "wb");
//         if ($decode){
//           fwrite($ifp, base64_decode($base));
//         }else{
//           fwrite($ifp, $base);
//         }

//         fclose($ifp);
//         return($output_file);
// }

// $remote_page = getPage($remote_file);

// $saved_file = saveToFile($remote_page , false, $local_file);
?>
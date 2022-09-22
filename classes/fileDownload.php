<?php
ignore_user_abort();
$process = new fileDownloadingProcess;
class fileDownloadingProcess
{
    public $dir;
    public $speciesId;
    public $speciesName;
    public $spFolder;
    public $address;
    public $mainFileName;
    public $genomeFile;
    public $genomeFileCoordinate;
    public $cdsFile;
    public $cdnaFile;
    public $logFile;
    public $fileLocation;
    function __construct()
    {
        $this->dir = "../data/public/";                                                                 //main directory
        $this->speciesName = $_POST["name"];                                                            //Name of Species
        $this->speciesId = $_POST["id"];                                                                //DB id of Species
        $this->spFolder = $this->dir.$this->speciesId."/";                                              //Species file containing folder
        $this->address = $_POST["ftp_path"];                                                            //FILE address
        $this->mainFileName = end(explode("/", $this->address));                                        //FILE basename
        $this->genomeFile = $this->address."/".$this->mainFileName."_genomic.fna.gz";                   //GENOME FILE [url]
        $this->genomeFileCoordinate = $this->address."/".$this->mainFileName."_genomic.gtf.gz";         //GENOME GTF FILE [url]
        $this->cdsFile = $this->address."/".$this->mainFileName."_cds_from_genomic.fna.gz";             //CDS FILE [url]
        $this->cdnaFile = $this->address."/".$this->mainFileName."_rna_from_genomic.fna.gz";            //CDNA FILE [url]
        $this->fileLocation = $this->spFolder.$this->speciesId;                                      //public/id/fileLocation_cds_from_genomic.fna.gz
        $this->preparationProcess();                                                                    //Preparation Process [creating folders and log files]
        $this->downloading();
        $this->unzipingFile();
    }
    public function preparationProcess()
    {
        if(!is_dir($this->spFolder))                                                                    //IF directory does't exits CREATE
        {
            mkdir($this->spFolder);
            $this->logFile = fopen($this->spFolder."$this->speciesId-logFile.txt", "w+");               //Downloading info log file
            fclose($this->logFile);
        }
        else
        {
            exit;
            /* if(true)
            {
                //check cds cdna ... files
            }
            else
            {

            } */
        }
        
    }
    public function downloading()
    {
        $s = $this->downloadFile($this->cdsFile, $this->fileLocation."_cds_from_genomic.fna.gz");
        $status = "cds:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
        $s = $this->downloadFile($this->cdnaFile, $this->fileLocation."_rna_from_genomic.fna.gz");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "cdna:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
        $s = $this->downloadFile($this->genomeFile, $this->fileLocation."_genomic.fna.gz");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "genomic:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
        $s = $this->downloadFile($this->genomeFileCoordinate, $this->fileLocation."_genomic.gtf.gz");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "genomicc:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
    }
    public function unzipingFile()
    {
        $s = $this->unzipGzFile($this->fileLocation."_cds_from_genomic.fna.gz", $this->fileLocation.".cds");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "cdsu:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
        $s = $this->unzipGzFile($this->fileLocation."_rna_from_genomic.fna.gz", $this->fileLocation.".cdna");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "cdnau:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
        $s = $this->unzipGzFile($this->fileLocation."_genomic.fna.gz", $this->fileLocation.".fna");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "genomicu:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
        $s = $this->unzipGzFile($this->fileLocation."_genomic.gtf.gz", $this->fileLocation.".gtf");
        $status = file_get_contents($this->spFolder."$this->speciesId-logFile.txt");
        $status .= "genomiccu:$s;";
        file_put_contents($this->spFolder."$this->speciesId-logFile.txt", $status);
    }
    public function downloadFile($source, $dest)
    {
        $curl = curl_init();
        $proxy = "192.168.192.1:3211";                                                                //PROXY must be activated at university
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_URL,"$source");
        curl_setopt($curl, CURLOPT_PROXY, $proxy);                                                    //PROXY option activation
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_FTP_USE_EPSV, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        $outfile = fopen($dest, 'wb');
        curl_setopt($curl, CURLOPT_FILE, $outfile);
        $info = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            return 0;
        }
        fclose($outfile);
        curl_close($curl);
        return 1;
    }
    public function unzipGzFile($source, $dest)
    {
        $zh = gzopen($source, "r");
        $h = fopen ($dest, "w");
        if(!$zh)
        {
            return 0;
        }
        if(!$h)
        {
            return 0;
        }
        while(($string = gzread($zh, 4096)) != false)
        {
            fwrite($h,$string,strlen($string));
        }
        gzclose($zh);
        fclose($h);
        return 1;
    }
}
?>
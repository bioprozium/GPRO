
<?php
  
  // Initialize a file URL to the variable
  $url = 
  'https://ftp.ncbi.nlm.nih.gov/genomes/all/GCF/000/001/215/GCF_000001215.4_Release_6_plus_ISO1_MT/GCF_000001215.4_Release_6_plus_ISO1_MT_genomic.fna.gz';
    
  // Use basename() function to return the base name of file
  $file_name = basename($url);
    
  // Use file_get_contents() function to get the file
  // from url and use file_put_contents() function to
  // save the file by using base name
  if (file_put_contents($file_name, file_get_contents($url)))
  {
      echo "File downloaded successfully";
  }
  else
  {
      echo "File downloading failed.";
  }

//https://ftp.ncbi.nlm.nih.gov/genomes/all/GCF/000/001/215/GCF_000001215.4_Release_6_plus_ISO1_MT
//https://ftp.ncbi.nlm.nih.gov/genomes/all/GCF/000/001/215/GCF_000001215.4_Release_6_plus_ISO1_MT/GCF_000001215.4_Release_6_plus_ISO1_MT_genomic.fna.gz
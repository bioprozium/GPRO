<?php
class anlListMaker
{
    public $uFiles;
    public $pFiles;
    public $posts;
    public $act;
    function __construct($arr=array())
    {

        $this->posts = $_POST;
        if(array_key_exists("user_files", $arr))
        {
            $this->uFiles = $arr["user_files"];
        }
        if(array_key_exists("tax_files", $arr))
        {
            $this->pFiles = $arr["tax_files"];
        }
        $this->act = array(
            "length"=>"",
            "gc"=>"",
            "cpg"=>"",
            "kmers"=>"",
            "nucl"=>"",
            "ade"=>"",
            "cyt"=>"",
            "gua"=>"",
            "thy"=>"",
            "gene"=>"",
            "trsc"=>"",
            "chr"=>"",
            "str"=>"",
            "mot"=>"",
            "cdnocc"=>"",
            "cdnp"=>"",
            "aa"=>"",
            "aapos"=>""
        );
    }
    function seqProp()
    {
        $list = "";
        $type = "";
        if(!empty($this->uFiles))
        {
            $checked = "";
            foreach($this->uFiles as $id=>$info)
            {
                if(isset($this->posts["sprop"][$id]))
                    $checked = "checked";
                switch ($info["type"]) {
                    case 1:
                        $type = "CDNA";
                        break;
                    case 2:
                        $type = "CDS";
                        break;
                    case 3:
                        $type = "CHROMOSOME";
                        break;
                    case 4:
                        $type = "3'UTR";
                        break;                    
                    case 5:
                        $type = "5'UTR";
                        break;
                    case 6:
                        $type = "CSV DATA";
                        break;
                    case 7:
                        $type = "TABULATED DATA";
                        break;
                }
                $list .= "
                <div class='list-group'>
                    <li class='list-group-item active' aria-current='true'>
                        {$info['name']}
                    </li>
                    <li class='list-group-item'><input class='form-check-input me-1' id='propcheck' type='checkbox' name='sprop[$id][]' value='$type' aria-label='...' $checked>$type</li>
                </div>
                <br>";
            }
        }
        if(!empty($this->pFiles))
        {
            $checked = "";
            foreach($this->pFiles as $id=>$info)
            {
                $cdnacheck = "";
                $cdscheck = "";
                $utr5check = "";
                $utr3check = "";
                if(isset($this->posts["prop"][$id]))
                {
                    foreach($this->posts["prop"][$id] as $i=>$v)
                    {
                        switch($v)
                        {
                            case "cdna":
                                $cdnacheck = "checked";
                                break;
                            case "cds":
                                $cdscheck = "checked";
                                break;
                            case "5utr":
                                $utr5check = "checked";
                                break;
                            case "3utr":
                                $utr3check = "checked";
                                break;
                            default:
                                $cdnacheck = "";
                                $cdscheck = "";
                                $utr5check = "";
                                $utr3check = "";
                        }
                    }
                }
                $list .= "
                <div class='list-group'>
                    <li class='list-group-item active' aria-current='true'>
                        $info[name]
                    </li>
                    <li class='list-group-item'><input class='form-check-input me-1' id='propcheck' type='checkbox' name='prop[$id][]' value='cdna' aria-label='...' $cdnacheck>CDNA</li>
                    <li class='list-group-item'><input class='form-check-input me-1' id='propcheck' type='checkbox' name='prop[$id][]' value='cds' aria-label='...' $cdscheck>CDS</li>
                    <li class='list-group-item'><input class='form-check-input me-1' id='propcheck' type='checkbox' name='prop[$id][]' value='5utr' aria-label='...' $utr5check>5'UTR</li>
                    <li class='list-group-item'><input class='form-check-input me-1' id='propcheck' type='checkbox' name='prop[$id][]' value='3utr' aria-label='...' $utr3check>3'UTR</li>
                </div>
                <br>";
            }
        }
        return $list;
    }
    function seqFunc()
    {
        if(isset($this->posts["action"]))
            $this->act[$this->posts["action"]] = "list-group-item-success";
        $list = "
            <div class='list-group'>
                <li class='list-group-item active' aria-current='true'>
                    Sequence Properties
                </li>
                <button class='list-group-item {$this->act['length']}' name='action' value='length'>Length</button>
                <button class='list-group-item {$this->act['gc']}' name='action' value='gc'>GC-content</button>
                <button class='list-group-item {$this->act['cpg']}' name='action' value='cpg'>CpG-Island</button>
                <button class='list-group-item {$this->act['kmers']}' name='action' value='kmers'>K-Mers</button>
                <button class='list-group-item {$this->act['nucl']}' name='action' value='nucl'>Nucleotide by Position</button>
                <button class='list-group-item {$this->act['ade']}' name='action' value='ade'>Adenine</button>
                <button class='list-group-item {$this->act['cyt']}' name='action' value='cyt'>Cytosine</button>
                <button class='list-group-item {$this->act['gua']}' name='action' value='gua'>Guanine</button>
                <button class='list-group-item {$this->act['thy']}' name='action' value='thy'>Thymine</button>
                <button class='list-group-item {$this->act['gene']}' name='action' value='gene'>Gene Names</button>
                <button class='list-group-item {$this->act['trsc']}' name='action' value='trsc'>Transcript names</button>
                <button class='list-group-item {$this->act['chr']}' name='action' value='chr'>Chromosome</button>
                <button class='list-group-item {$this->act['str']}' name='action' value='str'>Strand</button>
                <button class='list-group-item {$this->act['mot']}' name='action' value='mot'>Motifs</button>
            </div>
            <br>
            <div class='list-group'>
                <li class='list-group-item active' aria-current='true'>
                    Speciel Functions for CDS Sequence
                </li>
                <button class='list-group-item {$this->act['cdnocc']}' name='action' value='cdnocc'>Codon Content</button>
                <button class='list-group-item {$this->act['cdnp']}' name='action' value='cdnp'>Codon Position</button>
                <button class='list-group-item {$this->act['aa']}' name='action' value='aa'>AminoAcid Content</button>
                <button class='list-group-item {$this->act['aapos']}' name='action' value='aapos'>AminoAcid Position</button>
            </div>
        ";

        return $list;
    }
}
?>
<?php
class anlListMaker
{
    public $uFiles;
    public $pFiles;
    function __construct($arr=array())
    {
        if(array_key_exists("user_files", $arr))
        {
            $this->uFiles = $arr["user_files"];
        }
        if(array_key_exists("tax_files", $arr))
        {
            $this->pFiles = $arr["tax_files"];
        }
    }
    function seqProp()
    {
        $list = "";
        $type = "";
        if(!empty($this->uFiles))
        {
            foreach($this->uFiles as $id=>$info)
            {
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
                        $info[name]
                    </li>
                    <li class='list-group-item'><input class='form-check-input me-1' type='checkbox' name='sprop[$id][]' value='$type' aria-label='...'>$type</li>
                </div>
                <br>";
            }
        }
        if(!empty($this->pFiles))
        {
            foreach($this->pFiles as $id=>$info)
            {
                $list .= "
                <div class='list-group'>
                    <li class='list-group-item active' aria-current='true'>
                        $info[name]
                    </li>
                    <li class='list-group-item'><input class='form-check-input me-1' type='checkbox' name='prop[$id][]' value='cdna' aria-label='...'>CDNA</li>
                    <li class='list-group-item'><input class='form-check-input me-1' type='checkbox' name='prop[$id][]' value='cds' aria-label='...'>CDS</li>
                    <li class='list-group-item'><input class='form-check-input me-1' type='checkbox' name='prop[$id][]' value='5utr' aria-label='...'>5'UTR</li>
                    <li class='list-group-item'><input class='form-check-input me-1' type='checkbox' name='prop[$id][]' value='3utr' aria-label='...'>3'UTR</li>
                </div>
                <br>";
            }
        }
        return $list;
    }
    function seqFunc()
    {
        $list = "
            <div class='list-group'>
                <li class='list-group-item active' aria-current='true'>
                    Sequence Properties
                </li>
                <button class='list-group-item' name='action' value='length'>Length</button>
                <button class='list-group-item' name='action' value='gc'>GC-content</button>
                <button class='list-group-item' name='action' value='cpg'>CpG-Island</button>
                <button class='list-group-item' name='action' value='kmers'>K-Mers</button>
                <button class='list-group-item' name='action' value='nucl'>Nucleotide by Position</button>
                <button class='list-group-item' name='action' value='ade'>Adenine</button>
                <button class='list-group-item' name='action' value='cyt'>Cytosine</button>
                <button class='list-group-item' name='action' value='gua'>Guanine</button>
                <button class='list-group-item' name='action' value='thy'>Thymine</button>
                <button class='list-group-item' name='action' value='gene'>Gene Names</button>
                <button class='list-group-item' name='action' value='trsc'>Transcript names</button>
                <button class='list-group-item' name='action' value='chr'>Chromosome</button>
                <button class='list-group-item' name='action' value='str'>Strand</button>
                <button class='list-group-item' name='action' value='mot'>Motifs</button>
            </div>
            <br>
            <div class='list-group'>
                <li class='list-group-item active' aria-current='true'>
                    Speciel Functions for CDS Sequence
                </li>
                <button class='list-group-item' name='action' value='cdnocc'>Codon Content</button>
                <button class='list-group-item' name='action' value='cdnp'>Codon Position</button>
                <button class='list-group-item' name='action' value='aa'>AminoAcid Content</button>
                <button class='list-group-item' name='action' value='aapos'>AminoAcid Position</button>
            </div>
        ";

        return $list;
    }
}
?>
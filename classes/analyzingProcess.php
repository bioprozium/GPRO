<?php
class analyzingProcess
{
    public $actions;
    public $action;
    public $result;
    public $pprop;                                                  //Public files sequence Properties [cds,cdna,...,etc]
    public $sprop;                                                  //User files sequence Properties [cds,cdna,...,etc]
    public $filter;                                                 //used filters
    public $graph;
    function __construct($act, $propArr="",$spropArr="",$filter="")
    {
        //print_r($_POST);
        $this->actions = array(
            "length"=>"length",
            "cpg"=>"cpgIsland",
            "gc"=>"gcContent",
            "nucl"=>"nucleotide",
            "cdnocc"=>"codonOccurrence",
            "cdnp"=>"codonPosition",
            "aa"=>"aminoacids",
            "aapos"=>"aminoacidspos",
            "cdnp"=>"codonposition",
        );
        $this->pprop = $propArr;
        $this->sprop = $spropArr;
        $this->filter = $filter;
        $this->action = $this->actions[$act];
        $this->result = $this->processing($this->action);
        $this->graph = $this->preparationGraph($this->result);
    }
    private function processing($act)
    {
        require_once CLASSES."anlFuncs/$this->action.php";
        $proc = new $act($this->pprop, $this->sprop, $this->filter);
        return $proc->getResult();
    }
    private function preparationGraph($data)
    {
        require_once CLASSES."Graph.php";
        $graph = new Graph($data, $this->action);
        $graph = $graph->getGraph();
        return $graph;
    }
    public function getScript()
    {
        return $this->graph;
    }
}
?>
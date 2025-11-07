<?php
/**
 * FPDF Alpha extension
 * Allows setting transparency (opacity) for text/images.
 * Works with FPDF and FPDI.
 */

class FPDF_Alpha extends FPDF
{
    protected $extgstates = array();

    // Set transparency (alpha) value between 0 (transparent) and 1 (opaque)
    function SetAlpha($alpha, $bm='Normal')
    {
        // Set alpha for stroking/non-stroking operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs',$gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i=1;$i<=count($this->extgstates);$i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_out(sprintf('/ca %.3F /CA %.3F /BM %s',$parms['ca'],$parms['CA'],$parms['BM']));
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach($this->extgstates as $k=>$v)
        {
            $this->_out('/GS'.$k.' '.$v['n'].' 0 R');
        }
        $this->_out('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }
}
?>

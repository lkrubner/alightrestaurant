<?php



function getHidden($type, $id, $attr, $val){
	return "<input name='".getName($type, $id, $attr)."' type='hidden' value='".$val."'>";				
}




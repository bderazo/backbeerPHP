<?php
function removeElementsFromJson($data, $element,$id) {
    $json = json_decode($data, true);
    foreach ($json as $rol=>$value) {
        if($value['categoria'] === $element){
            unset($json[$rol]);
        }
      
    }
    return exitsFromJson(json_encode($json),$id);
}

function exitsFromJson($data,$id) {
    $json = json_decode($data, true);
    foreach ($json as $rol=>$value) {
        if($value['id'] === $id){
           return true;
        }
    }
    return false;
}
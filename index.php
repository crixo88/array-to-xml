<?php
/*
* Array $data
* Array $header it's the first line in the xml
* Array $xml_data used to make the header and first lap
*/
function arrayToXml(array $data, ?string $header = '<?xml version="1.0" encoding="utf-8"?>', ?SimpleXMLElement $xml_data = null):?string {
    // The first time it need to initialize the header and the first tag
    if(!isset($xml_data)){
        $key = array_key_first($data);
        $xml_data = new SimpleXMLElement($header.'<'.$key.'></'.$key.'>');
        arrayToXml($data[$key], null, $xml_data);
         // for this example we got '<response></response>' after the default header
        return $xml_data->asXML();
    }
    // After the first round, search for atrubutes or tag
    foreach( $data as $key => $value ) {
        // if get atributes use the function addAttribute
        if($key == '@attributes'){
            foreach ($value as $attributes => $attrValue) {
                $xml_data->addAttribute("$attributes",htmlspecialchars("$attrValue"));
            }
            continue;
        }
        //else search if the tag if a subnode o a primary node
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key;
            }
            $subnode = $xml_data->addChild($key);
            arrayToXml($value, null, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
    }
    return null;
}

/* 
//for test
$data = [
    'response' => [
        'error' => [
            '@attributes' => [
                'message'=>'mensaje detallando el error'
            ]
        ]
    ]
];
print_r(arrayToXml($data)); 
*/
?>

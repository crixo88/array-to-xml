<?php

function arrayToXml(array $data, ?string $header = '<?xml version="1.0" encoding="utf-8"?>', ?SimpleXMLElement $xml_data = null):?string {
    if(!isset($xml_data)){
        $key = array_key_first($data);
        $xml_data = new SimpleXMLElement($header.'<'.$key.'></'.$key.'>');
        arrayToXml($data[$key], null, $xml_data);
        return $xml_data->asXML();
    }
    foreach( $data as $key => $value ) {
        if($key == '@attributes'){
            foreach ($value as $attributes => $attrValue) {
                $xml_data->addAttribute("$attributes",htmlspecialchars("$attrValue"));
            }
            continue;
        }
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
?>

<?php

class XMLCustomizeParser {
    public $reader = null;
    public $writer = null;
    function __construct() 
    {
        $this->reader = new XMLReader();
        $this->writer = new XMLWriter();
    }

    function XMLCustomizeParser() 
    {
        $this->__construct();
    }

    function start($url, $file, $data = array()) 
    {
        $this->readerOpen($url);
        $this->writer->openURI($file);
        $this->copyFeed($data);
    }

    function readerOpen($url)
    {
        $this->reader->open($url);
    }

    function getHeaderInfo()
    {
        $reader = $this->reader;
        if (XMLReader::NONE == $this->reader->nodeType) {
            $this->reader->read();
        }
        while ($this->reader->nodeType != XMLReader::ELEMENT) {
            $this->reader->read();
        }
        $headers = array();
        do {
            if ($this->reader->name == 'item') {
                break;
            }
            if ($this->reader->nodeType == XMLReader::ELEMENT) {
                 $result = $this->getItemValue($this->reader);
                 if (!empty($result) && is_array($result)) {
                     $headers += $result;
                 }
            }
        } while($this->reader->read());
        return $headers;
    }



    function getItemValue($reader, $stop_item = null)
    {
        $current = $reader->name;
        switch ($reader->nodeType) {
            case XMLReader::SIGNIFICANT_WHITESPACE:
                break;
            case XMLReader::END_ELEMENT:
                break;
            case XMLReader::ATTRIBUTE;
                break;
            case XMLReader::COMMENT:
                break;
            case XMLReader::ELEMENT:
                $key = $reader->name;
                $reader->read();
                $data = $this->getItemValue($reader);
                $reader->read();
                if ($reader->name == $key) {
                    $array[$key] = $data;
                }
                break;
            case XMLReader::DOC:
                break;
            case XMLReader::END_ENTITY:
                break;
            case XMLReader::ENTITY:
                break;
            case XMLReader::ENTITY_REF:
                break;
            case XMLReader::LOADDTD:
                break;
            case XMLReader::NONE:
                break 2;
            case XMLReader::NOTATION:
                break;
            case XMLReader::PI:
                break;
            case XMLReader::TEXT:
                $array = $reader->value;
                break;
            case XMLReader::CDATA:;
                break;
            default:
                //var_dump($reader->nodeType);
        }
            return $array;
    }

    function readNext()
    {
        return $this->reader->next();
    }

    function currentNodeName()
    {
        return $this->reader->name;
    }

    function currentNodeType()
    {
        return $this->reader->nodeType;
    }

    function currentNodeData()
    {
        return $this->toArray($this->reader);
    }

    function startString($xml, $file, $data = array()) 
    {
        $this->reader->xml($xml);
        $this->writer->openURI($file);
        $this->copyFeed($data);
    }

    function copyXML($url, $file)
    {
        $this->readerOpen($url);
        $this->writer->openURI($file);
        if (XMLReader::NONE == $this->reader->nodeType) {
            $this->reader->read();
        }
        do {
            $this->writeNode($this->reader, $this->writer);
        } while($this->reader->read());

        $this->writer->flush();       
    }

    function writeNode(XMLReader $reader, XMLWriter $writer)
    {
        if ( $reader == null )
        {
            return false;
        }

        if ( $writer == null )
        {
            return false;
        }

        switch ( $reader->nodeType )
        {
            case XMLReader::NONE:
              break;
            case XMLReader::ELEMENT:
                $writer->startElement($reader->name);
                if ($reader->hasAttributes) {
                    while($reader->moveToNextAttribute()) {
                        $writer->writeAttribute ($reader->name, $reader->value);
                    }
                }
                if ($reader->isEmptyElement) {
                    $writer->endElement();
                }
                break;
            case XMLReader::WHITESPACE:
            case XMLReader::SIGNIFICANT_WHITESPACE:
            case XMLReader::TEXT:
                  $writer->text( $reader->value);
                  break;
            case XMLReader::CDATA:
                  $writer->writeCData( $reader->value );
                  break;
            case XMLReader::ENTITY_REF:
                  // $writer->writeDTD($reader->name);
                  break;
            case XMLReader::ENTITY:
                // $writer->writeDTDEntity($reader->name, $reader->value);
                break;
            case XMLReader::END_ENTITY:
                break;
            case XMLReader::XML_DECLARATION:
            case XMLReader::PI:
                  $writer->writePI($reader->name, $reader->value);
                  break;
            case XMLReader::DOC_TYPE:
                  //$writer->writeDTD( $reader->Name, $reader->getAttribute( "PUBLIC" ), $reader->getAttribute( "SYSTEM" ), $reader->Value );
                  break;
            case XMLReader::COMMENT:
                  $writer->writeComment( $reader->value);
                  break;
            case XMLReader::END_ELEMENT:
                  $writer->endElement();
                  break;
        }
    }

    function fromArray($data, $writer)
    {
        foreach ($data as $index => $row) {
            if (is_array($row)) {
                if (isset($row['value'])) {
                    $writer->startElement($index);
                    foreach ($row['#attributes'] as $k => $v) {
                        $writer->writeAttribute($k, $v);
                    }
                    $writer->text($row['value']);
                    $writer->endElement();
                } else {
                    foreach($row as $key => $value) {
                        if (isset($value['value'])) {
                            $writer->startElement($index);
                            foreach ($value['#attributes'] as $k => $v) {
                                $writer->writeAttribute($k, $v);
                            }
                            $writer->text($value['value']);
                            $writer->endElement();
                        } else {
                            $writer->writeElement($index, $value);
                        }
                    }
                }
            } else {
                $writer->writeElement($index, $row);
            }
        }
    }

    function toArray($reader, $stop_item = null)
    {
        $array = null;
        $current = $reader->name;
        while (true) {
            if ($stop_item == $reader->name) break;
            switch ($reader->nodeType) {
                case XMLReader::SIGNIFICANT_WHITESPACE:
                    break;
                case XMLReader::END_ELEMENT:
                     if ($reader->name == $current) {
                        break 2;
                     }
                    break;
                case XMLReader::ATTRIBUTE;
                    break;
                case XMLReader::COMMENT:
                    break;
                case XMLReader::ELEMENT:
                    $key = $reader->name;                     
                    if ($current != $key) {
                        $data = $this->toArray($reader, $stop_item);
                        if (is_string($data))
                        {
                            if ($reader->hasAttributes)                                 
                            {
                                $data = array('value' => $data);
                            }
                        }

                        if ($reader->hasAttributes)
                        {
                            while($reader->moveToNextAttribute()) {
                                $data['#attributes'][$reader->localName] = $reader->value;
                            }
                            $reader->moveToElement();
                        }
                        if (true == isset($array[$key]))
                        {
                            if (false === isset($array[$key][0]))
                            {
                                $oldData = $array[ $key ];

                                unset($array[ $key ]);

                                $array[ $key ][] = $oldData;
                            }
                            
                            $array[ $key ][] = $data;
                        } else {
                            $array[ $key ] = $data;
                        }
                    }
                    break;
                case XMLReader::DOC:
                    break;
                case XMLReader::END_ENTITY:
                    break;
                case XMLReader::ENTITY:
                    break;
                case XMLReader::ENTITY_REF:
                    break;
                case XMLReader::LOADDTD:
                    break;
                case XMLReader::NONE:
                    break 2;
                case XMLReader::NOTATION:
                    break;
                case XMLReader::PI:
                    break;
                case XMLReader::TEXT:
                    $array = $reader->value;
                    break;
                case XMLReader::CDATA:;
                    break;
                default:
                    //var_dump($reader->nodeType);
            }
            $reader->read();
        }
        return $array;
    }

    function copyFeed($data = array())
    {
        if (XMLReader::NONE == $this->reader->nodeType) {
            $this->reader->read();
        }
        while ($this->reader->nodeType != XMLReader::ELEMENT) {
            $this->reader->read();
        }
        do {
            if ($this->reader->name == 'item') {
                break;
            }
            $this->writeNode($this->reader, $this->writer);
        } while($this->reader->read());
        $i=0;
        do {
            if ($this->reader->name == 'channel') {
                break;
            } else if ($this->reader->nodeType == XMLReader::ELEMENT) {
                $this->writer->startElement($this->reader->name);
                $arr = $this->toArray($this->reader);
                $this->fromArray($arr, $this->writer);
                $this->writer->endElement();
                $i++;
            } else {
                $this->writeNode($this->reader, $this->writer);
            }
        } while($this->reader->next());
        do {
           $this->writeNode($this->reader, $this->writer);
        } while($this->reader->read());

        $this->writer->flush();
    }
}
?>
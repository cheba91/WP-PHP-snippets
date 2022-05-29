
<?php
function serialize_corrector($serialized_string)
{
    if (@unserialize($serialized_string) !== true &&  preg_match('/^[aOs]:/', $serialized_string)) {
        $serialized_string = preg_replace_callback('/s\:(\d+)\:\"(.*?)\";/s',    function ($matches) {
            return 's:' . strlen($matches[2]) . ':"' . $matches[2] . '";';
        },   $serialized_string);
    }
    return $serialized_string;
}

function replace_links($arr)
{
    if (is_array($arr)) {
        foreach ($arr as &$row) {
            $pattern = '/(uploads\/20[0-2][0-22]\/(?:0[1-9]|1[0-2])\/.*?)/';
            $row = preg_replace($pattern, 'uploads/2022/05/', $row);
            $row = str_replace(['https://url.com', 'https://www.url.com'], '', $row);
        }
    }
    return $arr;
};

function merge_serialized($data1 = '', $data2 = '')
{
    $returnData = false;
    if (!empty($data1) && is_serialized($data1)) {
        $unserialized1 = replace_links(unserialize(serialize_corrector($data1)));
    }
    if (!empty($data2) && is_serialized($data2)) {
        $unserialized2 = replace_links(unserialize(serialize_corrector($data2)));
    }
    // merge & serialize
    if (isset($unserialized1) && isset($unserialized2)) {
        $returnData = serialize(array_merge($unserialized1, $unserialized2));
    } elseif (isset($unserialized1)) {
        $returnData = serialize($unserialized1);
    } elseif (isset($unserialized2)) {
        $returnData = serialize($unserialized2);
    }
    return $returnData;
}

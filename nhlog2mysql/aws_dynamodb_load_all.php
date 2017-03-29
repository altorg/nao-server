<?php
require ('/usr/share/php5/aws.phar');

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$sdk = new Aws\Sdk([
    'region'   => 'us-east-1',
    'version'  => 'latest'
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();
$xlogfile_file = "/opt/nethack/nethack.alt.org/nh360/var/xlogfile";

$tableName = 'altorg-nhlogs';

$handle = fopen($xlogfile_file, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $xlogfile_entry = array();
        $pairs = explode("\t", $line);
	foreach($pairs as $pair) {
            list ($key,$value) = explode("=", $pair);
            $xlogfile_entry[trim($key)] = trim($value);
        }
        $xlogfile_entry['logid'] = md5($xlogfile_entry['name'].$xlogfile_entry['starttime']);
        $json = json_encode($xlogfile_entry);
        // print $json."\n";
        $params = [
          'TableName' => $tableName,
          'Item' => $marshaler->marshalJson($json)
        ];
        try {
            $result = $dynamodb->putItem($params);
        } catch (DynamoDbException $e) {
            echo "Unable to add dynamodb entry:\n";
            echo $e->getMessage() . "\n";
            break;
        }
    }
    fclose($handle);
} else {
    exit("Can't open xlogfile.");
} 

?>



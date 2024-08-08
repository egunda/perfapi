<?php
header('Content-Type: application/json');

if (!isset($_GET['host'])) {
    echo json_encode(['error' => 'Host parameter is required']);
    exit;
}

$host = escapeshellarg($_GET['host']);
$serverHostname = gethostname();

$command = "curl -s -w \"{ \\\"source\\\": \\\"$serverHostname\\\", \\\"target\\\": \\\"$host\\\", \\\"time_namelookup\\\": %{time_namelookup}, \\\"time_connect\\\": %{time_connect}, \\\"time_appconnect\\\": %{time_appconnect}, \\\"time_pretransfer\\\": %{time_pretransfer}, \\\"time_redirect\\\": %{time_redirect}, \\\"time_starttransfer\\\": %{time_starttransfer}, \\\"time_total\\\": %{time_total} }\" -o /dev/null $host -I";

$output = shell_exec($command);

if ($output === null) {
    echo json_encode(['error' => 'Failed to execute curl command']);
    exit;
}

echo $output;
?>

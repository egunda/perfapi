<?php
header('Content-Type: application/json');

if (!isset($_GET['host'])) {
    echo json_encode(['error' => 'Host parameter is required']);
    exit;
}

$host = escapeshellarg($_GET['host']);
$serverHostname = gethostname();

$command = "curl -s -w \"{ \\\"hostname\\\": \\\"$serverHostname\\\",\\\"dns_resolution\\\": %{time_namelookup},\\\"dns_resolution\\\": %{time_namelookup}, \\\"tcp_established\\\": %{time_connect}, \\\"ssl_handshake_done\\\": %{time_appconnect}, \\\"time_pretransfer\\\": %{time_pretransfer}, \\\"time_redirect\\\": %{time_redirect}, \\\"TTFB\\\": %{time_starttransfer}, \\\"time_total\\\": %{time_total} }\" -o /dev/null $host -I";

$output = shell_exec($command);

if ($output === null) {
    echo json_encode(['error' => 'Failed to execute curl command']);
    exit;
}

echo $output;
?>

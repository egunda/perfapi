<?php
header('Content-Type: application/json');

if (!isset($_GET['host'])) {
    echo json_encode(['error' => 'Host parameter is required']);
    exit;
}

$target = $_GET['host'];

// Extract the hostname from the URL or take it as-is if it's already a hostname
$parsedUrl = parse_url($target);
$hostname = isset($parsedUrl['host']) ? $parsedUrl['host'] : $target;

// Escape the hostname for use in the shell commands
$escapedHost = escapeshellarg($hostname);
$serverHostname = gethostname();

// Perform the ping test and calculate the average ping time
$pingOutput = shell_exec("ping -c 4 $escapedHost");
if ($pingOutput === null) {
    echo json_encode(['error' => 'Failed to execute ping command']);
    exit;
}

// Extract the average ping time from the ping output
preg_match('/rtt min\/avg\/max\/mdev = [\d\.]+\/([\d\.]+)\/[\d\.]+\/[\d\.]+ ms/', $pingOutput, $matches);
$avgPing = $matches[1] ?? 'N/A';

// Perform the tcpping test and calculate the average TCP response time
$tcppingOutput = shell_exec("tcpping -x 4 $escapedHost");
if ($tcppingOutput === null) {
    echo json_encode(['error' => 'Failed to execute tcpping command']);
    exit;
}

// Extract the TCP ping times and calculate the average
preg_match_all('/tcp response from .* \[open\]\s+([\d\.]+) ms/', $tcppingOutput, $matches);
$tcppingTimes = $matches[1];
$avgTcpPing = !empty($tcppingTimes) ? array_sum($tcppingTimes) / count($tcppingTimes) : 'N/A';

// Perform the curl command to get the timing metrics
$command = "curl -s -w \"{ \\\"hostname\\\": \\\"$serverHostname\\\", \\\"target\\\": \\\"$target\\\", \\\"avg_ping_ms\\\": $avgPing, \\\"avg_tcp_ping_ms\\\": $avgTcpPing, \\\"time_namelookup\\\": %{time_namelookup}, \\\"time_connect\\\": %{time_connect}, \\\"time_appconnect\\\": %{time_appconnect}, \\\"time_pretransfer\\\": %{time_pretransfer}, \\\"time_redirect\\\": %{time_redirect}, \\\"time_starttransfer\\\": %{time_starttransfer}, \\\"time_total\\\": %{time_total} }\" -o /dev/null $target -I";

$output = shell_exec($command);

if ($output === null) {
    echo json_encode(['error' => 'Failed to execute curl command']);
    exit;
}

echo $output;
?>

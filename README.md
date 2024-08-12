# Perf API

This simpel script written in python is useful in quickly measure HTTP performance matrices from server to a destination host.

## Example Usage

You can test your updated API by making a GET request to the URL where your api.php script is located, with the host parameter:

`bash`

`http://your-server/api.php?host=https://google.com`

This should return a JSON response with the timing metrics and the hostname of the server.

output will be

```
{
  "hostname": "api-server",
  "target": "https://google.com",
  "avg_ping_ms": 1.535,
  "avg_tcp_ping_ms": 0.944,
  "time_namelookup": 0.001352,
  "time_connect": 0.002645,
  "time_appconnect": 0.044656,
  "time_pretransfer": 0.044891,
  "time_redirect": 0,
  "time_starttransfer": 0.052663,
  "time_total": 0.052768
}
```

To install tcping refer 'https://gist.github.com/cnDelbert/5fb06ccf10c19dbce3a7'

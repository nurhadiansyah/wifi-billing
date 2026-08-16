import urllib.request
import json

url = "https://tripay.co.id/api/merchant/payment-channel"
headers = {
    "Authorization": "Bearer 2tmZbOYKAsfs1LqCZOvB04OFIqtqWjz5WJjw1bbh"
}

req = urllib.request.Request(url, headers=headers)
try:
    with urllib.request.urlopen(req) as response:
        print(response.read().decode())
except urllib.error.HTTPError as e:
    print(f"HTTPError: {e.code}")
    print(e.read().decode())

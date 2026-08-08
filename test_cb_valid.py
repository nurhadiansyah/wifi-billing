import requests
import hmac
import hashlib
import json

private_key = 'u5tgN-JTHRn-pakEl-q2pTj-OQdPZ'
url = 'https://dreamnetindonesia.com/tripay/callback'

payload = {
    "reference": "TEST12345",
    "merchant_ref": "INV-TEST",
    "status": "PAID",
    "payment_method": "BCAVA"
}

json_payload = json.dumps(payload, separators=(',', ':'))

signature = hmac.new(
    private_key.encode('utf-8'),
    json_payload.encode('utf-8'),
    hashlib.sha256
).hexdigest()

headers = {
    'x-callback-event': 'payment_status',
    'x-callback-signature': signature,
    'Content-Type': 'application/json',
    'User-Agent': 'Tripay/1.0'
}

print(f"Sending JSON: {json_payload}")
print(f"Signature: {signature}")

response = requests.post(url, data=json_payload, headers=headers)
print("Status:", response.status_code)
print("Response:", response.text)

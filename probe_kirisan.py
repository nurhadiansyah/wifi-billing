import requests

url = "https://api.kirisan.com/v1/send"
headers = {
    "Authorization": "Bearer GJLdRmnR42S82eFBhqlRohjDn02K20K3TupJrqb1amcU6wnh4I",
    "Content-Type": "application/json"
}

channel_id = "2de968ef79800e46e789890a2e28b6a44551d16868e9a6eaabc8b2ef4f757509"

payloads = [
    {
      "channels": {
        "whatsapp": {
          "keys": [channel_id],
          "type": "waba",
          "recipient": "6285256486282",
          "content": {
            "text": "Hello ini tes 1"
          }
        }
      }
    },
    {
      "channels": {
        "waba": {
          "keys": [channel_id],
          "recipient": "6285256486282",
          "content": {
            "text": "Hello ini tes 2"
          }
        }
      }
    },
    {
      "keys": [channel_id],
      "to": "6285256486282",
      "text": "Hello ini tes 3"
    }
]

for i, p in enumerate(payloads):
    try:
        r = requests.post(url, headers=headers, json=p)
        print(f"Test {i+1}:", r.text)
    except Exception as e:
        print("Error:", e)

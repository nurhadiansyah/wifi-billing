import paramiko

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
remote_base = 'domains/dreamnetindonesia.com/public_html'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    stdin, stdout, stderr = client.exec_command(f"cd {remote_base} && grep APP_URL .env")
    print("REMOTE APP_URL:")
    print(stdout.read().decode())
    
    client.close()
except Exception as e:
    print(f"Connection Error: {e}")

import paramiko

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
remote_base = 'domains/dreamnetindonesia.com/public_html'

commands = [
    f"cd {remote_base} && sed -i 's|^APP_URL=.*|APP_URL=https://dreamnetindonesia.com|' .env",
    f"cd {remote_base} && /usr/bin/php artisan config:clear",
    f"cd {remote_base} && /usr/bin/php artisan cache:clear",
]

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    for cmd in commands:
        stdin, stdout, stderr = client.exec_command(cmd)
        print(f"Executed: {cmd}")
        out = stdout.read().decode()
        err = stderr.read().decode()
        if out:
            print(f"Output: {out}")
        if err:
            print(f"Error: {err}")
            
    client.close()
    print("Remote APP_URL updated successfully!")
except Exception as e:
    print(f"Connection Error: {e}")

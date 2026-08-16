import paramiko

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
remote_base = 'domains/dreamnetindonesia.com/public_html'

commands = [
    f"cd {remote_base} && sed -i 's/^TRIPAY_API_KEY=.*/TRIPAY_API_KEY=2tmZbOYKAsfs1LqCZOvB04OFIqtqWjz5WJjw1bbh/' .env",
    f"cd {remote_base} && sed -i 's/^TRIPAY_PRIVATE_KEY=.*/TRIPAY_PRIVATE_KEY=QyMTp-dVd8p-BWTzY-iZtbx-CMhqg/' .env",
    f"cd {remote_base} && sed -i 's/^TRIPAY_MERCHANT_CODE=.*/TRIPAY_MERCHANT_CODE=T52128/' .env",
    f"cd {remote_base} && sed -i 's/^TRIPAY_MODE=.*/TRIPAY_MODE=production/' .env",
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
    print("Remote .env updated successfully!")
except Exception as e:
    print(f"Connection Error: {e}")

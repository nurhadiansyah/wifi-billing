import paramiko
import sys
import re

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
path = 'domains/dreamnetindonesia.com/public_html'

new_api_key = 'DEV-eAnkJF5m6p58hCJ7L48EIE9bvqOzLsjSoCNYJho3'
new_private_key = 'bHHKB-YoApF-tlhH6-l36sh-hKUwx'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    # Read remote .env
    sftp = client.open_sftp()
    remote_file_path = f"{path}/.env"
    with sftp.file(remote_file_path, 'r') as f:
        env_content = f.read().decode('utf-8')
        
    # Replace keys
    env_content = re.sub(r'TRIPAY_API_KEY=.*', f'TRIPAY_API_KEY={new_api_key}', env_content)
    env_content = re.sub(r'TRIPAY_PRIVATE_KEY=.*', f'TRIPAY_PRIVATE_KEY={new_private_key}', env_content)
    
    # Write back
    with sftp.file(remote_file_path, 'w') as f:
        f.write(env_content)
        
    sftp.close()
    
    # Clear cache
    cmd = f"cd {path} && php artisan config:clear && php artisan cache:clear"
    client.exec_command(cmd)
    
    client.close()
    print("API keys updated successfully on server.")
except Exception as e:
    print(f"Error: {e}")

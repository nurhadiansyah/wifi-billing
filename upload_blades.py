import paramiko
import os

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
remote_base = 'domains/dreamnetindonesia.com/public_html'
files_to_upload = {
    r'd:\kang_mus\wifi-billing\app\Http\Controllers\Admin\InvoiceController.php': f'{remote_base}/app/Http/Controllers/Admin/InvoiceController.php',
    r'd:\kang_mus\wifi-billing\resources\views\admin\invoices\index.blade.php': f'{remote_base}/resources/views/admin/invoices/index.blade.php'
}

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    sftp = client.open_sftp()
    
    for local_file, remote_file in files_to_upload.items():
        if os.path.exists(local_file):
            sftp.put(local_file, remote_file)
            print(f"Uploaded {local_file} -> {remote_file}")
        else:
            print(f"File not found: {local_file}")
            
    sftp.close()
    
    # Clear view cache
    cmd = f"cd {remote_base} && php artisan view:clear"
    client.exec_command(cmd)
    
    client.close()
    print("Upload complete!")
except Exception as e:
    print(f"Error: {e}")

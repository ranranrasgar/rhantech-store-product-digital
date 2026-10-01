import urllib.request, json
from base64 import b64encode

def get_mikrotik(path):
    url = f'http://192.168.101.7/rest/{path}'
    request = urllib.request.Request(url)
    auth = b64encode(b'admin:1').decode('ascii')
    request.add_header('Authorization', 'Basic ' + auth)
    res = urllib.request.urlopen(request, timeout=5)
    return json.loads(res.read().decode('utf-8'))

queues = get_mikrotik('queue/simple')
if isinstance(queues, list):
    for q in queues:
        name = q.get('name')
        parent = q.get('parent', 'none')
        print(f"{name}: Parent={parent}")

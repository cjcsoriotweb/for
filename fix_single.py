from pathlib import Path
path=Path('routes/FormateurRoute.php')
data=path.read_bytes()
decoded=data.decode('latin1')
fixed=decoded.encode('latin1').decode('utf-8')
print(list(fixed.encode('utf-8')[10:30]))

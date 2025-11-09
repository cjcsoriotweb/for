from pathlib import Path
path=Path('routes/FormateurRoute.php')
data=path.read_bytes()
decoded=data.decode('latin1')
fixed=decoded.encode('latin1').decode('utf-8')
print('decoded fragment:', repr(decoded[4050:4070]))
print('fixed fragment:', repr(fixed[4050:4070]))
print('decoded marker count', decoded.count(chr(0xc3)))
print('fixed marker count', fixed.count(chr(0xc3)))
